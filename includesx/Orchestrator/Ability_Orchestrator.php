<?php

namespace ORCHESTRATOR_FOR_WP_AI\Orchestrator;

use OrchestratorForWpAiClient\Common\Helpers\Helper;
use WordPress\AI_Client\AI_Client;
use WordPress\AI_Client\Resolvers\Ability_Function_Resolver;
use WordPress\AI_Client\Messages\Message;
use ORCHESTRATOR_FOR_WP_AI\Files\File_Upload_Helper;
use ORCHESTRATOR_FOR_WP_AI\Files\File_Factory;

final class Ability_Orchestrator {

	public function handle(\WP_REST_Request $request): Message|string {

		$text = $_POST['text'] ?? '';

		$uploaded = Helper::uploadFiles();
		$files    = [];

		foreach ( $uploaded as $file ) {
			$ai_file = File_Factory::from_attachment(
				$file['attachment_id']
			);

			if ( $ai_file ) {
				$files[] = $ai_file;
			}
		}

		$prompt = AI_Client::prompt();

		if ( $text !== '' ) {
			$prompt = $prompt->with_user_message( $text );
		}

		foreach ( $files as $file ) {
			$prompt = $prompt->with_user_message(
				Message::from_file( $file )
			);
		}

		$result = $prompt
			->with_abilities_enabled()
			->generate_result();

		if ( $result instanceof Message ) {
			$resolved = Ability_Function_Resolver::resolve_from_message( $result );

			if ( $resolved instanceof Message ) {
				return $resolved;
			}
		}

		return $result;
	}
}
