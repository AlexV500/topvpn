<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNPublicList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNDescPublicList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'widgets/TopVPNWidgetList.php';


class TopVPN_Shortcodes
{

    /**
     * Add shortcodes.
     */
    public static function init()
    {
        add_shortcode('TopVPNList', array(__CLASS__, 'TopVPNList'));
        add_shortcode('TopVPNDescriptionList', array(__CLASS__, 'TopVPNDescriptionList'));
        add_shortcode('TopVPNWidgetList', array(__CLASS__, 'TopVPNWidgetList'));
    }

    public static function TopVPNList( $atts, $content = null ) {

        return (new TopVPNPublicList('TopVPNModel', 'topvpn_vpn'))->init($atts)->render();
    }
    public static function TopVPNDescriptionList( $atts, $content = null ) {

        return (new TopVPNDescPublicList('TopVPNModel', 'topvpn_vpn'))->init($atts)->render();
    }
    public static function TopVPNWidgetList( $atts, $content = null ) {

        return (new TopVPNWidgetList('TopVPNModel', 'topvpn_vpn'))->init($atts)->render();
    }
}