<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TopVPNAdminAdd extends AdminPostAction{

    protected array $device;
    protected array $deviceData;
    protected array $paymentsData;
    protected array $streamingData;
    protected array $locationData;
    protected array $deviceChecked = [];
    protected array $paymentsChecked = [];
    protected array $streamingChecked = [];
    protected array $locationChecked = [];

    public function init( array $atts = []) : object{

        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $deviceModel = new DeviceModel('topvpn_device');
        $paymentsModel = new PaymentsModel('topvpn_payments');
        $streamingModel = new StreamingModel('topvpn_streaming');
        $locationModel = new LocationModel('topvpn_location');
        $this->deviceData = $deviceModel->getAllRows(true,  false);
        $this->paymentsData = $paymentsModel->getAllRows(true,  false);
        $this->streamingData = $streamingModel->getAllRows(true,  false);
        $this->locationData = $locationModel->getAllRows(true,  false);
        $this->setFormFills(
            [
                'vpn_name' => '',
                'vpn_sys_name' => '',
            //    'vpn_logo' => '',

                'referal_link' => '',
                'referal_link_mobile' => '',
                'top_status_description' => '',
                'features' => '',
                'privacy_score' => '',
                'feautures_score' => '',
                'value_for_money_score' => '',
                'user_score' => '',
                'rating' => '',
                'rating_description' => '',

                'save_from_price' => '',
                'lang' => '',
                'device' => $this->deviceData,
                'payments' => $this->paymentsData,
                'streaming' => $this->streamingData,
                'location' => $this->locationData,
                'short_description' => '',
                'description' => '',
                'verdict' => '',
                'overall_speed' => '',
                'torrenting_rate' => '',
                'streaming_rate' => '',
                'gaming_rate' => '',
                'easy_to_use' => '',
                'customer_support_score' => '',
                'bypassing_censorship_score' => '',
                'server_locations_score' => '',

                'data_cap' => '',
                'speed' => '',
                'logging_policy' => '',
                'data_leaks' => '',
                'jurisdiction' => '',
                'count_of_servers' => '',
                'ip_adresses' => '',
                'countries' => '',
                'torrenting' => '',
                'simultaneous_connections' => '',
                'work_in_china' => '',
                'support' => '',
                'price' => '',
                'chiepest_price' => '',
                'free_trial' => '',

                'kill_switch' => '',
                'wi_fi_protection' => '',
                'encryption' => '',

                'keep_your_ip_private' => '',

                'open_source_vpn' => '',

                'money_back' => '',
                'active' => 1,
                'created' => '',
                'updated' => '',
            ]
        );

        if ( isset( $_POST['add_vpn'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            if($result->getResultStatus() == 'ok'){
                $resultData = $result->getResultData();
                if($resultData['last_insert_id'] > 0){
                    $this->deviceChecked = $deviceModel->getDeviceByVPNId( $resultData['last_insert_id']);
                    $this->streamingChecked = $streamingModel->getStreamingByVPNId( $resultData['last_insert_id']);
                    $this->paymentsChecked = $paymentsModel->getPaymentsByVPNId( $resultData['last_insert_id']);
                    $this->locationChecked = $locationModel->getLocationByVPNId( $resultData['last_insert_id']);
                }
            }
            $this->setResultMessages('TopVPNModel', $result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object{

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить VPN');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_vpn" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название VPN','vpn_name', $this->getFormFill('vpn_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название VPN','vpn_sys_name', $this->getFormFill('vpn_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','vpn_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::file('Скрин','screen', 'namefield','required');
        $output .= AdminHtmlFormInputs::input('Партнерская ссылка','referal_link', $this->getFormFill('referal_link'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Партнерская моб. ссылка','referal_link_mobile', $this->getFormFill('referal_link_mobile'),'namefield','');

//        $output .= AdminHtmlFormInputs::select('Топ статус', 'top_status', $this->getFormFill('top_status'), [0 => 'Нет', 1 => 'Первое место', 2 => 'Второе место', 3 => 'Третье место'], '');
        $output .= AdminHtmlFormInputs::input('Описание топ статуса (регалии)','top_status_description', $this->getFormFill('top_status_description'),'namefield','');

        $output .= AdminHtmlFormInputs::textarea('Features', 'features', $this->getFormFill('features'), '');

        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Рейтинг','rating', $this->getFormFill('rating'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Описание рейтинга','rating_description', $this->getFormFill('rating_description'),'namefield','');
        $output .= '</div>';

        $output .= AdminHtmlFormInputs::textarea('Короткое описание', 'short_description', $this->getFormFill('short_description'), '');
        $output .= AdminHtmlFormInputs::textarea('Полное описание', 'description', $this->getFormFill('description'), '');
        $output .= AdminHtmlFormInputs::input('Вердикт','verdict', $this->getFormFill('verdict'),'namefield','');

        $output .= AdminHtmlFormInputs::input('Економия','save_from_price', $this->getFormFill('save_from_price'),'namefield','');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelectorField($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые операционные системы', 'device', $this->deviceData, ['image_name' => 'device_logo', 'image_path' => 'device/', 'font_logo_col_name' => 'device_font_logo', 'font_logo_color_col_name' => 'device_font_logo_color', 'font_logo_size_col_name' => 'device_font_logo_size', 'checked' => $this->deviceChecked], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые cтриминговые системы', 'streaming', $this->streamingData, ['image_name' => 'streaming_logo', 'image_path' => 'streaming/', 'checked' => $this->streamingChecked], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые платежные системы', 'payments', $this->paymentsData, ['image_name' => 'payments_logo', 'image_path' => 'payments/', 'checked' => $this->paymentsChecked], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('Location', 'location', $this->locationData, ['image_name' => 'location_logo', 'image_path' => 'location/', 'checked' => $this->locationChecked], '');

        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Privacy & Logging Policy score','privacy_score', $this->getFormFill('privacy_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Overall Speed score','overall_speed', $this->getFormFill('overall_speed'), 'namefield','');
        $output .= AdminHtmlFormInputs::input('Security & Features score','feautures_score', $this->getFormFill('feautures_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Streaming score','streaming_rate', $this->getFormFill('streaming_rate'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Torrenting score','torrenting_rate', $this->getFormFill('torrenting_rate'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Easy to Use score','easy_to_use', $this->getFormFill('easy_to_use'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Customer support score','customer_support_score', $this->getFormFill('customer_support_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Bypassing Censorship','bypassing_censorship_score', $this->getFormFill('bypassing_censorship_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Server Locations','server_locations_score', $this->getFormFill('server_locations_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Value for money score(Price & Value)','value_for_money_score', $this->getFormFill('value_for_money_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('User Score','user_score', $this->getFormFill('user_score'),'namefield','');
        $output .= '</div>';

        $output .= '<div class="inp-group">';
    /**/$output .= AdminHtmlFormInputs::input('Data Cap','data_cap', $this->getFormFill('data_cap'),'namefield','');
    /**/$output .= AdminHtmlFormInputs::input('Speed','speed', $this->getFormFill('speed'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Logging Policy','logging_policy', $this->getFormFill('logging_policy'),'namefield','');
    /**/$output .= AdminHtmlFormInputs::select('Data Leaks','data_leaks', $this->getFormFill('data_leaks'), ['Yes' => 'Да', 'No' => 'Нет'],'');
        $output .= AdminHtmlFormInputs::input('Страна (Jurisdiction)','jurisdiction', $this->getFormFill('jurisdiction'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Count. of Servers','count_of_servers', $this->getFormFill('count_of_servers'), 'namefield','');
    /**/$output .= AdminHtmlFormInputs::input('IP Adresses','ip_adresses', $this->getFormFill('ip_adresses'), 'namefield','');
        $output .= AdminHtmlFormInputs::input('Countries','countries', $this->getFormFill('countries'), 'namefield','');;
    /**/$output .= AdminHtmlFormInputs::input('Torrenting','torrenting', $this->getFormFill('torrenting'), 'namefield','');
    /**/$output .= AdminHtmlFormInputs::input('Simultaneous Connections','simultaneous_connections', $this->getFormFill('simultaneous_connections'), 'namefield','');
    /**/$output .= AdminHtmlFormInputs::select('Works In China','work_in_china', $this->getFormFill('work_in_china'), ['Yes' => 'Да', 'No' => 'Нет', 'Unreliable' => 'Unreliable'],'');
    /**/$output .= AdminHtmlFormInputs::input('Support','support', $this->getFormFill('support'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Прайс','price', $this->getFormFill('price'),'namefield','');
    /**/$output .= AdminHtmlFormInputs::input('Прайс(Chiepest)','chiepest_price', $this->getFormFill('chiepest_price'),'namefield','');
    /**/$output .= AdminHtmlFormInputs::input('Free Trial','free_trial', $this->getFormFill('free_trial'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Money Back','money_back', $this->getFormFill('money_back'), 'namefield','');
        $output .= '</div>';

        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::select('Kill Switch','kill_switch', $this->getFormFill('kill_switch'), [1 => 'Да', 0 => 'Нет'],'');
        $output .= AdminHtmlFormInputs::select('Wi-Fi Protection','wi_fi_protection', $this->getFormFill('wi_fi_protection'), [1 => 'Да', 0 => 'Нет'],'');
        $output .= AdminHtmlFormInputs::input('Encryption','encryption', $this->getFormFill('encryption'), 'namefield','');

        $output .= '</div>';
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::select('Keep Your IP Private','keep_your_ip_private', $this->getFormFill('keep_your_ip_private'), [1 => 'Да', 0 => 'Нет'],'');
        $output .= AdminHtmlFormInputs::select('Open Source VPN','open_source_vpn', $this->getFormFill('open_source_vpn'), [1 => 'Да', 0 => 'Нет'],'');
        $output .= '</div>';


        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="add_vpn" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}