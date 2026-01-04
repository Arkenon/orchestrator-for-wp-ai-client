<?php

namespace ORCHESTRATOR_FOR_WP_AI;

use ORCHESTRATOR_FOR_WP_AI\REST\Orchestrator_REST_Controller;

final class Plugin {

	public static function init(): void {

		// Load textdomain
		load_plugin_textdomain(
			'ai-orchestrator-for-abilities',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages'
		);

		// Register REST endpoints
		add_action(
			'rest_api_init',
			static function () {
				( new Orchestrator_REST_Controller() )->register_routes();
			}
		);
	}
}
