<?php
/**
 * Theme information Own Shop
 *
 * @package own-shop
 */


if ( ! class_exists( 'Own_shop_About_Page' ) ) {
	/**
	 * Singleton class used for generating the about page of the theme.
	 */
	class Own_shop_About_Page {
		/**
		 * Define the version of the class.
		 *
		 * @var string $version The Own_shop_About_Page class version.
		 */
		private $version = '1.0.0';
		/**
		 * Used for loading the texts and setup the actions inside the page.
		 *
		 * @var array $config The configuration array for the theme used.
		 */
		private $config;
		/**
		 * Get the theme name using wp_get_theme.
		 *
		 * @var string $theme_name The theme name.
		 */
		private $theme_name;
		/**
		 * Get the theme slug ( theme folder name ).
		 *
		 * @var string $theme_slug The theme slug.
		 */
		private $theme_slug;
		/**
		 * The current theme object.
		 *
		 * @var WP_Theme $theme The current theme.
		 */
		private $theme;
		/**
		 * Holds the theme version.
		 *
		 * @var string $theme_version The theme version.
		 */
		private $theme_version;		
		/**
		 * Define the html notification content displayed upon activation.
		 *
		 * @var string $notification The html notification content.
		 */
		private $notification;
		/**
		 * The single instance of Own_shop_About_Page
		 *
		 * @var Own_shop_About_Page $instance The Own_shop_About_Page instance.
		 */
		private static $instance;
		/**
		 * The Main Own_shop_About_Page instance.
		 *
		 * We make sure that only one instance of Own_shop_About_Page exists in the memory at one time.
		 *
		 * @param array $config The configuration array.
		 */
		public static function own_shop_init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Own_shop_About_Page ) ) {
				self::$instance = new Own_shop_About_Page;				
				self::$instance->config = $config;
				self::$instance->own_shop_setup_config();
				self::$instance->own_shop_setup_actions();				
			}
		}

		/**
		 * Setup the class props based on the config array.
		 */
		public function own_shop_setup_config() {
			$theme = wp_get_theme();
			if ( is_child_theme() ) {
				$this->theme_name = $theme->parent()->get( 'Name' );
				$this->theme      = $theme->parent();
			} else {
				$this->theme_name = $theme->get( 'Name' );
				$this->theme      = $theme->parent();
			}
			$this->theme_version = $theme->get( 'Version' );
			$this->theme_slug    = $theme->get_template();			
			$this->notification  = isset( $this->config['notification'] ) ? $this->config['notification'] : ( '<p>' . sprintf( 'Welcome! Thank you for choosing %1$s ! To take full advantage of this theme, please make sure you visit our %2$swelcome page%3$s.', $this->theme_name, '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-theme-info' ) ) . '">', '</a>' ) . '</p><p><a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-theme-info' ) ) . '" class="button" style="text-decoration: none;">' . sprintf( 'Get started with %s', $this->theme_name ) . '</a></p>' );		
		}

		/**
		 * Setup the actions used for this page.
		 */
		public function own_shop_setup_actions() {
			
			/* activation notice */
			add_action( 'load-themes.php', array( $this, 'own_shop_activation_admin_notice' ) );						
		}		
		

		/**
		 * Adds an admin notice upon successful activation.
		 */
		public function own_shop_activation_admin_notice() {
			global $pagenow;
			if ( is_admin() && ( 'themes.php' == $pagenow ) && isset( $_GET['activated'] ) ) {
				add_action( 'admin_notices', array( $this, 'own_shop_about_page_welcome_admin_notice' ), 99 );
			}
		}

		/**
		 * Display an admin notice linking to the about page
		 */
		public function own_shop_about_page_welcome_admin_notice() {
			if ( ! empty( $this->notification ) ) {
				echo '<div class="updated notice is-dismissible">';
				echo wp_kses_post( $this->notification );
				echo '</div>';
			}
		}		

	}
}


/**
 *  Adding a About page 
 */
add_action('admin_menu', 'own_shop_add_menu');

function own_shop_add_menu() {
     add_theme_page(esc_html__('About Own Shop Theme','own-shop'), esc_html__('About Own Shop Theme','own-shop'),'manage_options', esc_html__('own-shop-theme-info','own-shop'), esc_html__('own_shop_theme_info','own-shop'));
}

/**
 *  Callback
 */
function own_shop_theme_info() {
?>
	<div class="theme-info">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="title">
						<h2><?php esc_html_e( 'Thank you for using Own Shop Free WordPress theme', 'own-shop' ); ?></h2>
						<div class="title-content">
							<p><?php esc_html_e( 'Own Shop is a beautiful, easy to use eCommerce solution for any business website. It is a perfect theme for creating websites based on WooCommerce. Own Shop is fully responsive theme which supports RTL language, Gutenberg compatibility and cross browser compatibility. Theme has built in customizer settings and support most the popular plugins like Elementor, Beaver Builder, Site Origin, Contact Form 7, Jetpack etc. Own shop is SEO ready and based on most popular Bootstrap framework.', 'own-shop' ); ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="section-box">
						<div class="icon">
							<span class="dashicons dashicons-visibility"></span>
						</div>
						<div class="heading">
							<h3><a href="<?php echo esc_url(OWN_SHOP_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'VIEW DEMO', 'own-shop' ); ?></a></h3>
						</div>						
					</div>
				</div>
				<div class="col-md-2">
					<div class="section-box">
						<div class="icon">
							<span class="dashicons dashicons-format-aside"></span>
						</div>
						<div class="heading">
							<h3><a href="<?php echo esc_url(OWN_SHOP_THEME_DOC_URL); ?>" target="_blank"><?php esc_html_e( 'VIEW DOCUMENTATION', 'own-shop' ); ?></a></h3>
						</div>						
					</div>
				</div>
				<div class="col-md-2">
					<div class="section-box">
						<div class="icon">
							<span class="dashicons dashicons-video-alt2"></span>
						</div>
						<div class="heading">
							<h3><a href="<?php echo esc_url(OWN_SHOP_THEME_VIDEOS_URL); ?>" target="_blank"><?php esc_html_e( 'VIDEO TUTORIALS', 'own-shop' ); ?></a></h3>
						</div>						
					</div>
				</div>
				<div class="col-md-2">
					<div class="section-box">
						<div class="icon">
							<span class="dashicons dashicons-sos"></span>
						</div>
						<div class="heading">
							<h3><a href="<?php echo esc_url(OWN_SHOP_THEME_SUPPORT_URL); ?>" target="_blank"><?php esc_html_e( 'ASK FOR SUPPORT', 'own-shop' ); ?></a></h3>
						</div>						
					</div>
				</div>
			
				<div class="col-md-2">
					<div class="section-box">
						<div class="icon">
							<span class="dashicons dashicons-star-filled"></span>
						</div>
						<div class="heading">
							<h3><a href="<?php echo esc_url(OWN_SHOP_THEME_RATINGS_URL); ?>" target="_blank"><?php esc_html_e( 'RATE OUR THEME', 'own-shop' ); ?></a></h3>
						</div>						
					</div>
				</div>
				<div class="col-md-2">
					<div class="section-box">
						<div class="icon">
							<span class="dashicons dashicons-admin-tools"></span>
						</div>
						<div class="heading">
							<h3><a href="<?php echo esc_url(OWN_SHOP_THEME_CHANGELOGS_URL); ?>" target="_blank"><?php esc_html_e( 'VIEW CHANGELOGS', 'own-shop' ); ?></a></h3>
						</div>						
					</div>
				</div>
			</div>
		</div>		
	</div>
<?php
}
