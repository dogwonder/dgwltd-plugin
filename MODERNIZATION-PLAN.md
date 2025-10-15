# Plugin Modernization Plan

## Overview

This document outlines the complete modernization plan for the `dgwltd-plugin` from WordPress Plugin Boilerplate (2014) architecture to modern WordPress plugin development standards using PSR-4 autoloading, namespaces, and PHP 8.0+ features.

**Current Version:** 1.0.102
**Target Version:** 2.0.0
**Minimum PHP:** 8.0
**Minimum WordPress:** 6.0

---

## 🎯 Goals

1. **Eliminate manual file includes** - Use Composer PSR-4 autoloading
2. **Remove global namespace pollution** - Implement proper namespaces
3. **Modernize code style** - Use PHP 8.0+ features (typed properties, constructor promotion)
4. **Improve testability** - Implement dependency injection
5. **Better IDE support** - Type hints everywhere
6. **Reduce complexity** - Simpler, more maintainable code
7. **Future-proof** - Follow WordPress.org namespace recommendations

---

## 📊 Current State Analysis

### Current Structure
```
dgwltd-plugin/
├── admin/
│   ├── class-dgwltd-plugin-admin.php
│   └── partials/
├── includes/
│   ├── class-dgwltd-plugin.php
│   ├── class-dgwltd-plugin-loader.php
│   ├── class-dgwltd-plugin-activator.php
│   ├── class-dgwltd-plugin-deactivator.php
│   ├── class-dgwltd-plugin-i18n.php
│   ├── class-dgwltd-plugin-acf.php
│   ├── class-dgwltd-plugin-blocks.php
│   └── class-dgwltd-plugin-rules.php
├── public/
│   └── class-dgwltd-plugin-public.php
├── src/
│   ├── blocks/
│   └── acf-json/
└── dgwltd-plugin.php
```

### Issues Identified
- ❌ 8+ manual `require_once` statements
- ❌ Global class names with `DGWLTD_PLUGIN_` prefix
- ❌ No type declarations
- ❌ Verbose method names with prefixes
- ❌ Manual hook registration system
- ❌ No dependency injection
- ❌ Mixed concerns in class files

---

## 🚀 Target State

### New Structure
```
dgwltd-plugin/
├── src/
│   ├── Core/
│   │   ├── Plugin.php
│   │   ├── Loader.php
│   │   ├── Container.php
│   │   ├── Activator.php
│   │   └── Deactivator.php
│   ├── Admin/
│   │   ├── Admin.php
│   │   └── Enqueue.php
│   ├── Frontend/
│   │   ├── Frontend.php
│   │   └── Enqueue.php
│   ├── Blocks/
│   │   ├── BlockManager.php
│   │   └── Modifiers/
│   │       ├── GalleryModifier.php
│   │       ├── AccordionModifier.php
│   │       └── CodeHighlighter.php
│   ├── ACF/
│   │   ├── ACFManager.php
│   │   ├── BlockRegistration.php
│   │   └── JSONManager.php
│   ├── Rules/
│   │   ├── SiteRules.php
│   │   ├── BlockRestrictions.php
│   │   └── ThemeJSON.php
│   ├── Contracts/
│   │   ├── Hookable.php
│   │   └── Registerable.php
│   └── Utilities/
│       └── I18n.php
├── assets/              # Keep existing
├── languages/           # Keep existing
├── tests/               # New - PHPUnit tests
│   ├── Unit/
│   └── Integration/
├── vendor/              # Composer dependencies
├── .phpcs.xml.dist     # New - Coding standards
├── composer.json        # Updated
├── dgwltd-plugin.php   # Simplified bootstrap
└── README.md            # Updated documentation
```

---

## 📅 Implementation Phases

### Phase 1: Foundation Setup (Week 1)

**Goal:** Establish new structure without breaking existing functionality

#### Tasks:

**1.1 Update Composer Configuration**
```json
{
  "name": "dgwltd/plugin",
  "type": "wordpress-plugin",
  "description": "Modern WordPress plugin for DGW.ltd",
  "license": "GPL-2.0+",
  "autoload": {
    "psr-4": {
      "Dgwltd\\Plugin\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Dgwltd\\Plugin\\Tests\\": "tests/"
    }
  },
  "require": {
    "php": ">=8.0"
  },
  "require-dev": {
    "phpunit/phpunit": "^9.0",
    "squizlabs/php_codesniffer": "^3.7",
    "phpstan/phpstan": "^1.10"
  }
}
```

**1.2 Create Directory Structure**
```bash
mkdir -p src/{Core,Admin,Frontend,Blocks/Modifiers,ACF,Rules,Contracts,Utilities}
mkdir -p tests/{Unit,Integration}
```

**1.3 Setup Coding Standards**
- Create `.phpcs.xml.dist` with WordPress Coding Standards
- Configure PHPStan for static analysis
- Add `.editorconfig` for consistency

**1.4 Git Branch Strategy**
```bash
git checkout -b feature/modernize-plugin
```

**Deliverables:**
- [ ] Updated `composer.json`
- [ ] New directory structure created
- [ ] Coding standards configured
- [ ] Feature branch created
- [ ] Run `composer install` successfully

---

### Phase 2: Core Classes Migration (Week 2)

**Goal:** Migrate core functionality to namespaced classes

#### 2.1 Create Core Classes

**File:** `src/Core/Plugin.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Core;

use Dgwltd\Plugin\Admin\Admin;
use Dgwltd\Plugin\Frontend\Frontend;
use Dgwltd\Plugin\Blocks\BlockManager;
use Dgwltd\Plugin\ACF\ACFManager;
use Dgwltd\Plugin\Rules\SiteRules;

class Plugin {
    private Loader $loader;

    public function __construct(
        private readonly string $version,
        private readonly string $plugin_name = 'dgwltd-plugin'
    ) {
        $this->loader = new Loader();
        $this->init();
    }

    private function init(): void {
        $this->registerI18n();
        $this->registerAdminHooks();
        $this->registerFrontendHooks();
        $this->registerBlockHooks();
        $this->registerACFHooks();
        $this->registerSiteRules();
    }

    public function run(): void {
        $this->loader->run();
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getPluginName(): string {
        return $this->plugin_name;
    }
}
```

**File:** `src/Core/Loader.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Core;

class Loader {
    private array $actions = [];
    private array $filters = [];

    public function addAction(
        string $hook,
        callable $callback,
        int $priority = 10,
        int $accepted_args = 1
    ): void {
        $this->actions[] = compact('hook', 'callback', 'priority', 'accepted_args');
    }

    public function addFilter(
        string $hook,
        callable $callback,
        int $priority = 10,
        int $accepted_args = 1
    ): void {
        $this->filters[] = compact('hook', 'callback', 'priority', 'accepted_args');
    }

    public function run(): void {
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                $hook['callback'],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                $hook['callback'],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
```

**File:** `src/Core/Activator.php` & `src/Core/Deactivator.php`
- Migrate activation/deactivation logic
- Add type hints
- Keep as static methods for WordPress compatibility

**File:** `src/Utilities/I18n.php`
- Migrate internationalization
- Modern method naming

**Tasks:**
- [ ] Create `Plugin.php`
- [ ] Create `Loader.php`
- [ ] Create `Activator.php`
- [ ] Create `Deactivator.php`
- [ ] Create `I18n.php`
- [ ] Run PHPCS on new files
- [ ] Test in isolation

---

### Phase 3: Feature Classes Migration (Week 3)

**Goal:** Migrate Admin, Frontend, Blocks, ACF, and Rules classes

#### 3.1 Admin Classes

**File:** `src/Admin/Admin.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Admin;

class Admin {
    public function __construct(
        private readonly string $plugin_name,
        private readonly string $version
    ) {}

    public function enqueueStyles(): void {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(dirname(__DIR__, 2)) . 'admin/css/dgwltd-plugin-admin.css',
            [],
            $this->version
        );
    }

    public function enqueueBlockEditorAssets(): void {
        // Block editor variations
    }
}
```

#### 3.2 Frontend Classes

**File:** `src/Frontend/Frontend.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Frontend;

class Frontend {
    public function __construct(
        private readonly string $plugin_name,
        private readonly string $version
    ) {}

    public function enqueueScripts(): void {
        // Frontend script enqueuing
    }

    public function addTypeAttribute(string $tag, string $handle, string $src): string {
        if ($handle === $this->plugin_name) {
            return str_replace(' src', ' type="module" src', $tag);
        }
        return $tag;
    }
}
```

#### 3.3 Block Management

**File:** `src/Blocks/BlockManager.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Blocks;

use Dgwltd\Plugin\Blocks\Modifiers\{
    AccordionModifier,
    CodeHighlighter
};

class BlockManager {
    private AccordionModifier $accordion;
    private CodeHighlighter $code;

    public function __construct() {
        $this->accordion = new AccordionModifier();
        $this->code = new CodeHighlighter();
    }

    public function modifyAccordion(string $content, array $block, $instance): string {
        return $this->accordion->modify($content, $block, $instance);
    }

    public function addCodeHighlighting(string $content, array $block, $instance): string {
        return $this->code->modify($content, $block, $instance);
    }
}
```

**File:** `src/Blocks/Modifiers/AccordionModifier.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Blocks\Modifiers;

class AccordionModifier {
    public function modify(string $content, array $block, $instance): string {
        $processor = new \WP_HTML_Tag_Processor($content);
        $details_id = 'details-' . get_the_ID();

        while ($processor->next_tag(['tag_name' => 'details'])) {
            $processor->set_attribute('name', $details_id);
        }

        return $processor->get_updated_html();
    }
}
```

**File:** `src/Blocks/Modifiers/CodeHighlighter.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Blocks\Modifiers;

class CodeHighlighter {
    private const SUPPORTED_LANGUAGES = ['html', 'css', 'js', 'php'];

    public function modify(string $content, array $block, $instance): string {
        $processor = new \WP_HTML_Tag_Processor($content);

        while ($processor->next_tag(['class_name' => 'wp-block-code'])) {
            $class = $processor->get_attribute('class');
            $style = str_replace('wp-block-code is-style-', '', $class);

            if ($processor->next_tag(['tag_name' => 'code'], true)) {
                $processor->set_attribute('class', 'language-' . $style);
            }
        }

        return $processor->get_updated_html();
    }
}
```

#### 3.4 ACF Management

**File:** `src/ACF/ACFManager.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\ACF;

class ACFManager {
    public function registerOptionsPage(): void {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title' => 'DGW.ltd Settings',
                'menu_title' => 'DGW Settings',
                'menu_slug'  => 'dgwltd-settings',
                'capability' => 'manage_options',
            ]);
        }
    }

    public function registerBlocks(): void {
        // ACF block registration
    }

    public function loadJSONPaths(array $paths): array {
        $paths[] = DGWLTD_PLUGIN_DIR . 'src/acf-json';
        return $paths;
    }

    public function savePath(string $path): string {
        return DGWLTD_PLUGIN_DIR . 'src/acf-json';
    }
}
```

#### 3.5 Site Rules

**File:** `src/Rules/SiteRules.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Rules;

class SiteRules {
    public function applyThemeJSONFilters(): void {
        // Theme JSON user filters
    }

    public function restrictHeadingLevels(array $args, string $block_type): array {
        if ($block_type === 'core/heading') {
            // Restrict heading levels
        }
        return $args;
    }
}
```

**Tasks:**
- [ ] Create all Admin classes
- [ ] Create all Frontend classes
- [ ] Create BlockManager and Modifiers
- [ ] Create ACFManager
- [ ] Create SiteRules
- [ ] Test each class individually
- [ ] Run PHPCS on all files

---

### Phase 4: Update Main Plugin File (Week 4)

**Goal:** Simplify bootstrap file and wire everything together

**File:** `dgwltd-plugin.php`
```php
<?php
/**
 * Plugin Name: DGW.ltd Plugin
 * Plugin URI: https://dgw.ltd
 * Description: Modern WordPress plugin for DGW.ltd
 * Version: 2.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Dogwonder Ltd
 * Author URI: https://richholman.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: dgwltd-plugin
 * Domain Path: /languages
 *
 * @package Dgwltd\Plugin
 */

declare(strict_types=1);

namespace Dgwltd\Plugin;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('DGWLTD_PLUGIN_VERSION', '2.0.0');
define('DGWLTD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DGWLTD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DGWLTD_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Composer autoloader
if (file_exists(DGWLTD_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once DGWLTD_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>';
        echo 'DGW.ltd Plugin: Composer dependencies not installed. Run <code>composer install</code>.';
        echo '</p></div>';
    });
    return;
}

use Dgwltd\Plugin\Core\{Plugin, Activator, Deactivator};

// Activation/Deactivation hooks
register_activation_hook(__FILE__, [Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [Deactivator::class, 'deactivate']);

// Initialize plugin on plugins_loaded
add_action('plugins_loaded', function() {
    $plugin = new Plugin(DGWLTD_PLUGIN_VERSION);
    $plugin->run();
}, 10);
```

**Tasks:**
- [ ] Update main plugin file
- [ ] Test plugin activation
- [ ] Test plugin deactivation
- [ ] Verify all hooks register correctly
- [ ] Check WordPress Plugin Check plugin compatibility

---

### Phase 5: Testing & Quality Assurance (Week 5)

#### 5.1 Unit Testing

**Create Test Bootstrap:** `tests/bootstrap.php`
```php
<?php
// Load WordPress test environment
$_tests_dir = getenv('WP_TESTS_DIR');
if (!$_tests_dir) {
    $_tests_dir = rtrim(sys_get_temp_dir(), '/\\') . '/wordpress-tests-lib';
}

require_once $_tests_dir . '/includes/functions.php';

// Manually load the plugin
tests_add_filter('muplugins_loaded', function() {
    require dirname(__DIR__) . '/dgwltd-plugin.php';
});

require $_tests_dir . '/includes/bootstrap.php';
```

**Example Test:** `tests/Unit/Core/PluginTest.php`
```php
<?php
declare(strict_types=1);

namespace Dgwltd\Plugin\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Dgwltd\Plugin\Core\Plugin;

class PluginTest extends TestCase {
    private Plugin $plugin;

    protected function setUp(): void {
        $this->plugin = new Plugin('2.0.0');
    }

    public function testGetVersion(): void {
        $this->assertEquals('2.0.0', $this->plugin->getVersion());
    }

    public function testGetPluginName(): void {
        $this->assertEquals('dgwltd-plugin', $this->plugin->getPluginName());
    }
}
```

#### 5.2 Integration Testing

**Test Plan:**
- [ ] Plugin activation/deactivation
- [ ] Admin area functionality
- [ ] Frontend functionality
- [ ] Block rendering
- [ ] ACF integration
- [ ] Hook registration

#### 5.3 Code Quality

```bash
# Run PHPCS
./vendor/bin/phpcs --standard=WordPress src/

# Run PHPStan
./vendor/bin/phpstan analyse src/ --level=5

# Run PHPUnit
./vendor/bin/phpunit
```

**Tasks:**
- [ ] Write unit tests for all classes
- [ ] Run integration tests
- [ ] Fix all PHPCS violations
- [ ] Fix all PHPStan errors
- [ ] Achieve 80%+ code coverage

---

### Phase 6: Cleanup & Documentation (Week 6)

#### 6.1 Remove Old Code

**Deprecation Strategy:**
```php
// Keep old classes temporarily with deprecation notices
class DGWLTD_PLUGIN_BLOCKS {
    public function __construct() {
        _deprecated_function(__CLASS__, '2.0.0', 'Dgwltd\Plugin\Blocks\BlockManager');
    }
}
```

**After 2 releases, remove completely:**
- Delete `includes/` directory (except any needed backwards compatibility)
- Delete `admin/class-dgwltd-plugin-admin.php`
- Delete `public/class-dgwltd-plugin-public.php`

#### 6.2 Update Documentation

**Update README.md:**
```markdown
# DGW.ltd Plugin

Modern WordPress plugin for DGW.ltd with PSR-4 autoloading and namespaces.

## Requirements

- PHP 8.0+
- WordPress 6.0+
- Composer

## Installation

1. Clone the repository
2. Run `composer install`
3. Activate the plugin

## Development

### Code Standards
```bash
composer phpcs
composer phpstan
```

### Testing
```bash
composer test
```
```

**Create CHANGELOG.md:**
```markdown
# Changelog

## [2.0.0] - 2025-XX-XX

### Changed
- **BREAKING:** Migrated to PSR-4 autoloading with namespaces
- **BREAKING:** Minimum PHP version is now 8.0
- Modernized code with PHP 8.0+ features
- Improved class organization and structure
- Added comprehensive type hints

### Added
- Dependency injection container
- Unit and integration tests
- Code quality tools (PHPCS, PHPStan)
- Proper interfaces and contracts

### Deprecated
- Old `DGWLTD_PLUGIN_*` class names (will be removed in 3.0.0)

### Removed
- Manual file includes (replaced with autoloading)
```

**Tasks:**
- [ ] Add deprecation notices to old classes
- [ ] Update README.md
- [ ] Create CHANGELOG.md
- [ ] Update inline documentation
- [ ] Create developer documentation
- [ ] Update composer.json scripts

---

## 🔍 Testing Checklist

### Functionality Testing
- [ ] Plugin activates without errors
- [ ] Plugin deactivates without errors
- [ ] Admin styles load correctly
- [ ] Block editor variations work
- [ ] Frontend scripts load
- [ ] Accordion block modification works
- [ ] Code block highlighting works
- [ ] ACF options page appears
- [ ] ACF blocks register
- [ ] ACF JSON save/load works
- [ ] Site rules apply correctly
- [ ] Heading restrictions work
- [ ] No PHP errors in debug.log
- [ ] No JavaScript console errors

### Compatibility Testing
- [ ] PHP 8.0 compatibility
- [ ] PHP 8.1 compatibility
- [ ] PHP 8.2 compatibility
- [ ] WordPress 6.0+ compatibility
- [ ] Multisite compatibility
- [ ] Theme compatibility

### Performance Testing
- [ ] Plugin load time acceptable
- [ ] Memory usage reasonable
- [ ] No N+1 queries
- [ ] Autoloading works efficiently

---

## 🚨 Rollback Plan

If issues arise during migration:

### Immediate Rollback
```bash
git checkout main
git branch -D feature/modernize-plugin
composer install
```

### Partial Rollback
Keep new structure but restore old classes:
```bash
git checkout main -- includes/
git checkout main -- admin/class-dgwltd-plugin-admin.php
git checkout main -- public/class-dgwltd-plugin-public.php
```

---

## 📦 Composer Scripts

Add to `composer.json`:
```json
{
  "scripts": {
    "phpcs": "phpcs --standard=WordPress src/",
    "phpcbf": "phpcbf --standard=WordPress src/",
    "phpstan": "phpstan analyse src/ --level=5",
    "test": "phpunit",
    "test:coverage": "phpunit --coverage-html coverage/"
  }
}
```

---

## 🎓 Learning Resources

- [WordPress Namespace Guidelines](https://developer.wordpress.org/news/2025/09/implementing-namespaces-and-coding-standards-in-wordpress-plugin-development/)
- [PSR-4 Autoloading](https://www.php-fig.org/psr/psr-4/)
- [PHP 8.0 Features](https://www.php.net/releases/8.0/en.php)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

---

## 📝 Notes

### Breaking Changes in 2.0.0
- Minimum PHP version increased to 8.0
- Class names changed (namespaced)
- Autoloading required (Composer)
- Method signatures updated with types

### Backwards Compatibility
- Deprecated old classes with notices
- Will maintain for 2 releases
- Remove in version 3.0.0

### Future Enhancements
- Add more comprehensive tests
- Implement dependency injection container
- Consider PHP attributes for hooks
- Add REST API endpoints
- Improve block editor integration

---

## ✅ Success Criteria

Migration is complete when:
- [ ] All functionality works as before
- [ ] No PHP errors or warnings
- [ ] PHPCS passes with 0 violations
- [ ] PHPStan passes at level 5
- [ ] 80%+ test coverage
- [ ] Documentation is complete
- [ ] Tested on PHP 8.0, 8.1, 8.2
- [ ] WordPress Plugin Check passes
- [ ] Performance is equal or better

---

**Status:** Not Started
**Last Updated:** 2025-10-13
**Owner:** Rich Holman
**Reviewers:** TBD
