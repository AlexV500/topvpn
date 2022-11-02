<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://http://top10vpn.world/
 * @since      1.0.0
 *
 * @package    Topvpn
 * @subpackage Topvpn/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Topvpn
 * @subpackage Topvpn/admin
 * @author     Alex <alexv55555@gmail.com>
 */
class Topvpn_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Topvpn_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Topvpn_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/topvpn-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Topvpn_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Topvpn_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/topvpn-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function adminLoadDispatcher( string $manager = ''){
	    if($manager == ''){
	        return TopVPNAdminManager::init();
        } else return $manager::init();
    }

    public function topvpn_admin_menu() {

        $pages = array();

        // main
        $page = add_menu_page(
            __( 'Top VPN', 'topvpn' ),
            'Управление VPN',
            'manage_options',
            'show_topvpnlist',
            array( $this, 'adminLoadDispatcher' ),
            TOPVPN_PLUGIN_URL . 'images/affiliates.png',
            '58.187'
        );
        $pages[] = $page;
//        add_action( 'admin_print_styles-' . $page, 'fxreviews_admin_print_styles' );
//        add_action( 'admin_print_scripts-' . $page, 'fxreviews_admin_print_scripts' );

        $page = add_submenu_page(
            'show_oslist',
            __( 'Операционные системы', 'topvpnos' ),
            __( 'Операционные системы', 'topvpnos' ),
            'manage_options',
            'show_oslist',
            apply_filters( 'topvpn_add_submenu_page_function', array($this, 'adminLoadDispatcher'), 'OsAdminManager')
        );
        $pages[] = $page;


        $page = add_submenu_page(
            'show_topvpnlist',
            __( 'Язык', 'topvpnlanguage' ),
            __( 'Язык', 'topvpnlanguage' ),
            'manage_options',
            'show_topvpnlanguagelist',
            apply_filters( 'topvpn_add_submenu_page_function', array($this, 'adminLoadDispatcher'), 'LangAdminManager')
        );
        $pages[] = $page;

        do_action( 'topvpn_admin_menu', $pages );
    }
}
