# AJR WordPress Plugin Boilerplate

PSR-4 WordPress plugin scaffold. Clone, rename, build.

## Structure

```
ajr-my-plugin/
├── assets/
│   ├── css/admin.css
│   └── js/admin.js
├── src/
│   ├── Core/
│   │   ├── Plugin.php          ← wires all classes, no singleton
│   │   └── Utils.php           ← static helpers
│   └── Admin/
│       └── Settings_Page.php   ← working settings page example
├── .gitignore
├── composer.json
├── phpcs.xml
├── uninstall.php
└── ajr-my-plugin.php           ← bootstrap only
```

## Starting a New Plugin

**1. Clone and rename**

```bash
git clone git@github.com:andrew-ajrwebdesign/wp-plugin-boilerplate.git my-new-plugin
cd my-new-plugin
rm -rf .git
git init
```

**2. Find and replace the placeholder name** (case-sensitive, do all four)

| Find | Replace with |
|---|---|
| `ajr-my-plugin` | `my-real-plugin` (slug — lowercase, hyphens) |
| `ajr_my_plugin` | `my_real_plugin` (option keys, hook names — lowercase, underscores) |
| `AJR\MyPlugin` | `AJR\RealPlugin` (PHP namespace) |
| `AJR My Plugin` | `My Real Plugin` (human-readable label) |

Rename the bootstrap file too: `mv ajr-my-plugin.php my-real-plugin.php`

**3. Install dependencies**

```bash
composer install
```

**4. Lint**

```bash
./vendor/bin/phpcs
```

Fix any errors before writing plugin logic.

**5. Build**

Add classes to `src/`, wire them in `Plugin::init()`, done.

## Coding Rules

- One class per file, filename matches class name
- No hooks inside `__construct` — use an `init()` method
- Prefix all WP global class instantiations with `\` inside namespaced files:
  `new \WP_Error()`, `new \WP_Query()` — functions don't need it
- Escape late: `esc_html()`, `esc_attr()`, `esc_url()` immediately before output
- Sanitize on input, escape on output, nonces on all state-changing requests
- Pass the version constant to all `wp_enqueue_*` calls

## Adding a New Feature

1. Create `src/{Group}/{Class_Name}.php` with `namespace AJR\MyPlugin\{Group};`
2. Instantiate it in `Plugin::__construct()`
3. Call `->init()` in `Plugin::init()`
4. Run `./vendor/bin/phpcs src/{Group}/{Class_Name}.php` before committing
