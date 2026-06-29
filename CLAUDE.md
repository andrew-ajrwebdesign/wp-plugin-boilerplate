# AJR Plugin Boilerplate — Claude Context

This is the canonical boilerplate for all AJR WordPress plugins. Read this file at the start of every plugin session. It contains everything needed to work on a plugin efficiently without re-exploring the codebase.

---

## Starting a new plugin from this boilerplate

Find-replace these four strings **in this exact order** (order matters — replace the namespace first to avoid partial matches):

| Find | Replace with |
|---|---|
| `AJR\MyPlugin` | `AJR\PluginName` |
| `AJR_My_Plugin` | `AJR_Plugin_Name` |
| `ajr-my-plugin` | `ajr-plugin-name` |
| `ajr_my_plugin` | `ajr_plugin_name` |

Then rename the main plugin file: `ajr-my-plugin.php` → `ajr-plugin-name.php`.

After renaming, run:
```bash
composer install
npm install
./vendor/bin/phpcs   # must be 0 errors
```

---

## Project structure

```
ajr-my-plugin.php          Bootstrap — constants, autoloader, hooks
composer.json              PSR-4 autoload + dev tools
phpcs.xml                  WPCS ruleset
phpunit.xml                PHPUnit config
package.json               wp-scripts (JS/CSS build)
wp-env.json                Local dev environment (npx wp-env start)
uninstall.php              Permanent cleanup (runs on plugin delete)
src/
  Core/
    Plugin.php             Wires all classes together; call init() on each
    Utils.php              Static helpers (mask_sensitive_text, clean_plain_text)
  Admin/
    Settings_Page.php      Settings → Plugin Name admin page
assets/
  css/admin.css            Admin styles (built by wp-scripts)
  js/admin.js              Admin JS (built by wp-scripts)
languages/                 POT/PO/MO translation files
tests/
  bootstrap.php            PHPUnit + WP_Mock bootstrap
  Unit/
    Admin/
      Settings_PageTest.php  Example unit test
.github/
  workflows/
    ci.yml                 PHPCS + PHPUnit on every push/PR
```

---

## Architecture rules (enforce these, never debate them)

- **No singletons.** `Plugin` is instantiated once in the bootstrap — that's it.
- **No hooks in `__construct`.** Hooks go in `init()` methods only.
- **PSR-4 autoloading.** Class files live in `src/`, named exactly as the class (`Settings_Page.php` not `class-settings-page.php`).
- **Late escaping.** Escape immediately before output: `esc_html__()`, `esc_attr()`, `esc_url()`.
- **WP globals need `\` prefix** inside namespaced files: `new \WP_Post()`, `new \WP_Error()`, `new \WP_Query()`. WP functions do NOT need `\`.
- **Conditional asset enqueuing.** Check the hook suffix before enqueuing — never load assets on every admin page.
- **Sanitize on input, escape on output.** Use `sanitize_text_field()`, `absint()`, `wp_kses_post()` etc. for anything stored.

---

## Adding a new feature

1. Create `src/FeatureGroup/My_Feature.php` with namespace `AJR\MyPlugin\FeatureGroup`.
2. Add a docblock with `@package AJR_My_Plugin` and `@since x.x.x`.
3. Add hooks in `init()`, not `__construct()`.
4. Wire it in `src/Core/Plugin.php`: instantiate in `__construct()`, call `init()` in `Plugin::init()`.
5. Write at least one test in `tests/Unit/FeatureGroup/My_Feature_Test.php`.
6. Run `./vendor/bin/phpcs` — must be 0 errors before committing.

---

## Dev commands

```bash
# PHP
composer install           # Install PHP deps + PHPCS/WPCS
./vendor/bin/phpcs         # Lint (must pass before every commit)
./vendor/bin/phpcbf        # Auto-fix many PHPCS issues
./vendor/bin/phpunit       # Run unit tests

# JS/CSS
npm install                # Install JS deps
npm run build              # Production build → assets/
npm run start              # Watch mode for development
npm run lint:js            # ESLint
npm run lint:css           # stylelint

# Local dev
npx wp-env start           # Start WordPress at http://localhost:8888
npx wp-env stop
npx wp-env destroy         # Full reset
```

---

## PHPCS exclusions (intentional — do not remove)

- `WordPress.Files.FileName.InvalidClassFileName` — PSR-4 filenames differ from WP convention
- `WordPress.Files.FileName.NotHyphenatedLowercase` — same reason
- `Universal.Arrays.DisallowShortArraySyntax` — `[]` is valid modern PHP
- `Squiz.Commenting.InlineComment.InvalidEndChar` — commented-out code examples don't need sentence punctuation
