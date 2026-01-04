<?php declare( strict_types=1 );
/**
 * Plugin Name:       Orchestrator for WP AI Client
 * Description:       AI Orchestrator for WP AI Client & Abilities API.
 * Requires at least: 6.9
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Kadim Gültekin
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       orchestrator-for-wp-ai-client
 *
 * @package OrchestratorForWpAiClient
 */

defined( 'ABSPATH' ) || exit;

use DI\DependencyException;
use DI\NotFoundException;
use OrchestratorForWpAiClient\App;
use OrchestratorForWpAiClient\Services\ActivationService;
use OrchestratorForWpAiClient\Services\DeactivationService;
use OrchestratorForWpAiClient\Common\DI;


if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

define( 'ORCHESTRATOR_FOR_WP_AI_CLIENT_VERSION', get_file_data( __FILE__, array( 'version' => 'Version' ) )['version'] );
define( 'ORCHESTRATOR_FOR_WP_AI_CLIENT_VERSION_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) . '/' );
define( 'ORCHESTRATOR_FOR_WP_AI_CLIENT_VERSION_PATH', plugin_dir_path( __FILE__ ) );

//Activation
if ( ! function_exists( 'orchestratorForWpAiActivation' ) ) {
	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 * @throws Exception
	 * @since 1.0.0
	 */
	function orchestratorForWpAiActivation() : void {
		DI::container()->get( ActivationService::class )->activate();
	}

	register_activation_hook( __FILE__, 'orchestratorForWpAiActivation' );
}

//Deactivation
if ( ! function_exists( 'orchestratorForWpAiDeactivation' ) ) {
	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 * @throws Exception
	 * @since 1.0.0
	 */
	function orchestratorForWpAiDeactivation() : void {
		DI::container()->get( DeactivationService::class )->deactivate();
	}

	register_deactivation_hook( __FILE__, 'orchestratorForWpAiDeactivation' );
}

//Run plugin
if ( class_exists( App::class ) ) {
	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 * @throws Exception
	 * @since 1.0.0
	 */
	try {
		DI::container()->get( App::class )->run();
	} catch ( DependencyException | Exception $e ) {
		wp_die( $e->getMessage() );
	}
}
