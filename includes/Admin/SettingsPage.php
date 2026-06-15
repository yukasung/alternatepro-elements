<?php
/**
 * Admin settings page.
 *
 * @package AlternatePro\Elements
 */

namespace AlternatePro\Elements\Admin;

use AlternatePro\Elements\Capabilities;
use AlternatePro\Elements\Modules\HeaderFooter\Module as HeaderFooterModule;
use AlternatePro\Elements\Settings\SettingsRepository;
use AlternatePro\Elements\Settings\SettingsSanitizer;

defined( 'ABSPATH' ) || exit;

/**
 * Renders and saves v1.0 settings.
 */
final class SettingsPage {
	/**
	 * Menu slug.
	 */
	public const SLUG = 'alternatepro-elements';

	/**
	 * Settings repository.
	 *
	 * @var SettingsRepository
	 */
	private $settings;

	/**
	 * Settings sanitizer.
	 *
	 * @var SettingsSanitizer
	 */
	private $sanitizer;

	/**
	 * Diagnostics provider.
	 *
	 * @var Diagnostics
	 */
	private $diagnostics;

	/**
	 * Constructor.
	 *
	 * @param SettingsRepository $settings Settings repository.
	 * @param SettingsSanitizer  $sanitizer Settings sanitizer.
	 * @param Diagnostics        $diagnostics Diagnostics provider.
	 */
	public function __construct( SettingsRepository $settings, SettingsSanitizer $sanitizer, Diagnostics $diagnostics ) {
		$this->settings    = $settings;
		$this->sanitizer   = $sanitizer;
		$this->diagnostics = $diagnostics;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register admin menu.
	 *
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			__( 'AlternatePro Elements', 'alternatepro-elements' ),
			__( 'AlternatePro', 'alternatepro-elements' ),
			Capabilities::SETTINGS,
			self::SLUG,
			array( $this, 'render' ),
			'dashicons-layout',
			58
		);

		add_submenu_page(
			self::SLUG,
			__( 'Settings', 'alternatepro-elements' ),
			__( 'Settings', 'alternatepro-elements' ),
			Capabilities::SETTINGS,
			self::SLUG,
			array( $this, 'render' )
		);

		if ( $this->is_header_footer_enabled() ) {
			add_submenu_page(
				self::SLUG,
				__( 'Theme Builder', 'alternatepro-elements' ),
				__( 'Theme Builder', 'alternatepro-elements' ),
				Capabilities::SETTINGS,
				'edit.php?post_type=' . HeaderFooterModule::POST_TYPE
			);
		}
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'apro_elements_settings',
			SettingsRepository::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->settings->defaults(),
			)
		);
	}

	/**
	 * Sanitize settings before save.
	 *
	 * @param mixed $input Raw input.
	 * @return array<string,mixed>
	 */
	public function sanitize_settings( $input ) {
		$input   = is_array( $input ) ? wp_unslash( $input ) : array();
		$current = $this->settings->get();
		$section = isset( $input['_section'] ) ? sanitize_key( $input['_section'] ) : 'all';

		unset( $input['_section'] );

		if ( 'modules' === $section ) {
			$current['modules'] = isset( $input['modules'] ) ? $input['modules'] : array();

			return $this->sanitizer->sanitize( $current );
		}

		if ( 'widgets' === $section ) {
			$current['widgets'] = isset( $input['widgets'] ) ? $input['widgets'] : array();

			return $this->sanitizer->sanitize( $current );
		}

		return $this->sanitizer->sanitize( $input );
	}

	/**
	 * Enqueue admin assets for plugin pages.
	 *
	 * @param string $hook_suffix Current hook suffix.
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( false === strpos( $hook_suffix, self::SLUG ) ) {
			return;
		}

		wp_enqueue_style(
			'apro-elements-admin',
			APRO_ELEMENTS_URL . 'assets/css/admin.css',
			array(),
			$this->asset_version( 'assets/css/admin.css' )
		);

		wp_enqueue_script(
			'apro-elements-admin',
			APRO_ELEMENTS_URL . 'assets/js/admin.js',
			array(),
			$this->asset_version( 'assets/js/admin.js' ),
			true
		);
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! Capabilities::can_manage_settings() ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'alternatepro-elements' ) );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Tab selection is read-only admin navigation.
		$tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'overview';
		?>
		<div class="wrap apro-elements-admin">
			<h1><?php esc_html_e( 'AlternatePro Elements', 'alternatepro-elements' ); ?></h1>
			<?php settings_errors( SettingsRepository::OPTION ); ?>
			<?php $this->render_tabs( $tab ); ?>

			<div class="apro-elements-panel">
				<?php
				if ( 'modules' === $tab ) {
					$this->render_modules();
				} elseif ( 'widgets' === $tab ) {
					$this->render_widgets();
				} elseif ( 'theme-builder' === $tab ) {
					$this->render_theme_builder();
				} elseif ( 'diagnostics' === $tab ) {
					$this->render_diagnostics();
				} else {
					$this->render_overview();
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render page tabs.
	 *
	 * @param string $active Active tab.
	 * @return void
	 */
	private function render_tabs( $active ) {
		$tabs = array(
			'overview' => __( 'Overview', 'alternatepro-elements' ),
			'modules'  => __( 'Modules', 'alternatepro-elements' ),
			'widgets'  => __( 'Widgets', 'alternatepro-elements' ),
		);

		if ( $this->is_header_footer_enabled() ) {
			$tabs['theme-builder'] = __( 'Theme Builder', 'alternatepro-elements' );
		}

		$tabs['diagnostics'] = __( 'Diagnostics', 'alternatepro-elements' );
		?>
		<nav class="nav-tab-wrapper" aria-label="<?php esc_attr_e( 'AlternatePro Elements settings sections', 'alternatepro-elements' ); ?>">
			<?php foreach ( $tabs as $slug => $label ) : ?>
				<a class="nav-tab <?php echo esc_attr( $active === $slug ? 'nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::SLUG . '&tab=' . $slug ) ); ?>">
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Render overview tab.
	 *
	 * @return void
	 */
	private function render_overview() {
		?>
		<h2><?php esc_html_e( 'Overview', 'alternatepro-elements' ); ?></h2>
		<p><?php esc_html_e( 'AlternatePro Elements is ready for foundation configuration. Elementor widgets and full Theme Builder features are introduced in later phases.', 'alternatepro-elements' ); ?></p>
			<ul class="apro-elements-links">
				<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::SLUG . '&tab=modules' ) ); ?>"><?php esc_html_e( 'Manage modules', 'alternatepro-elements' ); ?></a></li>
				<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::SLUG . '&tab=widgets' ) ); ?>"><?php esc_html_e( 'Manage widget toggles', 'alternatepro-elements' ); ?></a></li>
				<?php if ( $this->is_header_footer_enabled() ) : ?>
					<li><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . HeaderFooterModule::POST_TYPE ) ); ?>"><?php esc_html_e( 'Open Theme Builder templates', 'alternatepro-elements' ); ?></a></li>
				<?php else : ?>
					<li><?php esc_html_e( 'Enable Header/Footer Builder to access Theme Builder templates.', 'alternatepro-elements' ); ?></li>
				<?php endif; ?>
			</ul>
		<?php
	}

	/**
	 * Render modules tab.
	 *
	 * @return void
	 */
	private function render_modules() {
		$settings = $this->settings->get();
		$modules  = isset( $settings['modules'] ) ? $settings['modules'] : array();
		?>
		<h2><?php esc_html_e( 'Modules', 'alternatepro-elements' ); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'apro_elements_settings' ); ?>
			<input type="hidden" name="<?php echo esc_attr( SettingsRepository::OPTION ); ?>[_section]" value="modules">
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Header/Footer Builder', 'alternatepro-elements' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( SettingsRepository::OPTION ); ?>[modules][header_footer]" value="1" <?php checked( ! empty( $modules['header_footer'] ) ); ?>>
							<?php esc_html_e( 'Enable existing Header/Footer Builder module.', 'alternatepro-elements' ); ?>
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	/**
	 * Render widgets tab.
	 *
	 * @return void
	 */
	private function render_widgets() {
		$settings = $this->settings->get();
		$widgets  = isset( $settings['widgets'] ) ? $settings['widgets'] : array();
		$labels   = $this->widget_labels();
		?>
		<h2><?php esc_html_e( 'Widgets', 'alternatepro-elements' ); ?></h2>
		<p><?php esc_html_e( 'Widget toggles are stored now and used when widgets are implemented in later phases.', 'alternatepro-elements' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'apro_elements_settings' ); ?>
			<input type="hidden" name="<?php echo esc_attr( SettingsRepository::OPTION ); ?>[_section]" value="widgets">
			<table class="form-table" role="presentation">
				<?php foreach ( $labels as $key => $label ) : ?>
					<tr>
						<th scope="row"><?php echo esc_html( $label ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( SettingsRepository::OPTION ); ?>[widgets][<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $widgets[ $key ] ) ); ?>>
								<?php esc_html_e( 'Enable widget when available.', 'alternatepro-elements' ); ?>
							</label>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}

	/**
	 * Render Theme Builder tab.
	 *
	 * @return void
	 */
	private function render_theme_builder() {
		if ( ! $this->is_header_footer_enabled() ) {
			?>
			<h2><?php esc_html_e( 'Theme Builder', 'alternatepro-elements' ); ?></h2>
			<p><?php esc_html_e( 'Theme Builder is hidden because the Header/Footer Builder module is disabled.', 'alternatepro-elements' ); ?></p>
			<p><a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=' . self::SLUG . '&tab=modules' ) ); ?>"><?php esc_html_e( 'Manage Modules', 'alternatepro-elements' ); ?></a></p>
			<?php

			return;
		}

		?>
		<h2><?php esc_html_e( 'Theme Builder', 'alternatepro-elements' ); ?></h2>
		<p><?php esc_html_e( 'Phase 1 provides links to the existing template area. Full Theme Builder foundations are expanded in later phases.', 'alternatepro-elements' ); ?></p>
		<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . HeaderFooterModule::POST_TYPE ) ); ?>"><?php esc_html_e( 'Open Templates', 'alternatepro-elements' ); ?></a></p>
		<?php
	}

	/**
	 * Render Diagnostics tab.
	 *
	 * @return void
	 */
	private function render_diagnostics() {
		?>
		<h2><?php esc_html_e( 'Diagnostics', 'alternatepro-elements' ); ?></h2>
		<p><?php esc_html_e( 'Read-only diagnostics. Secrets, salts, database credentials, auth tokens, and private data are not displayed.', 'alternatepro-elements' ); ?></p>
		<table class="widefat striped apro-elements-diagnostics">
			<tbody>
				<?php foreach ( $this->diagnostics->rows() as $label => $value ) : ?>
					<tr>
						<th scope="row"><?php echo esc_html( $label ); ?></th>
						<td><?php echo esc_html( $value ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Widget labels.
	 *
	 * @return array<string,string>
	 */
	private function widget_labels() {
		return array(
			'site_logo'            => __( 'Site Logo', 'alternatepro-elements' ),
			'site_title'           => __( 'Site Title', 'alternatepro-elements' ),
			'nav_menu'             => __( 'Nav Menu', 'alternatepro-elements' ),
			'search_form'          => __( 'Search Form', 'alternatepro-elements' ),
			'hero_section'         => __( 'Hero Section', 'alternatepro-elements' ),
			'call_to_action'       => __( 'Call To Action', 'alternatepro-elements' ),
			'image_box'            => __( 'Image Box', 'alternatepro-elements' ),
			'icon_box'             => __( 'Icon Box', 'alternatepro-elements' ),
			'team_member'          => __( 'Team Member', 'alternatepro-elements' ),
			'testimonial_carousel' => __( 'Testimonial Carousel', 'alternatepro-elements' ),
			'posts'                => __( 'Posts', 'alternatepro-elements' ),
			'breadcrumbs'          => __( 'Breadcrumbs', 'alternatepro-elements' ),
		);
	}

	/**
	 * Get asset version.
	 *
	 * @param string $relative Relative path.
	 * @return string
	 */
	private function asset_version( $relative ) {
		$file = APRO_ELEMENTS_PATH . ltrim( $relative, '/' );

		return file_exists( $file ) ? (string) filemtime( $file ) : APRO_ELEMENTS_VERSION;
	}

	/**
	 * Check whether Header/Footer Builder is enabled.
	 *
	 * @return bool
	 */
	private function is_header_footer_enabled() {
		return $this->settings->is_module_enabled( 'header_footer' );
	}
}
