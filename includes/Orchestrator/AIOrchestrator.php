<?php
/**
 * AI Orchestrator class. Responsible for handling requests and coordinating AI-related operations.
 * @package OrchestratorForWpAiClient
 * @subpackage Orchestrator
 * @since 1.0.0
 */

namespace OrchestratorForWpAiClient\Orchestrator;

defined( 'ABSPATH' ) || exit;

use OrchestratorForWpAiClient\Common\Helper;
use WordPress\AI_Client\AI_Client;
use WordPress\AI_Client\Builders\Helpers\Ability_Function_Resolver;
use WordPress\AiClient\Results\DTO\Candidate;
use WordPress\AiClient\Results\DTO\GenerativeAiResult;
use WP_REST_Request;

final class AiOrchestrator {
	private const MAX_FUNCTION_CALL_LOOPS = 10;

	public function handle( WP_REST_Request $request ) : array {
		try {
			$text     = trim( (string) ( $request->get_param( 'text' ) ?? '' ) );
			$uploads  = Helper::uploadFiles();
			$message  = null;
			$loop     = 0;
			$response = [ 'status' => 'error', 'message' => __( 'No valid AI response received', 'orchestrator-for-wp-ai-client' ) ];

			if ( '' === $text && empty( $uploads ) ) {
				return [
					'status'  => 'error',
					'message' => __( 'text or file parameter is required', 'orchestrator-for-wp-ai-client' ),
				];
			}

			$builder = AI_Client::prompt();

			$model_param = $request->get_param( 'model' );
			if ( is_string( $model_param ) && $model_param !== '' && $model_param !== 'auto' ) {
				$builder->using_model_preference( $model_param );
			}

			if ( '' !== $text ) {
				$builder->with_text( $text );
			}

			if ( ! empty( $uploads ) ) {
				foreach ( $uploads as $upload ) {
					$attachment_id = $upload['attachment_id'] ?? null;
					if ( ! $attachment_id ) {
						continue;
					}

					$file_url  = wp_get_attachment_url( $attachment_id );
					$mime_type = get_post_mime_type( $attachment_id );

					if ( $file_url ) {
						$builder->with_file( $file_url, $mime_type ?: null );
					}
				}
			}

			$abilities = function_exists( 'wp_get_abilities' ) ? wp_get_abilities() : array();
			if ( ! empty( $abilities ) ) {
				$builder->using_abilities( ...$abilities );
			}

			$result = $builder->generate_result();

			while ( $result instanceof GenerativeAiResult ) {
				$candidate = $result->getCandidates()[0] ?? null;

				if ( ! $candidate instanceof Candidate ) {
					break;
				}

				$message       = $candidate->getMessage();
				$finish_reason = $candidate->getFinishReason();

				$has_ability_calls = Ability_Function_Resolver::has_ability_calls( $message );
				$expects_tool_call = method_exists( $finish_reason, 'isToolCalls' ) && $finish_reason->isToolCalls();

				if ( ! $has_ability_calls && ! $expects_tool_call ) {
					return [
						'status'  => 'success',
						'message' => $message->toArray(),
					];
				}

				if ( $loop >= self::MAX_FUNCTION_CALL_LOOPS ) {
					return [
						'status'  => 'error',
						'message' => __( 'Function call loop limit exceeded', 'orchestrator-for-wp-ai-client' ),
					];
				}

				$function_response_message = Ability_Function_Resolver::execute_abilities( $message );
				$builder->with_history( $message, $function_response_message );
				$result = $builder->generate_result();
				$loop++;
			}

			return $response;
		} catch ( \Throwable $e ) {
			return [
				'status'  => 'error',
				'message' => $e->getMessage(),
			];
		}
	}
}
