<?php
/**
 * Additional Plugin Constants
 * (Base constant defined in the main plugin php file)
 * @package OrchestratorForWpAiClient
 * @subpackage Common
 * @since 1.0.0
 */

namespace OrchestratorForWpAiClient\Common;

defined( 'ABSPATH' ) || exit;

class Constants {
	public const NAME = 'orchestrator_for_wp_ai_client';
	public const INCLUDES_PATH = ORCHESTRATOR_FOR_WP_AI_CLIENT_VERSION_PATH . 'includes/';
	public const INCLUDES_URL = ORCHESTRATOR_FOR_WP_AI_CLIENT_VERSION_URL . '/includes/';
	public const AUTHOR = 'Kadim Gültekin';
	public const AUTHOR_URL = 'https://kadimgultekin.com/';
	public const PLUGIN_URL = 'https://kadimgultekin.com/';
	public const EMAIL = 'info@kadimgultekin.com';
	public const PHP_VERSION = '7.4';
	public const WP_VERSION = '6.9';
}
