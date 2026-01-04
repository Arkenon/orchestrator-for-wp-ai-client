<?php

namespace OrchestratorForWpAiClient\Services;


use ORCHESTRATOR_FOR_WP_AI\Orchestrator\Ability_Orchestrator;

defined( 'ABSPATH' ) || exit;

final class RestService {
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

	public function handle( WP_REST_Request $request ) {
		$orchestrator = new Ability_Orchestrator();
		return $orchestrator->handle($request);
	}
}
