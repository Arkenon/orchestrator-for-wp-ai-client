<?php
/**
 * REST Service for Orchestrator for WP AI Client
 *
 * @package OrchestratorForWpAiClient\Services
 * @subpackage Services
 * @since   1.0.0
 *
 */

namespace OrchestratorForWpAiClient\Services;


use ORCHESTRATOR_FOR_WP_AI\Orchestrator\Ability_Orchestrator;
use OrchestratorForWpAiClient\Orchestrator\AiOrchestrator;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

final class RestService {

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes(): void {

		register_rest_route(
			'ai-orchestrator/v1',
			'/run',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'handle' ],
				'permission_callback' => function () {
					return current_user_can( 'prompt_ai' );
				},
			]
		);
	}

	public function handle( WP_REST_Request $request ): WP_REST_Response {
		$orchestrator = new AiOrchestrator();

		$response = $orchestrator->handle( $request );

		return rest_ensure_response( $response );
	}
}
