=== Cinza Grid ===
Contributors: razorfrog
Tags: grid, post grid, display post, metafizzy, isotope
Requires at least: 5.2
Tested up to: 7.1
Requires PHP: 7.2
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A minimal grid plugin built with Isotope.

== Description ==
Cinza Grid is a minimal grid plugin built with <a href="https://isotope.metafizzy.co/" target="_blank">Isotope</a> by Metafizzy. It makes lightweight and flexible grids that can be easily customized with CSS.

Visit our website to view the <a href="https://razorfrog.com/cinza-grid/" target="_blank">plugin demo</a>.

== Installation ==
Upload the Cinza Grid plugin to your WordPress site, activate it, and start creating grids.

== Screenshots ==
1. Global grid options
2. Grid skin settings

== Changelog ==

Newest first. Dates are YYYY-MM-DD.

= 2.0.0 (2026-10-30) =
Razorfrog is now the developer of Cinza Grid. Settings, layout and filtering work as before.

Changed
* The plugin author and links now point to Razorfrog (razorfrog.com).
* The plugin demo is now at razorfrog.com/cinza-grid.

Removed
* The Cinza button in the editor's Developers box.

= 1.3.0 (2026-10-30) =
Security release. Grid settings, layout and filtering work as before.

Changed
* Tested with WordPress 7.1.

Security
* Filter links in the page address (`#filter=`) now only accept filter class names.

= 1.2.5 (2026-07-02) =
Changed
* Encoded apostrophes are easier to read in the editor's skin field.
* Tested with WordPress 7.0.

Fixed
* Skin meta tags now work when apostrophes are encoded inside HTML attributes.

= 1.2.4 (2025-12-09) =
Changed
* Tested with WordPress 6.9.

Security
* Security fixes.

= 1.2.3 (2025-11-17) =
Changed
* Pages are now included in the Post Type list, and post types starting with `wpb_` are left out.

= 1.2.2 (2025-10-20) =
Security
* Fixed a stored cross-site scripting (XSS) issue by sanitizing and escaping output.

= 1.2.1 (2024-10-22) =
Changed
* Updated the sorting functions.
* Tested with WordPress 6.6.2.

= 1.2.0 (2024-05-22) =
Added
* `%[shortcode]%` skin meta tag.

Changed
* Optimized all skin meta tag functions.

= 1.1.9 (2024-04-08) =
Added
* Setting to limit the number of items shown when a filter is active.

Changed
* Tested with WordPress 6.5.

= 1.1.8 (2024-02-14) =
Changed
* Grids no longer have their own single page on the front end.
* Tested with WordPress 6.4.3.

= 1.1.7 (2023-12-21) =
Added
* Random order in the Order by setting.

Changed
* Tested with WordPress 6.4.2.

= 1.1.6 (2023-08-24) =
Changed
* Re-uploaded the plugin files to WordPress.org.

= 1.1.5 (2023-08-23) =
Added
* `%excerpt%` skin meta tag.

Changed
* Updated how the skin field is sanitized.

= 1.1.4 (2023-08-01) =
Fixed
* Filters now work with buttons that contain slashes.

= 1.1.3 (2023-06-28) =
Fixed
* Filters now work over multiple lines.

= 1.1.2 (2023-06-16) =
Changed
* Minor interface improvements.

= 1.1.1 (2023-05-03) =
Added
* `%img('size')%` and `%imgurl('size')%` skin meta tags.

Changed
* Checkboxes in the editor are now toggles.
* Updated the editor style, with accessibility improvements.
* Tested with WordPress 6.2.

Fixed
* Front-end style for mobile and one-column layouts.
* The front-end script now triggers Isotope correctly.

= 1.1.0 (2023-01-27) =
Changed
* Updated the website and demo links.
* Tested with WordPress 6.1.1.

= 1.0.9 (2022-12-11) =
Added
* Layout modes: fitRows and masonry.

Changed
* Grids no longer have single pages.
* The editor works better on smaller screens.

Fixed
* The "All" filter button placeholder.
* Filter buttons now accept the & character.

= 1.0.8 (2022-11-07) =
Changed
* Tested with WordPress 6.1.

= 1.0.7 (2022-10-20) =
Added
* Query string support for filters: one or more filters, scrolling to a section, and updating the page address as filters are clicked.
* Developers box in the editor.

Changed
* Updated the editor style.
* Updated the plugin's WordPress.org images.

Fixed
* PHP 8.0 compatibility fixes.

= 1.0.6 (2022-09-15) =
Added
* Full width setting.
* Filtering from the page address.
* `%slug%` skin meta tag.
* With Rank Math, grids are set to noindex, nofollow.
* New breakpoint styles.

Changed
* The Rank Math filters only run when Rank Math is active.
* Improved meta field validation.
* Improved the `%content%` skin meta tag.

= 1.0.5 (2022-07-20) =
Added
* Skin meta tag for the post content.

Changed
* Transitions take 0.4 seconds.

Fixed
* Style fixes.

= 1.0.4 (2022-07-15) =
Fixed
* Dynamic front-end style.
* JavaScript console errors.
* Filter tags on grid items.
* Filters by meta field and taxonomy.

Removed
* A duplicated Metafizzy script.
* Skin debug text for queries.

= 1.0.3 (2022-07-14) =
Added
* Query settings: number of items, order by meta field, taxonomy and taxonomy terms.

= 1.0.2 (2022-07-14) =
Added
* Validation for filters and sorting.

Fixed
* Breakpoints.

= 1.0.1 (2022-07-13) =
Added
* Settings for breakpoints, columns, minimum height and spacing.

Changed
* Updated the editor and front-end style.

Fixed
* The CSS class of single grid items.
* The skin meta tag function.

= 1.0.0 (2022-06-29) =
First stable release.

Added
* Works with any post type and order.
* Skin with meta tags for post details: title, URL, featured image (image or URL), date (any format), taxonomy (plain text, with links and/or separators) and any meta field.
* Sort buttons that sort by the content of an element in the skin, chosen by its CSS class.
* Filter buttons that filter by the content of an element in the skin.

= 0.0.6 (2022-06-01) =
Changed
* Tested with WordPress 6.0.

= 0.0.5 (2022-05-25) =
Fixed
* Custom post type and shortcode fixes.

= 0.0.4 (2022-05-25) =
Changed
* Interface improvements.

= 0.0.3 (2022-05-18) =
Added
* Skin meta tags for custom fields and taxonomy.

= 0.0.2 (2022-05-17) =
Added
* Skin meta tags for title, URL and date (default and custom format).

= 0.0.1 (2022-05-13) =
First beta release.

== Upgrade Notice ==

= 2.0.0 =
Razorfrog is now the developer of Cinza Grid. No changes to settings or behavior.
