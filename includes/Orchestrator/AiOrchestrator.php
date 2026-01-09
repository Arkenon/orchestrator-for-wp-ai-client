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
use Throwable;
use WordPress\AI_Client\AI_Client;
use WordPress\AI_Client\Builders\Helpers\Ability_Function_Resolver;
use WordPress\AI_Client\Builders\Prompt_Builder;
use WordPress\AiClient\Results\Enums\FinishReasonEnum;
use WP_REST_Request;

final class AiOrchestrator {

	/**
	 * Handle the AI request sent via REST API.
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * Expected to include 'text', 'model', 'temperature' and 'files' parameters.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function handle( WP_REST_Request $request ): array {
		try {

			//Get and sanitize prompt text
			$text = trim( sanitize_textarea_field( $request->get_param( 'text' ) ) ?? '' );

			if ( '' === $text ) {
				return [
					'status'  => 'error',
					'type'    => 'error',
					'message' => __( 'Text parameter is required!', 'orchestrator-for-wp-ai-client' ),
				];
			}

			// Initialize the prompt builder
			$builder = AI_Client::prompt();
			$builder->with_text( $text );

			// If specific model is requested, set it in the builder
			$this->processModelParameter( $builder, $request->get_param( 'model' ) );

			// If temperature is provided, set it in the builder
			$this->processTemperatureParameter( $builder, $request->get_param( 'temperature' ) );

			// Handle file uploads if any files are included in the request
			$this->processAttachedFiles( $builder );

			// Get available abilities and add them to the builder
			$this->processAbilities( $builder );

			// Generate the initial result
			$result        = $builder->generate_result();
			$candidate     = $result->getCandidates()[0] ?? null;
			$message       = $candidate->getMessage();
			$finish_reason = $candidate->getFinishReason();

			$has_ability_calls = Ability_Function_Resolver::has_ability_calls( $message );
			$expects_tool_call = FinishReasonEnum::TOOL_CALLS == $finish_reason;

			// Handle function/ability calls if present in the result
			$get_function_response_parts = [];
			if ( $has_ability_calls && $expects_tool_call ) {
				// Execute abilities/functions and get the response message
				$function_response_message = Ability_Function_Resolver::execute_abilities( $message );
				$get_parts                 = $function_response_message->getParts();

				if ( empty( $get_parts ) ) {
					return [
						'status'  => 'error',
						'type'    => 'error',
						'message' => __( 'Function response message has no parts', 'orchestrator-for-wp-ai-client' ),
					];
				}

				// Extract function response parts
				foreach ( $get_parts as $part ) {
					if ( $part->getType()->isFunctionResponse() ) {
						$get_function_response_parts[] = $part;
					}
				}

				return [
					'status'  => 'success',
					'type'    => 'function_response',
					'message' => $get_function_response_parts,
				];
			} else {
				// No function calls, return the original result
				return [
					'status'  => 'success',
					'type'    => 'text_response',
					'message' => $message->toArray(),
				];
			}
		} catch ( Throwable $e ) {
			return [
				'status'  => 'error',
				'type'    => 'exception',
				'message' => $e->getMessage(),
			];
		}
	}

	/**
	 * Process the model parameter and set it in the prompt builder if valid.
	 *
	 * @param Prompt_Builder $builder
	 * @param $param
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function processModelParameter( Prompt_Builder $builder, $param ) {
		if ( is_string( $param ) && $param !== '' && $param !== 'auto' ) {
			$builder->using_model_preference( sanitize_text_field( $param ) );
		}
	}

	/**
	 * Process the temperature parameter and set it in the prompt builder if valid.
	 *
	 * @param Prompt_Builder $builder
	 * @param $param
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function processTemperatureParameter( Prompt_Builder $builder, $param ) {
		if ( is_numeric( $param ) ) {
			$builder->using_temperature( floatval( $param ) );
		}
	}

	/**
	 * Process attached files from the request and add them to the prompt builder.
	 *
	 * @param Prompt_Builder $builder
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function processAttachedFiles( Prompt_Builder $builder ) {
		$uploads = Helper::uploadFiles();
		if ( ! empty( $uploads ) ) {
			foreach ( $uploads as $upload ) {
				$attachment_id = $upload['attachment_id'] ?? null;
				if ( ! $attachment_id ) {
					continue;
				}

				$file_path = get_attached_file( $attachment_id );

				if ( $file_path && file_exists( $file_path ) ) {
					$mime_type = get_post_mime_type( $attachment_id );
					$builder->with_file( $file_path, $mime_type ?: null );
				}
			}
		}
	}

	/**
	 * Process available abilities and add them to the prompt builder.
	 *
	 * @param Prompt_Builder $builder
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function processAbilities( Prompt_Builder $builder ) {
		$abilities = function_exists( 'wp_get_abilities' ) ? wp_get_abilities() : array();
		if ( ! empty( $abilities ) ) {
			$builder->using_abilities( ...$abilities );
		}
	}
}
