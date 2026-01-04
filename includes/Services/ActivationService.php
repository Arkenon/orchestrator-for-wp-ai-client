<?php
/**
 * Deactivation service class for the plugin
 * @package OrchestratorForWpAiClient
 * @subpackage Services
 * @since 1.0.0
 */

namespace OrchestratorForWpAiClient\Services;

defined('ABSPATH') || exit;

class ActivationService
{
	public function activate(): void
	{
		//Define custom activation hook
		do_action('ai_orchestrator_for_abilities_activation');
	}
}
