<?php // phpcs:ignore 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reusable functions.
 *
 * @package Patterns_Store
 * @since 1.0.0
 * @author     codersantosh <codersantosh@gmail.com>
 */

if ( ! function_exists( 'patterns_store_default_options' ) ) :
	/**
	 * Get the Plugin Default Options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default Options
	 *
	 * @author     codersantosh <codersantosh@gmail.com>
	 */
	function patterns_store_default_options() {
		$default_options = array(
			'products'  => array(
				'postType'         => '',
				'patternSlug'      => '',
				'categorySlug'     => '',
				'tagSlug'          => '',
				'pluginSlug'       => '',
				'blockTypeSlug'    => '',
				'templateTypeSlug' => '',
				'postTypeTaxSlug'  => '',
				'offKits'          => false, /* disable pattern kits and sell only patterns */
				'excluded'         => array(), /*  Exclude from rest api */
				'offRename'        => true, /* disable renaming downloads to pattern */
			),
			'deleteAll' => false,
		);

		return apply_filters( 'patterns_store_default_options', $default_options );
	}
endif;

if ( ! function_exists( 'patterns_store_get_options' ) ) :
	/**
	 * Get the Plugin Saved Options.
	 * Recommended to use static variable for caching.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key optional option key.
	 *
	 * @return mixed All Options Array Or Options Value
	 *
	 * @author     codersantosh <codersantosh@gmail.com>
	 */
	function patterns_store_get_options( $key = '' ) {
		$options         = get_option( PATTERNS_STORE_OPTION_NAME );
		$default_options = patterns_store_default_options();

		if ( ! empty( $key ) ) {
			if ( isset( $options[ $key ] ) ) {
				return $options[ $key ];
			}
			return isset( $default_options[ $key ] ) ? $default_options[ $key ] : false;
		} else {
			if ( ! is_array( $options ) ) {
				$options = array();
			}
			return array_merge( $default_options, $options );
		}
	}
endif;

if ( ! function_exists( 'patterns_store_update_options' ) ) :
	/**
	 * Update the Plugin Options.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $key_or_data array of options or single option key.
	 * @param string       $val value of option key.
	 *
	 * @return mixed All Options Array Or Options Value
	 *
	 * @author     codersantosh <codersantosh@gmail.com>
	 */
	function patterns_store_update_options( $key_or_data, $val = '' ) {
		if ( is_string( $key_or_data ) ) {
			$options                 = patterns_store_get_options();
			$options[ $key_or_data ] = $val;
		} else {
			$options = $key_or_data;
		}
		update_option( PATTERNS_STORE_OPTION_NAME, $options );
	}
endif;

if ( ! function_exists( 'patterns_store_file_system' ) ) {
	/**
	 *
	 * WordPress file system wrapper
	 *
	 * @since 1.0.0
	 *
	 * @return string|WP_Error directory path or WP_Error object if no permission
	 */
	function patterns_store_file_system() {
		global $wp_filesystem;
		if ( ! $wp_filesystem ) {
			require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php';
		}

		WP_Filesystem();
		return $wp_filesystem;
	}
}

if ( ! function_exists( 'patterns_store_parse_changelog' ) ) {

	/**
	 * Parse changelog
	 * Both JavaScript and CSS
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function patterns_store_parse_changelog() {

		$wp_filesystem = patterns_store_file_system();

		$changelog_file = apply_filters( 'patterns_store_changelog_file', PATTERNS_STORE_PATH . 'readme.txt' );

		/*Check if the changelog file exists and is readable.*/
		if ( ! $changelog_file || ! is_readable( $changelog_file ) ) {
			return '';
		}

		$content = $wp_filesystem->get_contents( $changelog_file );

		if ( ! $content ) {
			return '';
		}

		$matches   = null;
		$regexp    = '~==\s*Changelog\s*==(.*)($)~Uis';
		$changelog = '';

		if ( preg_match( $regexp, $content, $matches ) ) {
			$changes = explode( '\r\n', trim( $matches[1] ) );

			foreach ( $changes as $index => $line ) {
				$changelog .= wp_kses_post( preg_replace( '~(=\s*Version\s*(\d+(?:\.\d+)+)\s*=|$)~Uis', '', $line ) );
			}
		}

		return wp_kses_post( $changelog );
	}
}

if ( ! function_exists( 'patterns_store_get_white_label' ) ) :
	/**
	 * Get white label options for this plugin.
	 *
	 * @since 1.0.0
	 * @param string $key optional option key.
	 * @return mixed All Options Array Or Options Value
	 * @author     codersantosh <codersantosh@gmail.com>
	 */
	function patterns_store_get_white_label( $key = '' ) {
		$plugin_name = apply_filters(
			'patterns_store_white_label_plugin_name',
			esc_html__( 'Patterns Store', 'patterns-store' )
		);

		$options = apply_filters(
			'patterns_store_white_label',
			array(
				'admin_menu_page' => array(
					'page_title' => esc_html__( 'Patterns Store Page', 'patterns-store' ),
					'menu_title' => esc_html__( 'Patterns Store', 'patterns-store' ),
					'menu_slug'  => PATTERNS_STORE_PLUGIN_NAME,
					'icon_url'   => PATTERNS_STORE_URL . 'assets/img/logo-20-20.png',
					'position'   => null,
				),
				'dashboard'       => array(
					'logo'   => PATTERNS_STORE_URL . 'assets/img/logo.png',
					'notice' => sprintf(
						/* translators: %s is the plugin name */
						esc_html__(
							"Congratulations on choosing the %s for your website development. This plugin is designed to help you quickly and efficiently build your patterns store ie patterns and pattern kits. To ensure a smooth process, we recommend taking a few minutes to read the following information on how the plugin works. This page is not just another 'getting started' page, but rather a comprehensive resource that will help you save hundreds of hours in the long run. Please read it carefully to fully understand the plugin's capabilities and how to use them effectively.",
							'patterns-store'
						),
						$plugin_name
					),
				),
				'landingPage'     => array(
					'banner'        => array(
						'heading'    => $plugin_name,
						'leadText'   => sprintf(
							/* translators: %s is the plugin name */
							esc_html__(
								'Congratulations! You have successfully installed %s and ready for you to embark on crafting captivating designs for your users.',
								'patterns-store'
							),
							$plugin_name
						),
						'normalText' => sprintf(
							/* translators: %s is the plugin name */
							esc_html__(
								'If you have any questions or need assistance, please do not hesitate to contact us for support. The %1$s plugin caters to WordPress developers and designers seeking to create and display patterns and pattern kits for their users or clients. It is specifically crafted to facilitate the development and showcasing of various design elements within the WordPress platform. If you decide to sell patterns, you can upgrade to the premium version of %2$s. The premium version seamlessly integrates with the Easy Digital Download Plugin (EDD). We hope you enjoy using %3$s and we cannot wait to see the amazing designs you offer with it.',
								'patterns-store'
							),
							$plugin_name,
							$plugin_name,
							$plugin_name,
						),
						'buttons'    => array(
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"/></svg>',
								'text'    => esc_html__( 'Get started', 'patterns-store' ),
								'url'     => 'https://patternswp.com/wp-plugins/patterns-store',
								'variant' => 'primary',
							),
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1M3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/></svg>',
								'text'    => esc_html__( 'Docmentation', 'patterns-store' ),
								'url'     => 'https://patternswp.com/wp-plugins/patterns-store',
								'variant' => 'outline-primary',
							),
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6zm11.386 3.785-1.806-2.41-.776 2.413zm-3.633.004.961-2.989H4.186l.963 2.995zM5.47 5.495 8 13.366l2.532-7.876zm-1.371-.999-.78-2.422-1.818 2.425zM1.499 5.5l5.113 6.817-2.192-6.82zm7.889 6.817 5.123-6.83-2.928.002z"/></svg>',
								'text'    => esc_html__( 'Get support', 'patterns-store' ),
								'url'     => 'https://patternswp.com/wp-plugins/patterns-store',
								'variant' => 'secondary',

							),
						),
						'image'      => PATTERNS_STORE_URL . 'assets/img/featured-image.png',

					),
					'identity'      => array(
						'logo'    => PATTERNS_STORE_URL . 'assets/img/logo.png',
						'title'   => $plugin_name,
						'buttons' => array(
							array(
								'text'    => esc_html__( 'Visit site', 'patterns-store' ),
								'url'     => 'https://patternswp.com/wp-plugins/patterns-store',
								'variant' => 'primary',

							),
							array(
								'text'    => esc_html__( 'Get Support', 'patterns-store' ),
								'url'     => 'https://patternswp.com/wp-plugins/patterns-store',
								'variant' => 'light',
							),
						),
					),
					'contact'       => array(
						'title'  => esc_html__( 'Contact Information', 'patterns-store' ),
						'info'   => array(
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/><path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/></svg>',
								'title'   => esc_html__( 'Support', 'patterns-store' ),
								'text'    => esc_html__( 'Get Support', 'patterns-store' ),
								'url'     => 'https://patternswp.com/wp-plugins/patterns-store',
								'variant' => 'link',
							),
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/></svg>',
								'title'   => esc_html__( 'Email', 'patterns-store' ),
								'text'    => esc_html__( 'codersantosh@gmail.com', 'patterns-store' ),
								'url'     => 'mailto:codersantosh@icloud.com',
								'variant' => 'link',
							),
							array(
								'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg>',
								'title' => esc_html__( 'Location', 'patterns-store' ),
								'text'  => esc_html__( 'Kathmandu, Nepal', 'patterns-store' ),
							),
						),
						'social' => array(
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M12.633 7.653c0-.848-.305-1.435-.566-1.892l-.08-.13c-.317-.51-.594-.958-.594-1.48 0-.63.478-1.218 1.152-1.218q.03 0 .058.003l.031.003A6.84 6.84 0 0 0 8 1.137 6.86 6.86 0 0 0 2.266 4.23c.16.005.313.009.442.009.717 0 1.828-.087 1.828-.087.37-.022.414.521.044.565 0 0-.371.044-.785.065l2.5 7.434 1.5-4.506-1.07-2.929c-.369-.022-.719-.065-.719-.065-.37-.022-.326-.588.043-.566 0 0 1.134.087 1.808.087.718 0 1.83-.087 1.83-.087.37-.022.413.522.043.566 0 0-.372.043-.785.065l2.48 7.377.684-2.287.054-.173c.27-.86.469-1.495.469-2.046zM1.137 8a6.86 6.86 0 0 0 3.868 6.176L1.73 5.206A6.8 6.8 0 0 0 1.137 8"/><path d="M6.061 14.583 8.121 8.6l2.109 5.78q.02.05.049.094a6.85 6.85 0 0 1-4.218.109m7.96-9.876q.046.328.047.706c0 .696-.13 1.479-.522 2.458l-2.096 6.06a6.86 6.86 0 0 0 2.572-9.224z"/><path fill-rule="evenodd" d="M0 8c0-4.411 3.589-8 8-8s8 3.589 8 8-3.59 8-8 8-8-3.589-8-8m.367 0c0 4.209 3.424 7.633 7.633 7.633S15.632 12.209 15.632 8C15.632 3.79 12.208.367 8 .367 3.79.367.367 3.79.367 8"/></svg>',
								'url'     => 'https://profiles.wordpress.org/codersantosh/',
								'variant' => 'outline-primary',
							),
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"/></svg>',
								'url'     => 'https://patternswp.com/',
								'variant' => 'outline-primary',
							),
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334q.002-.211-.006-.422A6.7 6.7 0 0 0 16 3.542a6.7 6.7 0 0 1-1.889.518 3.3 3.3 0 0 0 1.447-1.817 6.5 6.5 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.32 9.32 0 0 1-6.767-3.429 3.29 3.29 0 0 0 1.018 4.382A3.3 3.3 0 0 1 .64 6.575v.045a3.29 3.29 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3 3 0 0 1-.614-.057 3.28 3.28 0 0 0 3.067 2.277A6.6 6.6 0 0 1 .78 13.58a6 6 0 0 1-.78-.045A9.34 9.34 0 0 0 5.026 15"/></svg>',
								'url'     => 'https://twitter.com/codersantosh',
								'variant' => 'outline-primary',
							),
						),
					),
					'bannerColumns' => array(
						array(
							'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg at-w at-h" viewBox="0 0 16 16"><path d="M6.646 5.646a.5.5 0 1 1 .708.708L5.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708zm2.708 0a.5.5 0 1 0-.708.708L10.293 8 8.646 9.646a.5.5 0 0 0 .708.708l2-2a.5.5 0 0 0 0-.708z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1"/></svg>',
							'title' => esc_html__( 'Create Patterns', 'patterns-store' ),
						),
						array(
							'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg at-w at-h" viewBox="0 0 16 16"><path d="M3.112 3.645A1.5 1.5 0 0 1 4.605 2H7a.5.5 0 0 1 .5.5v.382c0 .696-.497 1.182-.872 1.469a.5.5 0 0 0-.115.118l-.012.025L6.5 4.5v.003l.003.01q.005.015.036.053a.9.9 0 0 0 .27.194C7.09 4.9 7.51 5 8 5c.492 0 .912-.1 1.19-.24a.9.9 0 0 0 .271-.194.2.2 0 0 0 .039-.063v-.009l-.012-.025a.5.5 0 0 0-.115-.118c-.375-.287-.872-.773-.872-1.469V2.5A.5.5 0 0 1 9 2h2.395a1.5 1.5 0 0 1 1.493 1.645L12.645 6.5h.237c.195 0 .42-.147.675-.48.21-.274.528-.52.943-.52.568 0 .947.447 1.154.862C15.877 6.807 16 7.387 16 8s-.123 1.193-.346 1.638c-.207.415-.586.862-1.154.862-.415 0-.733-.246-.943-.52-.255-.333-.48-.48-.675-.48h-.237l.243 2.855A1.5 1.5 0 0 1 11.395 14H9a.5.5 0 0 1-.5-.5v-.382c0-.696.497-1.182.872-1.469a.5.5 0 0 0 .115-.118l.012-.025.001-.006v-.003a.2.2 0 0 0-.039-.064.9.9 0 0 0-.27-.193C8.91 11.1 8.49 11 8 11s-.912.1-1.19.24a.9.9 0 0 0-.271.194.2.2 0 0 0-.039.063v.003l.001.006.012.025c.016.027.05.068.115.118.375.287.872.773.872 1.469v.382a.5.5 0 0 1-.5.5H4.605a1.5 1.5 0 0 1-1.493-1.645L3.356 9.5h-.238c-.195 0-.42.147-.675.48-.21.274-.528.52-.943.52-.568 0-.947-.447-1.154-.862C.123 9.193 0 8.613 0 8s.123-1.193.346-1.638C.553 5.947.932 5.5 1.5 5.5c.415 0 .733.246.943.52.255.333.48.48.675.48h.238zM4.605 3a.5.5 0 0 0-.498.55l.001.007.29 3.4A.5.5 0 0 1 3.9 7.5h-.782c-.696 0-1.182-.497-1.469-.872a.5.5 0 0 0-.118-.115l-.025-.012L1.5 6.5h-.003a.2.2 0 0 0-.064.039.9.9 0 0 0-.193.27C1.1 7.09 1 7.51 1 8s.1.912.24 1.19c.07.14.14.225.194.271a.2.2 0 0 0 .063.039H1.5l.006-.001.025-.012a.5.5 0 0 0 .118-.115c.287-.375.773-.872 1.469-.872H3.9a.5.5 0 0 1 .498.542l-.29 3.408a.5.5 0 0 0 .497.55h1.878c-.048-.166-.195-.352-.463-.557-.274-.21-.52-.528-.52-.943 0-.568.447-.947.862-1.154C6.807 10.123 7.387 10 8 10s1.193.123 1.638.346c.415.207.862.586.862 1.154 0 .415-.246.733-.52.943-.268.205-.415.39-.463.557h1.878a.5.5 0 0 0 .498-.55l-.001-.007-.29-3.4A.5.5 0 0 1 12.1 8.5h.782c.696 0 1.182.497 1.469.872.05.065.091.099.118.115l.025.012.006.001h.003a.2.2 0 0 0 .064-.039.9.9 0 0 0 .193-.27c.14-.28.24-.7.24-1.191s-.1-.912-.24-1.19a.9.9 0 0 0-.194-.271.2.2 0 0 0-.063-.039H14.5l-.006.001-.025.012a.5.5 0 0 0-.118.115c-.287.375-.773.872-1.469.872H12.1a.5.5 0 0 1-.498-.543l.29-3.407a.5.5 0 0 0-.497-.55H9.517c.048.166.195.352.463.557.274.21.52.528.52.943 0 .568-.447.947-.862 1.154C9.193 5.877 8.613 6 8 6s-1.193-.123-1.638-.346C5.947 5.447 5.5 5.068 5.5 4.5c0-.415.246-.733.52-.943.268-.205.415-.39.463-.557z"/></svg>',
							'title' => esc_html__( 'Bundle to Patterns Kits', 'patterns-store' ),
						),
						array(
							'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg at-w at-h" viewBox="0 0 16 16"><path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/><path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/></svg>',
							'title' => esc_html__( 'Start for free or sell them', 'patterns-store' ),
						),
					),
					'normalColumns' => array(
						array(
							'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/></svg>',
							'title'      => esc_html__( 'Knowledge base', 'patterns-store' ),
							'content'    => esc_html__(
								'The utilization of this plugin can be facilitated by perusing comprehensive and well-documented articles.',
								'patterns-store'
							),
							'buttonText' => esc_html__( 'Visit knowledge base', 'patterns-store' ),
							'buttonLink' => 'https://patternswp.com/wp-plugins/patterns-store',

						),
						array(
							'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/><path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/></svg>',
							'title'      => esc_html__( 'Community', 'patterns-store' ),
							'content'    => sprintf(
							/* translators: %s is the plugin name */
								esc_html__(
									'Our objective is to enhance the customer experience, we invite you to join our community where you can receive immediate support.',
									'patterns-store'
								),
								$plugin_name,
							),
							'buttonText' => esc_html__( 'Visit community page', 'patterns-store' ),
							'buttonLink' => 'https://patternswp.com/wp-plugins/patterns-store',
						),
						array(
							'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/></svg>',
							'title'      => esc_html__( '24x7 support', 'patterns-store' ),
							'content'    => sprintf(
							/* translators: %s is the plugin name */
								esc_html__(
									'Our support team is available 24/7 to assist you in the event that you encounter any problems while utilizing this plugin.',
									'patterns-store'
								),
								$plugin_name,
							),
							'buttonText' => esc_html__( 'Create a support thread', 'patterns-store' ),
							'buttonLink' => 'https://patternswp.com/wp-plugins/patterns-store',

						),
						array(
							'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M0 12V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2m6.79-6.907A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z"/></svg>',
							'title'      => esc_html__( 'Video guide', 'patterns-store' ),
							'content'    => sprintf(
							/* translators: %s is the plugin name */
								esc_html__(
									'The plugin is accompanied by comprehensive video tutorials that provide practical demonstrations for most customization.',
									'patterns-store'
								),
								$plugin_name,
							),
							'buttonText' => esc_html__( 'View video guide', 'patterns-store' ),
							'buttonLink' => 'https://patternswp.com/wp-plugins/patterns-store',

						),
					),
					'topicLinks'    => array(
						'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/><path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/></svg>',
						'title'   => esc_html__( 'Quick links to settings', 'patterns-store' ),
						'columns' => array(
							array(
								'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/></svg>',
								'title'   => esc_html__( 'General settings', 'patterns-store' ),
								'link'    => '#/settings/general',
								'variant' => 'light',
								'target'  => '_self',
							),
						),
					),
					'changelog'     => array(
						'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/></svg>',
						'title'   => esc_html__( 'Changelog', 'patterns-store' ),
						'content' => patterns_store_parse_changelog(),
					),
				),
			)
		);
		if ( ! empty( $key ) ) {
			return $options[ $key ];
		} else {
			return $options;
		}
	}
endif;

if ( ! function_exists( 'patterns_store_get_allowed_product_types' ) ) {

	/**
	 * Return get allowed product types.
	 *
	 * @return array
	 */
	function patterns_store_get_allowed_product_types() {
		return apply_filters(
			'patterns_store_allowed_product_types',
			array( 'all', 'pattern-kits', 'patterns' )
		);
	}
}

if ( ! function_exists( 'patterns_store_decode_pattern_content' ) ) {

	/**
	 * Process post content, replacing broken encoding & removing refs.
	 *
	 * Some image URLs have &s, which are double-encoded and sanitized to become malformed,
	 * for example, `https://img.rawpixel.com/s3fs-private/rawpixel_images/website_content/a010-markuss-0964.jpg?w=1200\u0026amp;h=1200\u0026amp;fit=clip\u0026amp;crop=default\u0026amp;dpr=1\u0026amp;q=75\u0026amp;vib=3\u0026amp;con=3\u0026amp;usm=15\u0026amp;cs=srgb\u0026amp;bg=F4F4F3\u0026amp;ixlib=js-2.2.1\u0026amp;s=7d494bd5db8acc2a34321c15ed18ace5`.
	 *
	 * @param string $content The raw post content.
	 *
	 * @return string
	 */
	function patterns_store_decode_pattern_content( $content ) {
		// Sometimes the initial `\` is missing, so look for both versions.
		$content = str_replace( array( '\u0026amp;', 'u0026amp;' ), '&', $content );
		// Remove `ref` from all content.
		$content = preg_replace( '/"ref":\d+,?/', '', $content );
		return $content;
	}
}

if ( ! function_exists( 'patterns_store_is_pattern_tax' ) ) {
	/**
	 * Check if pattern tax page
	 *
	 * @since    1.0.0
	 *
	 * @return boolean true if is pattern tax page.
	 */
	function patterns_store_is_pattern_tax() {
		$pattern_taxs = patterns_store_post_type_manager()->get_pattern_taxs();

		if ( is_tax( $pattern_taxs ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'patterns_store_is_modify_patterns_query' ) ) {

	/**
	 * Check if modification on patterns query needed.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $query The WP_Query instance.
	 *
	 * @return boolean modify pattern query.
	 */
	function patterns_store_is_modify_patterns_query( $query ) {
		if ( is_admin()
		|| ! ( $query->is_main_query() && ( patterns_store_is_pattern_tax() || is_post_type_archive( patterns_store_post_type_manager()->post_type ) ) )
		) {
			return false;
		}
		return true;
	}
}

if ( ! function_exists( 'patterns_store_sanitize_date' ) ) {

	/**
	 * Sanitize the date and time format Y-m-d H:i:s.
	 *
	 * @param string $input The date and time string to validate.
	 * @return string The validated date and time string or an empty string if invalid.
	 */
	function patterns_store_sanitize_date( $input ) {
		$format = 'Y-m-d H:i:s';

		$date = DateTime::createFromFormat( $format, $input );

		if ( $date && $date->format( $format ) === $input ) {
			return $input;
		}

		return '';
	}
}
