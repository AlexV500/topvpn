<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TopVPNAdminEdit extends AdminPostAction
{
    protected array $postData;
    protected array $os;
    protected array $osData;
    protected array $osChecked;

    public function init() : object{

        $this->setId(HTTP::getGet('item_id'));
        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $osModel = new OSModel('topvpn_os');

        $data = $this->getModel()->getRowById($this->getId());
        $this->osData = $osModel->getAllRows(true,  false);
        $this->osChecked = $osModel->getOSByVPNId( $this->getId());
        $this->setFormFills(
            [
                'vpn_name' => $data['vpn_name'],
                'vpn_sys_name' => $data['vpn_sys_name'],
                'country' => $data['country'],
                'referal_link' => $data['referal_link'],
                'referal_link_mobile' => $data['referal_link_mobile'],
                'top_status' => $data['top_status'],
                'rating' => $data['rating'],
                'rating_description' => $data['rating_description'],
                'active' => $data['active'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'price' => $data['price'],
                'save_from_price' => $data['save_from_price'],
                'lang' => $data['lang'],
                'os' => $this->osData,
                'updated' => '',
            ]
        );

        if ( isset( $_POST['edit_vpn'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->osChecked = $osModel->getOSByVPNId( $this->getId());
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
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые операционные системы', 'os', $this->osData, ['image_name' => 'os_logo', 'image_path' => 'os/', 'checked' => $this->osChecked], '');
    //    $output .= '<input type="hidden" name="vpn_logo" value="">';
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_vpn" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}