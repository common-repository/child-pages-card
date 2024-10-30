<?php
/**
 * Child Pages Card
 *
 * @package    Child Pages Card
 * @subpackage ChildPagesCard Main Functions
/*
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

$childpagescard = new ChildPagesCard();

/** ==================================================
 * Main Functions
 */
class ChildPagesCard {

	/** ==================================================
	 * Construct
	 *
	 * @since   1.00
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'childpagescard_block_init' ) );
		add_action( 'enqueue_block_assets', array( $this, 'load_style' ) );
		add_shortcode( 'childpagescard', array( $this, 'childpagescard_func' ) );
	}

	/** ==================================================
	 * Attribute block
	 *
	 * @since 1.00
	 */
	public function childpagescard_block_init() {

		$childpagescard_settings = $this->load_settings();

		register_block_type(
			plugin_dir_path( __DIR__ ) . 'block/build',
			array(
				'attributes'      => array(
					'pageid'   => array(
						'type'    => 'string',
						'default' => $childpagescard_settings['pageid'],
					),
					'sort'   => array(
						'type'    => 'string',
						'default' => $childpagescard_settings['sort'],
					),
					'excerpt'   => array(
						'type'    => 'number',
						'default' => $childpagescard_settings['excerpt'],
					),
					'imgsize'   => array(
						'type'    => 'number',
						'default' => $childpagescard_settings['imgsize'],
					),
					'img_pos' => array(
						'type'    => 'string',
						'default' => $childpagescard_settings['img_pos'],
					),
					'color'    => array(
						'type'    => 'string',
						'default' => $childpagescard_settings['color'],
					),
					'color_width' => array(
						'type'    => 'number',
						'default' => $childpagescard_settings['color_width'],
					),
					't_line_height' => array(
						'type'    => 'number',
						'default' => $childpagescard_settings['t_line_height'],
					),
					'd_line_height' => array(
						'type'    => 'number',
						'default' => $childpagescard_settings['d_line_height'],
					),
				),
				'render_callback' => array( $this, 'childpagescard_func' ),
				'title' => _x( 'Child Pages Card', 'block title', 'child-pages-card' ),
				'description' => _x( 'Displays child page archives in card form.', 'block description', 'child-pages-card' ),
				'keywords' => array(
					_x( 'archives', 'block keyword', 'child-pages-card' ),
					_x( 'child page', 'block keyword', 'child-pages-card' ),
					_x( 'page', 'block keyword', 'child-pages-card' ),
				),
			)
		);

		$script_handle = generate_block_asset_handle( 'child-pages/childpagescard-block', 'editorScript' );
		wp_set_script_translations( $script_handle, 'child-pages-card' );
	}

	/** ==================================================
	 * Short code
	 *
	 * @param array  $atts  attributes.
	 * @param string $content  contents.
	 * @return string $content  contents.
	 * @since 1.00
	 */
	public function childpagescard_func( $atts, $content = null ) {

		$a = shortcode_atts(
			array(
				'pageid'  => '',
				'sort'    => '',
				'excerpt' => '',
				'imgsize' => '',
				'img_pos' => '',
				'color'   => '',
				'color_width' => '',
				't_line_height' => '',
				'd_line_height' => '',
			),
			$atts
		);

		$settings_tbl = $this->load_settings();

		foreach ( $settings_tbl as $key => $value ) {
			$shortcodekey = strtolower( $key );
			if ( 'excerpt' === $key ||
					'imgsize' === $key ||
					'color_width' === $key ||
					't_line_height' === $key ||
					'd_line_height' === $key ) {
				if ( empty( $a[ $shortcodekey ] ) ) {
					if ( is_numeric( $a[ $shortcodekey ] ) ) {
						$a[ $shortcodekey ] = 0;
					} else {
						$a[ $shortcodekey ] = $value;
					}
				} elseif ( ! is_numeric( $a[ $shortcodekey ] ) ) {
					$a[ $shortcodekey ] = $value;
				}
			} elseif ( 'sort' === $key ) {
				if ( empty( $a[ $shortcodekey ] ) ) {
					$a[ $shortcodekey ] = $value;
				} else {
					$sorts = array( 'ASC', 'DESC' );
					$a[ $shortcodekey ] = strtoupper( $a[ $shortcodekey ] );
					if ( ! in_array( $a[ $shortcodekey ], $sorts ) ) {
						$a[ $shortcodekey ] = $value;
					}
				}
			} elseif ( 'pageid' === $key ) {
				if ( empty( $a[ $shortcodekey ] ) ) {
					$a[ $shortcodekey ] = get_the_ID();
				} elseif ( ! is_numeric( $a[ $shortcodekey ] ) ) {
					$page_data = get_page_by_path( $a[ $shortcodekey ], OBJECT, 'page' );
					$a[ $shortcodekey ] = $page_data->ID;
				}
			} elseif ( empty( $a[ $shortcodekey ] ) ) {
				$a[ $shortcodekey ] = $value;
			}
		}
		if ( 'right' === $a['img_pos'] ) {
			$a['border_pos'] = 'left';
		} else {
			$a['border_pos'] = 'right';
		}

		return do_shortcode( $this->childpagescard( $a ) );
	}

	/** ==================================================
	 * Child Page Card
	 *
	 * @param array $settings  settings.
	 * @return string $content  contents.
	 * @since 1.00
	 */
	private function childpagescard( $settings ) {

		$contents = null;

		if ( ! empty( $settings['pageid'] ) ) {
			/* child pages */
			$args = array(
				'post_parent' => intval( $settings['pageid'] ),
				'post_status' => 'publish',
				'post_type'   => 'page',
				'order'       => $settings['sort'],
				'orderby'     => 'menu_order',
			);
			$children_array = get_children( $args );

			if ( count( $children_array ) > 0 ) {
				$hash = md5( $settings['pageid'] );
				$img_pos = apply_filters( 'child_pages_card_img_pos', $settings['img_pos'] );
				$border_pos = apply_filters( 'child_pages_card_border_pos', $settings['border_pos'] );
				$color_width = apply_filters( 'child_pages_card_color_width', $settings['color_width'] );
				$color = apply_filters( 'child_pages_card_color', $settings['color'] );
				$t_line_height = apply_filters( 'child_pages_card_t_line_height', $settings['t_line_height'] );
				$d_line_height = apply_filters( 'child_pages_card_d_line_height', $settings['d_line_height'] );
				$img_width = apply_filters( 'child_pages_card_img_width', $settings['imgsize'] );
				$img_height = apply_filters( 'child_pages_card_img_height', $settings['imgsize'] );

				list( $template_html_file_name, $template_css_file_name, $css_file_name ) = $this->select_template( get_option( 'childpagescard_template', 'default' ) );

				$template_html_file = apply_filters( 'child_pages_card_generate_template_html_file', plugin_dir_path( __DIR__ ) . 'template/' . $template_html_file_name );
				$template_css_file = apply_filters( 'child_pages_card_generate_template_css_file', plugin_dir_path( __DIR__ ) . 'template/' . $template_css_file_name );
				ob_start();
				include $template_css_file;
				foreach ( $children_array as $child ) {

					$title = $child->post_title . ' - ' . get_option( 'blogname' );

					$excerpt = null;
					if ( function_exists( 'mb_substr' ) ) {
						$excerpt = mb_substr( strip_shortcodes( wp_strip_all_tags( $child->post_content ) ), 0, $settings['excerpt'] ) . '...';
					} else {
						$excerpt = substr( strip_shortcodes( wp_strip_all_tags( $child->post_content ) ), 0, $settings['excerpt'] ) . '...';
					}

					$url = get_permalink( $child->ID );

					global $wpdb;
					$thumb_id = $wpdb->get_var(
						$wpdb->prepare(
							"
								SELECT	meta_value
								FROM	{$wpdb->prefix}postmeta
								WHERE	post_id = %d
								AND		meta_key = '_thumbnail_id'
							",
							$child->ID
						)
					);

					$img = false;
					$img_url = null;
					if ( $settings['imgsize'] > 0 ) {
						$img = true;
						if ( $thumb_id && wp_attachment_is_image( $thumb_id ) ) {
							$img = wp_get_attachment_image_src( $thumb_id, array( $img_width, $img_height ) );
							$img_url = $img[0];
							$img_width = $img[1];
							$img_height = $img[2];
						} else if ( get_option( 'site_icon' ) ) {
							$siteicon_id = get_option( 'site_icon' );
							if ( wp_attachment_is_image( $siteicon_id ) ) {
								$img = wp_get_attachment_image_src( $siteicon_id, array( $img_width, $img_height ) );
								$img_url = $img[0];
								$img_width = $img[1];
								$img_height = $img[2];
							} else {
								$img_url = includes_url() . 'images/w-logo-blue.png';
							}
						} else {
							$img_url = includes_url() . 'images/w-logo-blue.png';
						}
					}

					include $template_html_file;
				}
				$contents = ob_get_contents();
				ob_end_clean();
			} else {
				$contents .= '<div style="text-align: center;">';
				$contents .= '<div><strong><span class="dashicons dashicons-editor-ul" style="position: relative; top: 5px;"></span>Child Pages Card</strong></div>';
				/* translators: Input Page ID */
				$contents .= esc_html( sprintf( __( 'Please input "%1$s".', 'child-pages-card' ), __( 'ID of the parent page', 'child-pages-card' ) ) );
				$contents .= '</div>';
			}
		} else {
			$contents .= '<div style="text-align: center;">';
			$contents .= '<div><strong><span class="dashicons dashicons-editor-ul" style="position: relative; top: 5px;"></span>Child Pages Card</strong></div>';
			/* translators: Input Page ID */
			$contents .= esc_html( sprintf( __( 'Please input "%1$s".', 'child-pages-card' ), __( 'ID of the parent page', 'child-pages-card' ) ) );
			$contents .= '</div>';
		}

		return $contents;
	}

	/** ==================================================
	 * Load Style
	 *
	 * @since 1.06
	 */
	public function load_style() {

		list( $template_html_file_name, $template_css_file_name, $css_file_name ) = $this->select_template( get_option( 'childpagescard_template', 'default' ) );

		$css_url = apply_filters( 'child-pages-card_css_url', plugin_dir_url( __DIR__ ) . 'template/' . $css_file_name );
		wp_enqueue_style( 'child-pages-card', $css_url, array(), '1.00' );
	}

	/** ==================================================
	 * Select Template & CSS
	 *
	 * @param string $slug  slug.
	 * @return array $template_file_name, $css_file_name  filename.
	 * @since 2.00
	 */
	private function select_template( $slug ) {

		$templates = $this->load_templates();

		$template_html_file_name = $templates['templates'][0]['files']['template_html'];
		$template_css_file_name = $templates['templates'][0]['files']['template_css'];
		$css_file_name = $templates['templates'][0]['files']['css'];
		foreach ( $templates as $key => $value ) {
			foreach ( $value as $value2 ) {
				if ( $slug === $value2['slug'] ) {
					if ( ! empty( $value2['files']['template_html'] ) ) {
						$template_html_file_name = $value2['files']['template_html'];
					}
					if ( ! empty( $value2['files']['template_css'] ) ) {
						$template_css_file_name = $value2['files']['template_css'];
					}
					if ( ! empty( $value2['files']['css'] ) ) {
						$css_file_name = $value2['files']['css'];
					}
				}
			}
		}

		return array( $template_html_file_name, $template_css_file_name, $css_file_name );
	}

	/** ==================================================
	 * Load Templates
	 *
	 * @return array $templates  templates.
	 * @since 2.00
	 */
	public function load_templates() {

		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		$wp_filesystem = new WP_Filesystem_Direct( false );

		$json = $wp_filesystem->get_contents( plugin_dir_path( __DIR__ ) . 'template/templates.json' );
		$templates = json_decode( $json, true );

		return $templates;
	}

	/** ==================================================
	 * Load settings
	 *
	 * @return array $childpagescard_settings  settings.
	 * @since 2.00
	 */
	public function load_settings() {

		$childpagescard_settings = get_option(
			'childpagescard_settings',
			array(
				'pageid' => null,
				'sort' => 'ASC',
				'excerpt' => 500,
				'imgsize' => 100,
				'img_pos' => 'right',
				'color' => '#7db4e6',
				'color_width' => 5,
				't_line_height' => 120,
				'd_line_height' => 120,
			)
		);
		/* 'img_pos' from ver 2.00 */
		if ( ! array_key_exists( 'img_pos', $childpagescard_settings ) ) {
			$childpagescard_settings['img_pos'] = 'right';
		}
		/* 'color_width' from ver 2.00 */
		if ( ! array_key_exists( 'color_width', $childpagescard_settings ) ) {
			$childpagescard_settings['color_width'] = 5;
		}
		/* 't_line_height' from ver 2.00 */
		if ( ! array_key_exists( 't_line_height', $childpagescard_settings ) ) {
			$childpagescard_settings['t_line_height'] = 120;
		}
		/* 'd_line_height' from ver 2.00 */
		if ( ! array_key_exists( 'd_line_height', $childpagescard_settings ) ) {
			$childpagescard_settings['d_line_height'] = 120;
		}

		return $childpagescard_settings;
	}
}
