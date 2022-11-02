<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Admin/OSModel.php';

class TopVPNAdminEdit extends AdminPostAction
{
    protected array $postData;
    protected array $os;
    protected array $osChecked;

    public function init() : object{

        $this->setId(HTTP::getGet('topvpn_id'));
        $this->initAllLanguageAdm('LangModel', 'languages');
        $osModel = new OSModel('os');
        $data = $this->getModel()->getRowById($this->getId());
        $this->os = $osModel->getAllRows(true, true, false);
        $this->osChecked = $osModel->getOSByVPNId( $this->getId());
        $this->setFormFills(
            [
                'vpn_name' => $data['vpn_name'],
                'vpn_sys_name' => $data['vpn_sys_name'],
                'vpn_logo' => $data['vpn_logo'],
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
                'save_from_rice' => $data['save_from_rice'],
                'lang' => $data['lang'],
                'os' => $this->os,
                'updated' => '',
            ]
        );

        if ( isset( $_POST['edit_vpn'] )){
            foreach ($this->getFormFills() as $key => $value){
                $this->postData[$key] = $_POST[$key];
                $formFill[$key] = $_POST[$key];
                $this->setFormFills($formFill);
            }
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('TopVPNModel', 'VPN '.$this->getFormFill('vpn_name').' изменен успешно!');
                $this->setResultMessages('TopVPNModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('TopVPNModel', $result->getResultMessage());
                $this->setResultMessages('TopVPNModel','error', $this->getError());
            }
        }
        return $this;
    }

    public function render() : object{

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать VPN '.$this->getFormFill('vpn_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_vpn" enctype="" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название VPN','vpn_name', $this->getFormFill('vpn_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название VPN','vpn_sys_name', $this->getFormFill('vpn_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','vpn_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::input('Страна основания','country', $this->getFormFill('country'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Партнерская ссылка','referal_link', $this->getFormFill('referal_link'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Партнерская моб. ссылка','referal_link_mobile', $this->getFormFill('referal_link_mobile'),'namefield','');
        $output .= AdminHtmlFormInputs::select('Топ статус', 'top_status', $this->getFormFill('top_status'), [0 => 'Нет', 1 => 'Top'], '');
        $output .= AdminHtmlFormInputs::input('Рейтинг','rating', $this->getFormFill('rating'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Описание рейтинга','rating_description', $this->getFormFill('rating_description'),'namefield','');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', [1 => 'Да', 0 => 'Нет'], $this->getFormFill('active'), '');
        $output .= AdminHtmlFormInputs::textarea('Короткое описание', 'short_description', $this->getFormFill('short_description'), '');
        $output .= AdminHtmlFormInputs::textarea('Полное описание', 'description', $this->getFormFill('description'), '');
        $output .= AdminHtmlFormInputs::input('Прайс','price', $this->getFormFill('price'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Економия','save_from_rice', $this->getFormFill('save_from_rice'),'namefield','');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelector($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= AdminHtmlFormInputs::selectManyToOne('Поддерживаемые операционные системы', 'os', $this->getFormFillArray('os'), ['image_name' => 'logo', 'image_path' => 'logo', 'checked' => $this->osChecked], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_vpn" value="1">';
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}