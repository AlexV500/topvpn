<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNBestPicks.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNPublicList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNDescPublicList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNDescription.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNDescriptionBefore.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNDescriptionAfter.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNComparePublicList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'widgets/TopVPNWidgetList.php';
require_once V_PLUGIN_INCLUDES_DIR . 'widgets/TopVPNWidgetContext.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNHeaderShortcode.php';
require_once V_PLUGIN_INCLUDES_DIR . 'customization/Customization.php';

class TopVPN_Shortcodes
{

    /**
     * Add shortcodes.
     */
    public static function init()
    {
        add_shortcode('top_vpn_best_picks', array(__CLASS__, 'TopVPNBestPicks'));
        add_shortcode('TopVPNList', array(__CLASS__, 'TopVPNList'));
        add_shortcode('TopVPNDescriptionList', array(__CLASS__, 'TopVPNDescriptionList'));
        add_shortcode('TopVPNDescription', array(__CLASS__, 'TopVPNDescription'));
        add_shortcode('TopVPNDescriptionBefore', array(__CLASS__, 'TopVPNDescriptionBefore'));
        add_shortcode('TopVPNDescriptionAfter', array(__CLASS__, 'TopVPNDescriptionAfter'));
        add_shortcode('TopVPNComparePublicList', array(__CLASS__, 'TopVPNComparePublicList'));
        add_shortcode('TopVPNWidgetList', array(__CLASS__, 'TopVPNWidgetList'));
        add_shortcode('TopVPNWidgetContext', array(__CLASS__, 'TopVPNWidgetContext'));
        add_shortcode('short_vpn_description', array(__CLASS__, 'TopVPNHeaderShortcode'));
        add_shortcode('customization_snippets', array(__CLASS__, 'Customization'));
    }

    public static function TopVPNBestPicks( $atts, $content = null ) {

        return (new TopVPNBestPicks('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNList( $atts, $content = null ) {

        return (new TopVPNPublicList('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNDescriptionList( $atts, $content = null ) {

        return (new TopVPNDescPublicList('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNDescription( $atts, $content = null ) {

        return (new TopVPNDescription('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNDescriptionBefore( $atts, $content = null ) {

        return (new TopVPNDescriptionBefore('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNDescriptionAfter( $atts, $content = null ) {

        return (new TopVPNDescriptionAfter('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNComparePublicList( $atts, $content = null ) {

        return (new TopVPNComparePublicList('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNWidgetList( $atts, $content = null ) {

        return (new TopVPNWidgetList('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNWidgetContext( $atts, $content = null ) {

        return (new TopVPNWidgetContext('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function TopVPNHeaderShortcode( $atts, $content = null ) {

        return (new TopVPNHeaderShortcode('TopVPNModel', 'topvpn_vpn', $atts))->init()->render();
    }
    public static function Customization( $atts, $content = null ) {

        return (new Customization('CustomizationModel', 'topvpn_customization', $atts))->init()->render();
    }
}