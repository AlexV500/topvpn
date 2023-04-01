<?php
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/Additional/TopVPNAdditionalModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_CORE_LIB . 'View/HTMLOutputs.php';
require_once V_CORE_LIB . 'Public/PublicList.php';
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_CORE_LIB . 'Utils/ArrayExtractor.php';

class TopVPNComparePublicList extends PublicList{

    protected int $showCount = 3;
    protected array $addKeys = [];

    public function __construct($model, $dbTable, array $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object{

        $this->addItemToCollection(new DeviceModel('topvpn_device'), 'deviceModel');
        $this->addItemToCollection(new StreamingModel('topvpn_streaming'), 'streamingModel');
        $this->addItemToCollection(new LocationModel('topvpn_location'), 'locationModel');
        $this->addItemToCollection(new TopVPNAdditionalModel('topvpn_vpn_additional'), 'vpnAdditionalModel');
        $this->switchMultiLangMode();
        $this->setOrderColumn('rating');
        $this->setOrderDirection('DESC');
        $this->setLimitCount(3);
        $this->initRows();
        $this->addRelationParam('device', $this->getItemFromCollection('deviceModel'), 'device_sys_name');
        $this->addRelationParam('streaming', $this->getItemFromCollection('streamingModel'), 'streaming_sys_name');
        $this->addRelationParam('location', $this->getItemFromCollection('locationModel'), 'location_sys_name');
        $this->addAdditionalParam('device', $this->getItemFromCollection('vpnAdditionalModel'));
        $this->addAdditionalParam('streaming', $this->getItemFromCollection('vpnAdditionalModel'));
        $this->addAdditionalParam('location', $this->getItemFromCollection('vpnAdditionalModel'));
        $this->initRowsCount($this->activeMode);
        $this->initRowsData($this->activeMode, false, true);

        if (count($this->getAdditionalResultData('compare')) > 0) {
            $arrayExtractor = new ArrayExtractor($this->getAdditionalResultData('compare'));
            $this->addKeys = $arrayExtractor->extractKeys();
        }

        return $this;
    }

    public function render() : string {

        $output = '';
        $show_count = 3;
        $show_trigger_2 = 0;
        $deviceLogoPath = DEVICE_LOGO_PATH;
        $count = count($this->getRowsData());
        $output .= '<div class="comparion-scroll-table mt-4">';
        $output .= '<div class="headers">';
        $output .= '<div class="scroller syncscroll" name="myElements">';
        $output .= '<div class="track white px-2">';
        $output .= '<div class="heading">';
        $output .= '</div>';
        $output .= '</div>';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = VPN_LOGO_PATH . '/' . $result['vpn_logo'];

                $output .= '<div class="track white px-2">';
                $output .= '<div class="heading">';
                $output .= '<div class="entry-logo"><a href="' . $result['vpn_sys_name'] . '/" alt="Logo"><img src="' . $logo . '" height="35" alt="Logo"></a></div>';
                $output .= '</div>';
                $output .= '</div>';
            }
        }
        $output .= '</div>';
        $output .= '</div>';


        $output .= '<div class="tracks syncscroll" name="myElements">';
        $output .= '<div class="track first_track">';

        $output .= '<div class="entry" data-toggle="tooltip" data-placement="top" title="Tooltip on top"">';
        $output .= '<p>User Rating</p>';
        $output .= '</div>';

        $output .= '<div class="entry table_category">';
        $output .= '<p>Performance Ratings</p>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Overall Speed</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="A higher-speed VPN enables you to stream 4k content with no lag and also to download large files quickly."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Torrenting</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Most VPNs allow torrenting using a BitTorrent client. We’ve ranked VPN services according to their torrenting privacy features."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Streaming</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="VPN services enable you to watch content from major streaming platforms privately and securely."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';


        $output .= '<div class="entry">';
        $output .= '<p>Server Locations</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="The physical location of your server is an essential factor in determining your website loading speed. If your servers are located far away, it will cause a delay in data transfer. As a result, your users will witness site latency."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Easy to Use </p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Not all VPN services are equal in terms of ease of use. We’re ranked our VPN partners based on how streamlined the interface and features are."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry table_category">';
        $output .= '<p>Security</p>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Kill Switch</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="A VPN kill switch is a key feature that stops your device’s Internet connectivity when the VPN stops working."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Wi-Fi Protection</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Hardening your wireless connection with a VPN keeps your personal data secure, especially when using public Wi-Fi."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Encryption</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Strong encryption is essential to securing the data you send through the VPN tunnel. Your internet activity becomes unreadable by snoopers."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry table_category">';
        $output .= '<p>Privacy</p>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Keep Your IP Private</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Keeping your IP address private is the core feature of a VPN service. All of the VPNs in this list have passed the IP leak test."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';


        $output .= '<div class="entry">';
        $output .= '<p>Open Source VPN</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Top VPN providers allow anyone to inspect the source code of their VPN software. This helps build trust with the users."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry table_category">';
        $output .= '<p>Key Data</p>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Data Cap</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Unfortunately, using a VPN to get online doesn\'t mean you can forget about data caps. Browsing the internet, listening to music, downloading videos, and streaming movies will still count against any data limits set by your providers, whether you\'re using a VPN or not."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>IP Adresses</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="A VPN replaces your actual IP address to make it look like you\'ve connected to the internet from a different location: the physical location of the VPN server, rather than your real location. This is just one reason why so many people use VPNs."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Speed</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="All VPNs establish an encrypted tunnel over your existing Internet connection, which means the main factor that determines the speed of your VPN connection is the base speed of your Internet connection. Other factors that might limit VPN speeds include: The speed of the connection from your Internet service provider."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Data Leaks</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Even though all VPNs are developed for the purpose of providing safe and private connections, not all of them are created equal; in some cases, buggy VPNs can leak data such as internet protocol (IP) addresses and even facilitate certain types of cybercrime."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Logging Policy</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="A VPN provider\'s log-keeping policy is its official stance on data retention. It sets the bar for what you, the customer, can expect when using its services."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Jurisdiction</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Your ability to access a VPN in your country is determined by the laws and regulations enacted by your government. Each country can allow or ban the use of VPNs and your only real recourse would be to move to a more privacy-friendly country."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Simultaneous Connections</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="In the case of VPNs, simultaneous connections are when you have more than one device active (the connection) in your VPN subscription at the same time (that\'s the “simultaneous” part). The more of these that your provider allows, the more connections you can have open at the same time."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>No. of Servers</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="The more servers a VPN has, the faster the browsing is. A VPN connects you to optimal servers so you can enjoy faster and safer connections."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Countries</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="A large number of supported countries translates to a better experience when connecting to servers from all over the world."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Works In China</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Best vpn\'s for China"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        if (count($this->addKeys) > 0) {
            $output .= '<div class="entry table_category">';
            $output .= '<p>Additional Data</p>';
            $output .= '</div>';
            foreach ($this->addKeys as $key) {
                $output .= '<div class="entry">';
                $output .= '<p>' . $key . '</p>';
                $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="'.$this->getAdditionalResultData('compare')[$key]['info'].'"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
                $output .= '</div>';

            }
        }

        $output .= '<div class="entry table_category">';
        $output .= '<p>Purchasing</p>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Support</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="Sometimes, you may have questions or issues with a VPN provider. Excellent customer support makes top VPNs stand out."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Money Back</p>';
        $output .= '&nbsp<span class="" data-toggle="tooltip" data-placement="top" title="If your experience with a VPN service wasn’t to your liking, don’t worry. For a period of usually 30 days, you can ask for a refund."><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg></span>';
        $output .= '</div>';

        $output .= '<div class="entry">';
        $output .= '<p>Value for Money</p>';
        $output .= '</div>';

        $output .= '<div class="entry last-row" style="height: 72px;">';
        $output .= '<p></p>';
        $output .= '</div>';
        $output .= '</div>';

        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $output .= '<div class="track">';
                $output .= '<div class="entry">';
                $output .= '<div class="d-flex flex-row justify-content-center">';
                $output .= HTMLOutputs::renderRating($result['rating'], 0);
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="entry table_category">';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<div class="entry-rate">';
                $output .= '<div class="rating-bar">';
                $output .= HTMLOutputs::renderRatingBar2($result['overall_speed']);
                $output .= '</div>';
                $output .= '<div class="rating-rate">'.$result['overall_speed'].'/10</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<div class="entry-rate">';
                $output .= '<div class="rating-bar">';
                $output .= HTMLOutputs::renderRatingBar2($result['torrenting_rate']);
                $output .= '</div>';
                $output .= '<div class="rating-rate">'.$result['torrenting_rate'].'/10</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<div class="entry-rate">';
                $output .= '<div class="rating-bar">';
                $output .= HTMLOutputs::renderRatingBar2($result['streaming_rate']);
                $output .= '</div>';
                $output .= '<div class="rating-rate">'.$result['streaming_rate'].'/10</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<div class="entry-rate">';
                $output .= '<div class="rating-bar">';
                $output .= HTMLOutputs::renderRatingBar2($result['server_locations_score']);
                $output .= '</div>';
                $output .= '<div class="rating-rate">'.$result['server_locations_score'].'/10</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<div class="entry-rate">';
                $output .= '<div class="rating-bar">';
                $output .= HTMLOutputs::renderRatingBar2($result['easy_to_use']);
                $output .= '</div>';
                $output .= '<div class="rating-rate">'.$result['easy_to_use'].'/10</div>';
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="entry table_category">';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<span class="check-status">';
                $output .= HTMLOutputs::renderCheckStatus((int) $result['kill_switch']);
                $output .= '</span>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<span class="check-status">';
                $output .= HTMLOutputs::renderCheckStatus((int) $result['wi_fi_protection']);
                $output .= '</span>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['encryption'];
                $output .= '</div>';

                $output .= '<div class="entry table_category">';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<span class="check-status">';
                $output .= HTMLOutputs::renderCheckStatus((int) $result['keep_your_ip_private']);
                $output .= '</span>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<span class="check-status">';
                $output .= HTMLOutputs::renderCheckStatus((int) $result['open_source_vpn']);
                $output .= '</span>';
                $output .= '</div>';

                $output .= '<div class="entry table_category">';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['data_cap'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['ip_adresses'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['speed'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['data_leaks'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['logging_policy'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['jurisdiction'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['simultaneous_connections'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['count_of_servers'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['countries'];
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['work_in_china'];
                $output .= '</div>';


                if (count($this->addKeys) > 0) {
                    $output .= '<div class="entry table_category">';
                    $output .= '</div>';
                    foreach ($this->addKeys as $key) {
                        $output .= '<div class="entry">';
                        $output .= $this->getAdditionalResultData('compare')[$key][$result['id']];
                        $output .= '</div>';
                    }
                }

                $output .= '<div class="entry table_category">';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= '<span class="check-status">';
                $output .= $result['support'];
                $output .= '</span>';
                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .= $result['money_back'];
                $output .= '</div>';

                $output .= '<div class="entry">';
            //    $output .= $result['value_for_money_score'];
                $output .= HTMLOutputs::renderRate(9.8);
                $output .= '</div>';

                $output .= '<div class="entry">';

                $output .= '</div>';

                $output .= '<div class="entry">';
                $output .='<button class="btn btn-tertiary margin-0-auto">Visit Website</button>';
                $output .= '</div>';
                $output .= '</div>';
            }
        }

        $output .= '</div>';
        $output .= '</div>';
        $this->render = $output;
        return $output;
    }

}