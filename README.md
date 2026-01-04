# Light Wp Plugin Boilerplate

Plugin boilerplate for WordPress plugin developers.

This boilerplate is based on [Wordpress Create Block](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/)

It supports block development with React, ESNext, and JSX compilation.

Useful for simple and small projects.

If you need a plugin for large projects includes multiple post types, custom fields, custom taxonomies and more, you can use [[Clean Wp Plugin Boilerplate](https://github.com/Arkenon/clean-wp-plugin-boilerplate)]

Download the plugin files from Code -> Download ZIP section or:

Clone the repository via Https: `git clone https://github.com/Arkenon/light-wp-plugin-boilerplate.git`

Then follow these steps:

* change `ai_orchestrator_for_abilities` to `example_me`
* change `ai-orchestrator-for-abilities` to `example-me`
* change `ORCHESTRATOR_FOR_WP_AI` to `Example_Me`
* change `OrchestratorForWpAiClient` to `ExampleMe`
* change `ORCHESTRATOR_FOR_WP_AI` to `EXAMPLE_ME`
* change `aiOrchestratorAbilities` to `exampleMe`
* change `plugin.php` to `example-me.php`


* Install composer dependencies `composer update`


* Install dependencies `npm i`


* Update packages `npm run packages-update`


* Start plugin `npm start`


* Build for production `npm run build`

## How to Register Blocks?

The boilerplate use "/src" folder to create blocks (via @wordpress-scripts package). There is a sample block in "src" folder. You can modify this ore create another custom block.

To register a block:
1) Build your blocks with the "npm run build" command (Make sure your current root is equal to the root of the plugin in the terminal) This command builds all blocks in "src" folder.
2) Go to "inc/class-ai-orchestrator-for-abilities-blocks.php"
3) Register your blocks in the register_ai_orchestrator_for_abilities_blocks() method via the register_block_type() function. To learn more about the Register_block_type() function, visit https://developer.wordpress.org/reference/functions/register_block_type/)

If you want to watch changes in your block you can use 'npm start' command and see the changes immediately.


## Architecture Diagram

```
light-wp-plugin-boilerplate/
│
├── composer.json                           # Composer dependencies and autoload
├── plugin.php                              # Main plugin file, bootstrap ve hooks (convert this to your-ai-orchestrator-for-abilities.php)
├── uninstall.php                           # Plugin uninstall script
├── readme.txt                              # Documentation
│
└── src/first-block/                        # Sample Gutenberg Block (optional)
│
└── build/first-block/                      # Built assets for the sample block
│
└── includes/                               # PSR-4 autoload: OrchestratorForWpAiClient\
    │
    ├── App.php                             # 🚀 Main Application Bootstrap
    │                                       # - Plugin lifecycle management
    │                                       # - Service initialization (plugins_loaded, init hooks)
    │                                       # - Run services from DI container
    │
    ├── Services/                       # 🔧 SERVICES (Plugin Features & Logic)
    │   ├── ActivationService.php       # Plugin activation logic
    │   ├── BlockService.php            # Gutenberg block registration
    │   ├── DeactivationService.php     # Plugin deactivation logic
    │
    ├── Common/                          # 🛠️ SHARED UTILITIES
    │  	├── Helper.php              	 # Sanitization ve utility functions
    │  	├── DI.php                  	# Dependency Injection container setup
    │  	├── Constants.php           	# Plugin constants (paths, URLs, configs)
    │
    └── Presentation/                   # 🎨 PRESENTATION LAYER (UI & Controllers)
        ├── ControllerInit.php          # Controller initialization manager
        │                               # - Admin/Client controller routing
        │
        ├── Admin/                              # WordPress Admin Operations
        │   ├── Controllers/
        │   │   └── AdminController.php         # Admin menu, scripts, styles
        │   ├── Views/
        │   │   └── admin-menu-content.php      # Admin page template
        │   └── Assets/
        │       ├── css/ai-orchestrator-for-abilities-admin.css
        │       └── js/ai-orchestrator-for-abilities-admin.js
        │
        └── Client/                             # Frontend Interface
            ├── Controllers/
            │   ├── ClientController.php        # Frontend scripts ve styles
            │   └── BookController.php          # AJAX endpoints for Book operations
            └── Assets/
                ├── css/ai-orchestrator-for-abilities-client.css
                └── js/ai-orchestrator-for-abilities-client.js
```

## 📚 Detailed Explanation:

### 🚀 **App.php (Bootstrap)**
- Main entry point of the plugin
- Service lifecycle management (`plugins_loaded`, `init` hooks)
- Initialization of services through DI container

### 🔧 **Services**
- WordPress-specific implementations (Custom Fields, Post Types, Taxonomies)
- External service integrations (Mail, Blocks)
- Framework-specific logic

### 🛠️ **Common/Shared**
- **Utilities**: Sanitization, helpers
- **DI Container**: Dependency management with PHP-DI
- **Constants**: Configuration management

### 🎨 **Presentation Layer**
- **Admin Controllers**: WordPress admin area management
- **Client Controllers**: Frontend ve AJAX endpoints
- **Assets**: CSS/JS for admin and client sides

## ✨ Features:
1. **Dependency Injection**: Clean dependency management with PHP-DI
2. **Clean Separation**: Each layer with its own responsibility, loose coupling
3. **WordPress Integration**: Clean interface with native WP APIs

## Generic Prompt for AI Assistants

You must use the **light-wp-plugin-boilerplate** for developing this WordPress plugin.
This boilerplate is based on WordPress Create Block and moder PHP practices,
and it enforces a layered, maintainable structure.

⚙️ Key rules:
- Always respect the boilerplate’s folder structure and layer responsibilities:
	- **Services Layer** → Application Services (Business Logic)
	- **Common Layer** → Shared utilities, DI, helpers, constants
    - **Presentation Layer** → Admin & Client controllers, Views, Assets (CSS/JS)

- Use **PHP-DI** for dependency injection. All services and repositories must be bound in `Common/DI.php`.
- Business logic belongs in the **Services**, never in controllers or repositories.
- Register Post Types, Taxonomies, and Custom Fields only via the **Services**
- For Gutenberg blocks:
	- Place React/JSX code in `/src`
	- Build with `npm run build`
	- Register via `includes/Infrastructure/Services/BlockService.php`
	-
- Always follow the naming convention:
  `ai_orchestrator_for_abilities` for variables
  `ai-orchestrator-for-abilities` for file names, folder names, slugs and text domains
  `OrchestratorForWpAiClient` for class names and namespaces
  `ORCHESTRATOR_FOR_WP_AI`for constants and defines
  `aiOrchestratorAbilities` for JS variables, method and function names

- Always add defined( 'ABSPATH' ) || exit; after namespace section of PHP files.
- Always import used classes with `use` statements after namespace section of PHP files.
- Always document classes and methods/functions at the top of the class (class PhpDoc must include @package, @subpackage, @since tags).
- Always use translation functions `__()` and `_e()` with the text domain `ai-orchestrator-for-abilities`.
- Always sanitize inputs with appropriate `sanitize_*` functions and escape outputs with `esc_*` functions. (Sanitize first, escape later. Always validate.)
- Always use translation functions `__()` and `_e()` with the text domain `ai-orchestrator-for-abilities`.
- Always sanitize inputs with appropriate `sanitize_*` functions and escape outputs with `esc_*` functions.
- Use nonces and capability checks for security in admin actions.
- Check user permissions with `current_user_can()` before sensitive operations.
- Optimize performance by minimizing database queries, using transients for caching, and loading assets conditionally
- Follow WordPress coding standards and PSR-4 autoloading.

🛠 Development workflow:
1. Install composer dependencies: `composer update`
2. Install npm dependencies: `npm i`
3. Update packages: `npm run packages-update`
4. Use `npm start` for development (watch mode)
5. Use `npm run build` for production
6. Follow **GPL v2 or later** license compatibility

Your task: When I describe a feature, requirement, or entity, implement it strictly within this boilerplate’s architecture,
ensuring clean code, separation of concerns, and WordPress best practices.


## Recommended Tools

### i18n Tools

The WordPress Block Plugin Boilerplate uses a variable to store the text domain used when internationalizing strings throughout the Boilerplate. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

* [Poedit](http://www.poedit.net/)
* [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
* [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

## License

The WordPress Block Plugin Boilerplate is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Important Notes

### Licensing

The WordPress Block Plugin Boilerplate is licensed under the GPL v2 or later; however, if you opt to use third-party code that is not compatible with v2, then you may need to switch to using code that is GPL v3 compatible.

For reference, [here's a discussion](http://make.wordpress.org/themes/2013/03/04/licensing-note-apache-and-gpl/) that covers the Apache 2.0 License used by [Bootstrap](http://twitter.github.io/bootstrap/).

# Credits

Created by Kadim Gültekin

* https://github.com/Arkenon
* https://www.linkedin.com/in/kadim-gültekin-86320a198/
