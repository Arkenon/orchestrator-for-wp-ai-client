## AI Orchestrator for WP AI Client

AI Orchestrator for Wp Ai Client and Abilities API provides an explicit execution and orchestration layer
for WordPress AI features.

This plugin is built on top of the official **wp-ai-client** library and integrates
deeply with the **WordPress Abilities API**.

### Key principles:

* Uses wp-ai-client for prompt building and AI communication
* Exposes WordPress abilities to AI as callable tools
* Provides an opt-in orchestration pipeline using
  `Ability_Function_Resolver` (located in wp-ai-client)
* Abilities are added as function declarations using the `using_abilities()` method. 
* Abilities are automatically executed when the tool is called from the AI ​​assistant.
* It returns a generic response that includes AI responses and function call responses.
* Keeps all side-effects under explicit developer control

### Technical Notes:

* wp-ai-client is required and used as the AI transport layer
* Ability execution is performed via `Ability_Function_Resolver` (located in wp-ai-client)
* No MCP server is required
* REST endpoint provided for client-side integrations

## Recommended Tools

### i18n Tools

This plugin uses a variable to store the text domain used when internationalizing strings. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

## License

This plugin is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

This plugin is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

# Credits

Created by Kadim Gültekin

* https://github.com/Arkenon
* https://www.linkedin.com/in/kadim-gültekin-86320a198/
