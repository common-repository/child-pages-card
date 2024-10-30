<?php
/**
 * Child Pages Card
 *
 * @package    Child Pages Card
 * @subpackage ChildPagesCardAdmin Management screen
	Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$childpagescardadmin = new ChildPagesCardAdmin();

/** ==================================================
 * Management screen
 */
class ChildPagesCardAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );

		add_action( 'rest_api_init', array( $this, 'register_rest' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10, 1 );
	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'child-pages-card/childpagescard.php';
		}
		if ( $file === $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=childpagescard' ) . '">' . __( 'Settings' ) . '</a>';
		}
			return $links;
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_menu() {
		add_options_page(
			'Child Pages Card Options',
			'Child Pages Card',
			'manage_options',
			'childpagescard',
			array( $this, 'plugin_options' )
		);
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function plugin_options() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		printf(
			'<div class="wrap" id="child-pages-card-settings">%s</div>',
			esc_html__( 'Loading…', 'child-pages-card' )
		);
	}

	/** ==================================================
	 * Load script
	 *
	 * @param string $hook_suffix  hook_suffix.
	 * @since 2.00
	 */
	public function admin_scripts( $hook_suffix ) {

		if ( 'settings_page_childpagescard' !== $hook_suffix ) {
			return;
		}

		$asset_file = plugin_dir_path( __DIR__ ) . 'guten/build/index.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_style(
			'child_pages_card_settings_style',
			plugin_dir_url( __DIR__ ) . 'guten/build/index.css',
			array( 'wp-components' ),
			'1.0.0',
		);

		wp_enqueue_script(
			'child_pages_card_settings_script',
			plugin_dir_url( __DIR__ ) . 'guten/build/index.js',
			$asset['dependencies'],
			$asset['version'],
			array(
				'in_footer' => true,
			)
		);

		$childpagescard = new ChildPagesCard();

		$childpagescard_settings = $childpagescard->load_settings();

		$childpagescard_template = get_option( 'childpagescard_template', 'default' );

		$templates = $childpagescard->load_templates();
		$template_label_value = array();
		$template_overviews = array();
		foreach ( $templates as $key => $value ) {
			foreach ( $value as $value2 ) {
				$template_label_value[] = array(
					'label' => __( $value2['name'], 'child-pages-card' ),
					'value' => $value2['slug'],
				);
				$template_overviews[ $value2['slug'] ] = array(
					'description' => __( $value2['description'], 'child-pages-card' ),
					'version' => $value2['version'],
					'author' => $value2['author'],
					'author_link' => $value2['author_link'],
				);
			}
		}

		/* To be added to Glotpress */
		$tmp = __( 'Default template', 'child-pages-card' );
		$tmp = __( 'This is default card.', 'child-pages-card' );

		wp_localize_script(
			'child_pages_card_settings_script',
			'child_pages_card_settings_script_data',
			array(
				'options' => wp_json_encode( $childpagescard_settings, JSON_UNESCAPED_SLASHES ),
				'template' => $childpagescard_template,
				'template_label_value' => wp_json_encode( $template_label_value, JSON_UNESCAPED_SLASHES ),
				'template_overviews' => wp_json_encode( $template_overviews, JSON_UNESCAPED_SLASHES ),
				'img_block_search' => plugin_dir_url( __DIR__ ) . 'assets/screenshot-3.png',
			)
		);

		wp_set_script_translations( 'child_pages_card_settings_script', 'child-pages-card' );

		$this->credit( 'child_pages_card_settings_script' );
	}

	/** ==================================================
	 * Register Rest API
	 *
	 * @since 2.00
	 */
	public function register_rest() {

		register_rest_route(
			'rf/childpagescard_set_api',
			'/token',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'api_save' ),
				'permission_callback' => array( $this, 'rest_permission' ),
			),
		);
	}

	/** ==================================================
	 * Rest Permission
	 *
	 * @since 2.00
	 */
	public function rest_permission() {

		return current_user_can( 'manage_options' );
	}

	/** ==================================================
	 * Rest API save
	 *
	 * @param object $request  changed data.
	 * @since 2.00
	 */
	public function api_save( $request ) {

		$args = json_decode( $request->get_body(), true );

		$childpagescard_settings['pageid'] = null;
		$childpagescard_settings['sort'] = sanitize_text_field( wp_unslash( $args['sort'] ) );
		$childpagescard_settings['excerpt'] = intval( $args['excerpt'] );
		$childpagescard_settings['imgsize'] = intval( $args['imgsize'] );
		$childpagescard_settings['img_pos'] = sanitize_text_field( wp_unslash( $args['img_pos'] ) );
		$childpagescard_settings['color'] = sanitize_text_field( wp_unslash( $args['color'] ) );
		$childpagescard_settings['color_width'] = intval( $args['color_width'] );
		$childpagescard_settings['t_line_height'] = intval( $args['t_line_height'] );
		$childpagescard_settings['d_line_height'] = intval( $args['d_line_height'] );
		$childpagescard_template = sanitize_text_field( wp_unslash( $args['template'] ) );

		update_option( 'childpagescard_settings', $childpagescard_settings );
		update_option( 'childpagescard_template', $childpagescard_template );

		return new WP_REST_Response( $args, 200 );
	}

	/** ==================================================
	 * Credit
	 *
	 * @param string $handle  handle.
	 * @since 2.00
	 */
	private function credit( $handle ) {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}

		wp_localize_script(
			$handle,
			'credit',
			array(
				'links'          => __( 'Various links of this plugin', 'child-pages-card' ),
				'plugin_version' => __( 'Version:' ) . ' ' . $plugin_ver_num,
				/* translators: FAQ Link & Slug */
				'faq'            => sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'child-pages-card' ), $slug ),
				'support'        => 'https://wordpress.org/support/plugin/' . $slug,
				'review'         => 'https://wordpress.org/support/view/plugin-reviews/' . $slug,
				'translate'      => 'https://translate.wordpress.org/projects/wp-plugins/' . $slug,
				/* translators: Plugin translation link */
				'translate_text' => sprintf( __( 'Translations for %s' ), $plugin_name ),
				'facebook'       => 'https://www.facebook.com/katsushikawamori/',
				'twitter'        => 'https://twitter.com/dodesyo312',
				'youtube'        => 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w',
				'donate'         => __( 'https://shop.riverforest-wp.info/donate/', 'child-pages-card' ),
				'donate_text'    => __( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'child-pages-card' ),
				'donate_button'  => __( 'Donate to this plugin &#187;' ),
			)
		);
	}
}
