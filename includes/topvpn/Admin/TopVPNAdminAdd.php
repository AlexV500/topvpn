<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TopVPNAdminAdd extends AdminPostAction{

    protected array $os;
    protected array $osData;
    protected array $osChecked = [];

    public function init( array $atts = []) : object{

        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $osModel = new OSModel('topvpn_os');
        $this->osData = $osModel->getAllRows(true,  false);
        $this->setFormFills(
            [
                'vpn_name' => '',
                'vpn_sys_name' => '',
            //    'vpn_logo' => '',
                'country' => '',
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
                'price' => '',
                'save_from_price' => '',
                'lang' => '',
                'os' => $this->osData,
                'short_description' => '',
                'description' => '',
                'verdict' => '',
            //    'position' => '',
                'active' => 1,
                'created' => '',
                'updated' => '',
            ]
        );

        if ( isset( $_POST['add_vpn'] )){
        //    print_r($_POST);
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            if($result->getResultStatus() == 'ok'){
                $resultData = $result->getResultData();
                if($resultData['last_insert_id'] > 0){
                    $this->osChecked = $osModel->getOSByVPNId( $resultData['last_insert_id']);
                }
            }
            $this->setResultMessages('TopVPNModel', $result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object{

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('???????????????? VPN');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_vpn" enctype="multipart/form-data" action="admin.php?page=show_topvpnlist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('???????????????? VPN','vpn_name', $this->getFormFill('vpn_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('?????????????????? ???????????????? VPN','vpn_sys_name', $this->getFormFill('vpn_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('??????????????','vpn_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::input('???????????? ??????????????????','country', $this->getFormFill('country'),'namefield','');
        $output .= AdminHtmlFormInputs::input('?????????????????????? ????????????','referal_link', $this->getFormFill('referal_link'),'namefield','');
        $output .= AdminHtmlFormInputs::input('?????????????????????? ??????. ????????????','referal_link_mobile', $this->getFormFill('referal_link_mobile'),'namefield','');

//        $output .= AdminHtmlFormInputs::select('?????? ????????????', 'top_status', $this->getFormFill('top_status'), [0 => '??????', 1 => '???????????? ??????????', 2 => '???????????? ??????????', 3 => '???????????? ??????????'], '');
        $output .= AdminHtmlFormInputs::input('???????????????? ?????? ?????????????? (??????????????)','top_status_description', $this->getFormFill('top_status_description'),'namefield','');

        $output .= AdminHtmlFormInputs::textarea('Features', 'features', $this->getFormFill('features'), '');
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Privacy','privacy_score', $this->getFormFill('privacy_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Features','feautures_score', $this->getFormFill('feautures_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Value for money','value_for_money_score', $this->getFormFill('value_for_money_score'),'namefield','');
        $output .= AdminHtmlFormInputs::input('User Score','user_score', $this->getFormFill('user_score'),'namefield','');
        $output .= '</div>';
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('??????????????','rating', $this->getFormFill('rating'),'namefield','');
        $output .= AdminHtmlFormInputs::input('???????????????? ????????????????','rating_description', $this->getFormFill('rating_description'),'namefield','');
        $output .= '</div>';

        $output .= AdminHtmlFormInputs::textarea('???????????????? ????????????????', 'short_description', $this->getFormFill('short_description'), '');
        $output .= AdminHtmlFormInputs::textarea('???????????? ????????????????', 'description', $this->getFormFill('description'), '');
        $output .= AdminHtmlFormInputs::input('??????????????','verdict', $this->getFormFill('verdict'),'namefield','');
        $output .= AdminHtmlFormInputs::input('??????????','price', $this->getFormFill('price'),'namefield','');
        $output .= AdminHtmlFormInputs::input('????????????????','save_from_price', $this->getFormFill('save_from_price'),'namefield','');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelectorField($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= AdminHtmlFormInputs::select('????????????????', 'active', $this->getFormFill('active'), [1 => '????', 0 => '??????'], '');
        $output .= AdminHtmlFormInputs::selectManyToOne('???????????????????????????? ???????????????????????? ??????????????', 'os', $this->osData, ['image_name' => 'os_logo', 'image_path' => 'os/', 'checked' => $this->osChecked], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="add_vpn" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('????????????????');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}