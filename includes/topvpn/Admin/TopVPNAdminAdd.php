<?php


class TopVPNAdminAdd extends AdminPostAction
{
    protected array $postData;

    public function init() : object{

        $this->setFormFill(
            [
                'vpn_name' => '',
                'vpn_sys_name' => '',
                'number_of_users' => '',
                'country' => '',
                'referal_link' => '',
                'referal_link_mobile' => '',
                'top_status' => '',
                'rating' => '',
                'active' => '',
                'benefits' => '',
                'short_description' => '',
                'description' => '',
                'lang' => '',
                'payments' => '',
                'datetime' => '',
            ]
        );

        if ( isset( $_POST['add_vpn'] )){
            foreach ($this->getFormFills() as $key => $value){
                $this->postData[$key] = $_POST[$key];
                $formFill[$key] = $_POST[$key];
                $this->setFormFill($formFill);
            }
            $result = $this->getModel()->addRow($this->postData);
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('TopVPNModel', 'VPN добавлен успешно!');
                $this->setResultMessages('TopVPNModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('TopVPNModel', $result->getResultMessage());
                $this->setResultMessages('TopVPNModel','error',$this->getError());
            }
        }
        return $this;
    }

    public function render(){

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить VPN');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add-topvpn" enctype="" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Добавить новый VPN','vpn_name', $this->getFormFill('vpn_name'),'namefield','required');
    }
}