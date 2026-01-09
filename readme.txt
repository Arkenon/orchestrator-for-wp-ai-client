=== Orchestrator for Abilities API ===
Contributors:      arkenon
Tags:              wp-ai-client, wp abilities api, ai client, ai orchestrator
Tested up to:      6.9
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html


== Description ==

AI Orchestrator for Abilities API provides an explicit execution and orchestration layer
for WordPress AI features.

This plugin is built on top of the official **wp-ai-client** library and integrates
deeply with the **WordPress Abilities API**.

Key principles:

* Uses wp-ai-client for prompt building and AI communication
* Exposes WordPress abilities to AI as callable tools
* Provides an opt-in orchestration pipeline using
  `Ability_Function_Resolver` (located in wp-ai-client)
* Abilities are added as function declarations using the `using_abilities()` method.
* Abilities are automatically executed when the tool is called from the AI ​​assistant.
* It returns a generic response that includes AI responses and function call responses.
* Keeps all side-effects under explicit developer control


This design mirrors MCP-style tool calling semantics while remaining fully embedded
inside the WordPress runtime.

== Technical Notes ==

* wp-ai-client is required and used as the AI transport layer
* Ability execution is performed via `Ability_Function_Resolver`
* No MCP server is required
* REST endpoint provided for client-side integrations

== Changelog ==

= 1.0.0 =
* Release

== License ==
GPLv2 or later


