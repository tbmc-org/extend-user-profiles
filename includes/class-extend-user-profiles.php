<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Extend_User_Profiles {

	/**
	 * The single instance of Extend_User_Profiles.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * Suffix for Javascripts.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'extend_user_profiles';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
		
		// Add custom fields to user profile
		$this->add_custom_user_field();
	}
	
	public function add_custom_user_field() {
		// Registration form
		add_action( 'register_form', array( $this, 'register_form_phone') );
		add_filter( 'registration_errors', array( $this, 'registration_errors_phone'), 10, 3 );
		add_action( 'user_register', array( $this, 'user_register_phone') );
		
		// User profile page
		add_filter( 'user_contactmethods' , array( $this, 'update_profile_phone' ));
	}
	
	public function register_form_phone() {
			$phone_number = ( ! empty( $_POST['phone_number'] ) ) ? trim( $_POST['phone_number'] ) : '';
					
			?>
				<p>
					<label for="phone_number"><?php _e( 'Phone Number' ) ?><br />
					<input type="tel" name="phone_number" id="phone_number" class="input" value="<?php echo esc_attr( wp_unslash( $phone_number ) ); ?>" size="10" /></label>
				</p>
			<?php
	}

	public function registration_errors_phone( $errors, $sanitized_user_login, $user_email ) {	
		if ( empty( $_POST['phone_number'] ) || ! empty( $_POST['phone_number'] ) && trim( $_POST['phone_number'] ) == '' ) {
			$errors->add( 'phone_number_error', __( '<strong>ERROR</strong>: A valid phone number is required' ) );
		}
		if ( ! empty( $_POST['phone_number'] ) && strlen( $_POST['phone_number'] ) != 10 ) {
			$errors->add( 'phone_number_error', __( '<strong>ERROR</strong>: The phone number isn\'t correct.' ) );
		}

		return $errors;
	}

	public function user_register_phone( $user_id ) {
		if ( ! empty( $_POST['phone_number'] ) ) {
			update_user_meta( $user_id, 'phone_number', trim( $_POST['phone_number'] ) );
		}
	}
	
	public function update_profile_phone($profile_fields) {
		$profile_fields['phone_number'] = 'Phone Number';

		return $profile_fields;
	}

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'extend-user-profiles', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
			$domain = 'extend-user-profiles';

			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Extend_User_Profiles Instance
	 *
	 * Ensures only one instance of Extend_User_Profiles is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Extend_User_Profiles()
	 * @return Main Extend_User_Profiles instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
