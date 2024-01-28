<?php
/*
Plugin Name: WPC Product Videos for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Product Videos helps you add many videos for a product and linked to the feature image or product gallery images.
Version: 1.0.9
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-product-videos
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.3
WC requires at least: 3.0
WC tested up to: 8.0
*/

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

! defined( 'WPCPV_VERSION' ) && define( 'WPCPV_VERSION', '1.0.9' );
! defined( 'WPCPV_FILE' ) && define( 'WPCPV_FILE', __FILE__ );
! defined( 'WPCPV_URI' ) && define( 'WPCPV_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCPV_REVIEWS' ) && define( 'WPCPV_REVIEWS', 'https://wordpress.org/support/plugin/wpc-product-videos/reviews/?filter=5' );
! defined( 'WPCPV_CHANGELOG' ) && define( 'WPCPV_CHANGELOG', 'https://wordpress.org/plugins/wpc-product-videos/#developers' );
! defined( 'WPCPV_DISCUSSION' ) && define( 'WPCPV_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-product-videos' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WPCPV_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';

if ( ! function_exists( 'wpcpv_init' ) ) {
	add_action( 'plugins_loaded', 'wpcpv_init', 11 );

	function wpcpv_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-product-videos', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wpcpv_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWpcpv' ) ) {
			class WPCleverWpcpv {
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					// enqueue scripts
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

					// settings page
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// meta data
					add_filter( 'attachment_fields_to_edit', [ $this, 'attachment_field_video' ], 10, 2 );
					add_filter( 'attachment_fields_to_save', [ $this, 'attachment_field_video_save' ], 10, 2 );

					// show videos
					add_filter( 'woocommerce_single_product_image_thumbnail_html', [ $this, 'thumbnail_html' ], 10, 2 );

					// HPOS compatibility
					add_action( 'before_woocommerce_init', function () {
						if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
							FeaturesUtil::declare_compatibility( 'custom_order_tables', WPCPV_FILE );
						}
					} );
				}

				function enqueue_scripts() {
					// light gallery
					wp_enqueue_script( 'lightgallery', WPCPV_URI . 'assets/libs/lightgallery/js/lightgallery-all.min.js', [ 'jquery' ], WPCPV_VERSION, true );
					wp_enqueue_style( 'lightgallery', WPCPV_URI . 'assets/libs/lightgallery/css/lightgallery.min.css' );

					// feather
					wp_enqueue_style( 'wpcpv-feather', WPCPV_URI . 'assets/libs/feather/feather.css' );

					// main
					wp_enqueue_style( 'wpcpv-frontend', WPCPV_URI . 'assets/css/frontend.css' );
					wp_enqueue_script( 'wpcpv-frontend', WPCPV_URI . 'assets/js/frontend.js', [ 'jquery' ], WPCPV_VERSION, true );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Product Videos', 'wpc-product-videos' ), esc_html__( 'Product Videos', 'wpc-product-videos' ), 'manage_options', 'wpclever-wpcpv', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'how';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Product Videos', 'wpc-product-videos' ) . ' ' . WPCPV_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-product-videos' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WPCPV_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-product-videos' ); ?></a> |
                                <a href="<?php echo esc_url( WPCPV_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-product-videos' ); ?></a> |
                                <a href="<?php echo esc_url( WPCPV_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-product-videos' ); ?></a>
                            </p>
                        </div>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wpcpv&tab=how' ); ?>" class="<?php echo esc_attr( $active_tab === 'how' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'How to use?', 'wpc-product-videos' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-product-videos' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'how' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
										<?php esc_html_e( 'When set product image or add product gallery images you can add the video URL for each image. You also can do it when editing an image via Media Library.', 'wpc-product-videos' ); ?>
                                    </p>
                                    <p><img src="<?php echo WPCPV_URI; ?>assets/images/how-01.jpg" alt=""/></p>
                                    <p>
										<?php esc_html_e( 'Then the video will be linked to the image on the product page.', 'wpc-product-videos' ); ?>
                                    </p>
                                    <p><img src="<?php echo WPCPV_URI; ?>assets/images/how-02.jpg" alt=""/></p>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings_link = '<a href="' . admin_url( 'admin.php?page=wpclever-wpcpv&tab=how' ) . '">' . esc_html__( 'How to use?', 'wpc-product-videos' ) . '</a>';
						array_unshift( $links, $settings_link );
					}

					return (array) $links;
				}

				function row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = [
							'support' => '<a href="' . esc_url( WPCPV_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-product-videos' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function attachment_field_video( $form_fields, $post ) {
					$form_fields['wpcpv-video-url'] = [
						'label' => esc_html__( 'WPC Product Videos', 'wpc-product-videos' ),
						'input' => 'text',
						'value' => get_post_meta( $post->ID, 'wpcpv_video_url', true ),
						'helps' => esc_html__( 'Youtube/Vimeo URL.', 'wpc-product-videos' )
					];

					return $form_fields;
				}

				function attachment_field_video_save( $post, $attachment ) {
					if ( isset( $attachment['wpcpv-video-url'] ) ) {
						update_post_meta( $post['ID'], 'wpcpv_video_url', esc_url( $attachment['wpcpv-video-url'] ) );
					}

					return $post;
				}

				function thumbnail_html( $html, $attachment_id ) {
					$thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'woocommerce_gallery_thumbnail_size' );

					if ( $video = get_post_meta( $attachment_id, 'wpcpv_video_url', true ) ) {
						$html = str_replace( '</div>', '<span class="wpcpv-item wpcpv-item-video" data-src="' . esc_url( $video ) . '"><img src="' . esc_url( $thumbnail_src[0] ) . '" alt=""/></span></div>', $html );
					} else {
						$html = str_replace( '</div>', '<span class="wpcpv-item wpcpv-item-image" data-src="' . wp_get_attachment_url( $attachment_id ) . '"><img src="' . esc_url( $thumbnail_src[0] ) . '" alt=""/></span></div>', $html );
					}

					return strip_tags( $html, '<div><img><span>' );
				}
			}

			return WPCleverWpcpv::instance();
		}

		return null;
	}

	function wpcpv_notice_wc() {
		?>
        <div class="error">
            <p><?php esc_html_e( 'WPC Product Videos require WooCommerce version 3.0 or greater.', 'wpc-product-videos' ); ?></p>
        </div>
		<?php
	}
}
