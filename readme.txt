=== Child Pages Card ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags: archives, block, child page, page, shortcode
Requires at least: 5.0
Requires PHP: 8.0
Tested up to: 6.6
Stable tag: 2.01
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays child page archives in card form.

== Description ==

= Displays child page archives in card form =
* Generated with shortcode
* Generated with block
* Can sort in ascending order and descending order.
* Specify the number of characters from the text and display the excerpt.
* Displays an featured image. If there is no featured image, a site icon is displayed, and if there is no site icon, a WordPress icon is displayed.
* Can specify the size of the displayed image.

= How it works =
[youtube https://youtu.be/AYzcpFhYu2c]

= Customize =
* Template files allow for flexible [customization](https://github.com/katsushi-kawamori/Child-Pages-Card-Templates).
* The default template file is `template/childpagescard-template-html.php` and `template/childpagescard-template-css.php`. Using this as a reference, you can specify a separate template file using the filters below.
~~~
/** ==================================================
 * Filter for template file of html.
 *
 */
add_filter(
	'child_pages_card_generate_template_html_file',
	function () {
		$wp_uploads = wp_upload_dir();
		$upload_dir = wp_normalize_path( $wp_uploads['basedir'] );
		$upload_dir = untrailingslashit( $upload_dir );
		return $upload_dir . '/tmp/childpagescard-template-html.php';
	},
	10,
	1
);
~~~
~~~
/** ==================================================
 * Filter for template file of css.
 *
 */
add_filter(
	'child_pages_card_generate_template_css_file',
	function () {
		$wp_uploads = wp_upload_dir();
		$upload_dir = wp_normalize_path( $wp_uploads['basedir'] );
		$upload_dir = untrailingslashit( $upload_dir );
		return $upload_dir . '/tmp/childpagescard-template-css.php';
	},
	10,
	1
);
~~~

* CSS files can be set separately. Please see the filters below.
~~~
/** ==================================================
 * Filter for CSS file.
 *
 */
add_filter(
	'child-pages-card_css_url',
	function () {
		$wp_uploads = wp_upload_dir();
		$upload_url = $wp_uploads['baseurl'];
		if ( is_ssl() ) {
			$upload_url = str_replace( 'http:', 'https:', $upload_url );
		}
		$upload_url = untrailingslashit( $upload_url );
		return $upload_url . '/tmp/childpagescard.css';
	},
	10,
	1
);
~~~

== Installation ==

1. Upload `child-pages-card` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

none

== Screenshots ==

1. View
2. Block
3. Block search
4. Settings

== Changelog ==

= [2.01] 2024/04/29 =
* Fix - Translation.

= [2.00] 2024/04/29 =
* Fix - Initial value issue with shortcode attribute values.
* Added - The parent page can now be specified using a slug.
* Added - Some shortcode attributes have been added.
* Added - Customization by template files.
* Change - The management screen was converted to React.

= 1.15 =
Fixed a problem with checking for the presence of images in the media library.

= 1.14 =
Fixed translation.

= 1.13 =
Fixed translation.

= 1.12 =
Rebuilt blocks.

= 1.11 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 1.10 =
Fixed a problem with private postings being visible.

= 1.09 =
Fixed problem of XSS via shortcode.

= 1.08 =
Fixed excerpt size and image size issues.
WordPress 6.1 is now supported.

= 1.07 =
Rebuilt blocks.

= 1.06 =
Added the ability to modify CSS in the admin panel.

= 1.05 =
Rebuilt blocks.

= 1.04 =
Fixed an issue that could not be displayed in the post status.
The admin screen has been modified.
Fixed an issue with database prefixes.

= 1.03 =
The block now supports ESNext.

= 1.02 =
Supported removing shortcode in excerpts.

= 1.01 =
Added input place for ID of parent page.

= 1.00 =
Initial release.

== Upgrade Notice ==

= 1.10 =
Security measures.

= 1.09 =
Security measures.

= 1.00 =

