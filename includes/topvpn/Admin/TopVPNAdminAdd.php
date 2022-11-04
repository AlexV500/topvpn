<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TopVPNAdminAdd extends AdminPostAction
{

    protected array $os;

    public function init() : object{

        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $osModel = new OSModel('topvpn_os');
        $os = $osModel->getAllRows(true, true, false);
        $this->setFormFills(
            [
                'vpn_name' => '',
                'vpn_sys_name' => '',
                'vpn_logo' => '',
                'country' => '',
                'referal_link' => '',
                'referal_link_mobile' => '',
                'top_status' => '',
                'rating' => '',
                'rating_description' => '',
                'price' => '',
                'save_from_price' => '',
                'lang' => '',
                'os' => $os,
                'short_description' => '',
                'description' => '',
                'position' => '',
                'active' => '',
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_vpn'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('TopVPNModel', 'VPN добавлен успешно!');
                $this->setResultMessages('TopVPNModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('TopVPNModel', $result->getResultMessage());
                $this->setResultMessages('TopVPNModel','error', $this->getError());
            }
        }
        return $this;
    }

    public function render(){

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить VPN');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_vpn" enctype="multipart/form-data" action="admin.php?page=show_topvpnlist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название VPN','vpn_name', $this->getFormFill('vpn_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название VPN','vpn_sys_name', $this->getFormFill('vpn_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','vpn_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::input('Страна основания','country', $this->getFormFill('country'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Партнерская ссылка','referal_link', $this->getFormFill('referal_link'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Партнерская моб. ссылка','referal_link_mobile', $this->getFormFill('referal_link_mobile'),'namefield','');
        $output .= AdminHtmlFormInputs::select('Топ статус', 'top_status', $this->getFormFill('top_status'), [0 => 'Нет', 1 => 'Top'], '');
        $output .= AdminHtmlFormInputs::input('Рейтинг','rating', $this->getFormFill('rating'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Описание рейтинга','rating_description', $this->getFormFill('rating_description'),'namefield','');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= AdminHtmlFormInputs::textarea('Короткое описание', 'short_description', $this->getFormFill('short_description'), '');
        $output .= AdminHtmlFormInputs::textarea('Полное описание', 'description', $this->getFormFill('description'), '');
        $output .= AdminHtmlFormInputs::input('Прайс','price', $this->getFormFill('price'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Економия','save_from_price', $this->getFormFill('save_from_price'),'namefield','');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelectorField($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые операционные системы', 'os', $this->getFormFillArray('os'), ['image_name' => 'logo', 'image_path' => 'logo'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_vpn" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}