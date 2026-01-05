<?php
/**
 * Helper class for common functions
 *
 * @package OrchestratorForWpAiClient
 * @subpackage Common\Helpers
 * @since 1.0.0
 */

namespace OrchestratorForWpAiClient\Common;

use WordPress\AiClient\Files\DTO\File;

defined( 'ABSPATH' ) || exit;

class Helper {
	/**
	 * Sanitize input
	 *
	 * @param string $name Input name
	 * @param string $method GET or POST or REQUEST
	 * @param string $type Type of input (title, id, textarea, url, email, username, text, bool)
	 *
	 * @return bool|int|string|null
	 * @since 1.0.0
	 */
	public static function sanitize( string $name, string $method, string $type = "text" ) {

		$value  = "";
		$method = strtolower( $method );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a helper method for sanitization, not directly processing form data
		$input = $method === 'post' ? $_POST : ( $method === 'get' ? $_GET : $_REQUEST );

		if ( isset( $input[ $name ] ) ) {
			$value = wp_unslash( $input[ $name ] );
			$value = is_array( $value ) ? self::sanitizeArray( $value ) : sanitize_text_field( $value );
		}

		if ( isset( $value ) ) {
			switch ( $type ) {
				case "title":
					return sanitize_title( $value );
				case "id":
					return absint( $value );
				case "textarea":
					return sanitize_textarea_field( $value );
				case "url":
					return esc_url_raw( $value );
				case "email":
					return sanitize_email( $value );
				case "username":
					return sanitize_user( $value );
				case "bool":
					return rest_sanitize_boolean( $value );
				case "key":
					return sanitize_key( $value );
				default:
					return $value;
			}
		}

		return null;

	}

	/**
	 * Sanitize array recursively
	 *
	 * @param array $array
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function sanitizeArray( array $array ): array {
		return array_map( function ( $value ) {
			if ( is_array( $value ) ) {
				// @phpstan-ignore-next-line Recursive call for nested arrays
				return self::sanitizeArray( $value );
			}

			return sanitize_text_field( $value );
		}, array_combine(
			array_map( 'sanitize_text_field', array_keys( $array ) ),
			$array
		) );
	}

	/**
	 * Upload files from the request
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function uploadFiles(): array {

		$uploaded = [];

		if ( ! isset( $_FILES['files'] ) ) {
			return $uploaded;
		}

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$files = $_FILES['files'];

		foreach ( $files['name'] as $key => $name ) {

			if ( empty( $name ) ) {
				continue;
			}

			$file = [
				'name'     => $files['name'][ $key ],
				'type'     => $files['type'][ $key ],
				'tmp_name' => $files['tmp_name'][ $key ],
				'error'    => $files['error'][ $key ],
				'size'     => $files['size'][ $key ],
			];

			$upload = wp_handle_upload(
				$file,
				[ 'test_form' => false ]
			);

			if ( isset( $upload['error'] ) ) {
				continue;
			}

			$filename = $upload['file'];
			$filetype = wp_check_filetype( basename( $filename ), null );

			$attach_id = wp_insert_attachment(
				[
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_status'    => 'inherit',
				],
				$filename
			);

			if ( is_wp_error( $attach_id ) ) {
				continue;
			}

			$uploaded[] = [
				'attachment_id' => $attach_id,
				'file_name'     => $name,
			];
		}

		return $uploaded;
	}
}
