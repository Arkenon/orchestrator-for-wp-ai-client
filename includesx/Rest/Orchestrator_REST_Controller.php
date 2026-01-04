<?php

namespace ORCHESTRATOR_FOR_WP_AI\REST;

use WP_REST_Controller;
use WP_REST_Request;
use ORCHESTRATOR_FOR_WP_AI\Orchestrator\Ability_Orchestrator;

final class Orchestrator_REST_Controller extends WP_REST_Controller {

	public function register_routes(): void {

		register_rest_route(
			'ai-orchestrator/v1',
			'/run',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'run' ],
				'permission_callback' => function () {
					return current_user_can( 'prompt_ai' );
				},
			]
		);
	}

	public function run( WP_REST_Request $request ) {
		$orchestrator = new Ability_Orchestrator();
		return $orchestrator->handle();
	}
}
