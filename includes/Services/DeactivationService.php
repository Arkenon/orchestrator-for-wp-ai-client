<?php
/**
 * Activation service class for the plugin
 * @package OrchestratorForWpAiClient
 * @subpackage Services
 * @since 1.0.0
 */

namespace OrchestratorForWpAiClient\Services;

defined('ABSPATH') || exit;

class DeactivationService
{
	public function deactivate(): void
	{
		//Define custom deactivation hook
		do_action('ai_orchestrator_for_abilities_deactivation');
	}
}
