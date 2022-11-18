<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/TopVPNPublicList.php';


class TopVPN_Shortcodes
{

    /**
     * Add shortcodes.
     */
    public static function init()
    {
        add_shortcode('TopVPNList', array(__CLASS__, 'TopVPNList'));
    }

    public static function TopVPNList( $atts, $content = null ) {

        return (new TopVPNPublicList('TopVPNModel', 'topvpn_vpn'))->init($atts)->render();
    }
}