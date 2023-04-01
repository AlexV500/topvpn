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

class TopVPNAdminEdit extends AdminPostAction
{
    protected array $postData;
    protected array $device;
    protected array $deviceData;
    protected array $streaming;
    protected array $streamingData;
    protected array $locationData;
    protected array $payments;
    protected array $paymentsData;
    protected array $deviceChecked;
    protected array $streamingChecked;
    protected array $paymentsChecked;
    protected array $locationChecked = [];

    public function init( array $atts = []) : object{

        $this->setId(HTTP::getGet('item_id'));
        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $deviceModel = new DeviceModel('topvpn_device');
        $streamingModel = new StreamingModel('topvpn_streaming');
        $paymentsModel = new PaymentsModel('topvpn_payments');
        $locationModel = new LocationModel('topvpn_location');
        $data = $this->getModel()->getRowById($this->getId());
        $this->deviceData = $deviceModel->getAllRows(true,  false);
        $this->streamingData = $streamingModel->getAllRows(true,  false);
        $this->paymentsData = $paymentsModel->getAllRows(true,  false);
        $this->locationData = $locationModel->getAllRows(true,  false);
        $this->deviceChecked = $deviceModel->getDeviceByVPNId( $this->getId());
        $this->streamingChecked = $streamingModel->getStreamingByVPNId( $this->getId());
        $this->paymentsChecked = $paymentsModel->getPaymentsByVPNId( $this->getId());
        $this->locationChecked = $locationModel->getLocationByVPNId( $this->getId());

        $this->setFormFills(
            [
                'vpn_name' => $data['vpn_name'],
                'vpn_sys_name' => $data['vpn_sys_name'],

                'referal_link' => $data['referal_link'],
                'referal_link_mobile' => $data['referal_link_mobile'],
                'top_status_description' => $data['top_status_description'],
                'features' => $data['features'],
                'privacy_score' => $data['privacy_score'],
                'feautures_score' => $data['feautures_score'],
                'value_for_money_score' => $data['value_for_money_score'],
                'user_score' => $data['user_score'],
                'rating' => $data['rating'],
                'rating_description' => $data['rating_description'],
                'active' => $data['active'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'verdict' => $data['verdict'],

                'save_from_price' => $data['save_from_price'],
                'lang' => $data['lang'],
                'device' => $this->deviceData,
                'streaming' => $this->streamingData,
                'payments' => $this->paymentsData,
                'location' => $this->locationData,
                'overall_speed' => $data['overall_speed'],
                'torrenting_rate' => $data['torrenting_rate'],
                'streaming_rate' => $data['streaming_rate'],
                'gaming_rate' => $data['gaming_rate'],
                'easy_to_use' => $data['easy_to_use'],
                'bypassing_censorship_score' => $data['bypassing_censorship_score'],
                'server_locations_score' => $data['server_locations_score'],
                'data_cap' => $data['data_cap'],
                'speed' => $data['speed'],
                'logging_policy' => $data['logging_policy'],
                'data_leaks' => $data['data_leaks'],
                'jurisdiction' => $data['jurisdiction'],
                'count_of_servers' => $data['count_of_servers'],
                'ip_adresses' => $data['ip_adresses'],
                'countries' => $data['countries'],
                'torrenting' => $data['torrenting'],
                'simultaneous_connections' => $data['simultaneous_connections'],
                'work_in_china' => $data['work_in_china'],
                'support' => $data['support'],
                'price' => $data['price'],
                'chiepest_price' => $data['chiepest_price'],
                'free_trial' => $data['free_trial'],

                'kill_switch' => $data['kill_switch'],
                'wi_fi_protection' => $data['wi_fi_protection'],
                'encryption' => $data['encryption'],
                'keep_your_ip_private' => $data['keep_your_ip_private'],

                'open_source_vpn' => $data['open_source_vpn'],

                'money_back' => $data['money_back'],
                'updated' => '',
            ]
        );

        if ( isset( $_POST['edit_vpn'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->deviceChecked = $deviceModel->getDeviceByVPNId( $this->getId());
            $this->streamingChecked = $streamingModel->getStreamingByVPNId( $this->getId());
            $this->paymentsChecked = $paymentsModel->getPaymentsByVPNId( $this->getId());
            $this->locationChecked = $locationModel->getLocationByVPNId( $this->getId());
            $this->setResultMessages('TopVPNModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object{

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать VPN '.$this->getFormFill('vpn_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_vpn" enctype="multipart/form-data" action="" method="post">';
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
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые стриминговые системы', 'streaming', $this->streamingData, ['image_name' => 'streaming_logo', 'image_path' => 'streaming/', 'checked' => $this->streamingChecked], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые платежные системы', 'payments', $this->paymentsData, ['image_name' => 'payments_logo', 'image_path' => 'payments/', 'checked' => $this->paymentsChecked], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('Location', 'location', $this->locationData, ['image_name' => 'location_logo', 'image_path' => 'location/', 'checked' => $this->locationChecked], '');

        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Privacy score','privacy_score', $this->getFormFill('privacy_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Overall Speed','overall_speed', $this->getFormFill('overall_speed'), 'namefield','');
        $output .= AdminHtmlFormInputs::input('Features','feautures_score', $this->getFormFill('feautures_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Streaming','streaming_rate', $this->getFormFill('streaming_rate'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Torrenting','torrenting_rate', $this->getFormFill('torrenting_rate'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Easy to Use','easy_to_use', $this->getFormFill('easy_to_use'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Bypassing Censorship','bypassing_censorship_score', $this->getFormFill('bypassing_censorship_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Server Locations','server_locations_score', $this->getFormFill('server_locations_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Value for money','value_for_money_score', $this->getFormFill('value_for_money_score'),'namefield','');
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
        /**/$output .= AdminHtmlFormInputs::select('Works In China','work_in_china', $this->getFormFill('work_in_china'), ['Yes' => 'Да', 'No' => 'Нет'],'');
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



    //    $output .= '<input type="hidden" name="vpn_logo" value="">';
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_vpn" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}