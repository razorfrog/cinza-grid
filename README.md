# Cinza Grid

Cinza Grid is a minimal grid plugin built with [Isotope](https://isotope.metafizzy.co/) by Metafizzy. It makes lightweight and flexible grids that can be easily customized with CSS.

- **WordPress.org:** https://wordpress.org/plugins/cinza-grid/
- **Plugin page:** https://razorfrog.com/cinza-grid/
- **Current release:** 2.0.0 · requires WordPress 5.2 and PHP 7.2 · tested up to WordPress 7.1
- **License:** GPLv2 or later

`readme.txt` is the WordPress.org plugin page (description, FAQ, changelog). For help, use the plugin's support forum on WordPress.org.

## Checks

Every push runs `.github/workflows/check.yml`:

- `php -l` on every PHP file under PHP 7.2 and 8.4
- PHP_CodeSniffer with `phpcs.xml.dist`: PHP 7.2+ compatibility (PHPCompatibilityWP) plus WordPress security sniffs (output escaping, nonces, input sanitizing, prepared SQL, deprecated functions)

Run the same locally with `phpcs` from the repo root once WPCS and PHPCompatibilityWP are installed.
