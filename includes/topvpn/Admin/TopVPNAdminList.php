<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TopVPNAdminList extends AdminList {

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init(){

        $this->selectLanguageAdm();
        $this->switchMultiLangMode();
        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->initRowsCount($this->activeMode);
        $this->setPaginationCount();
        $this->initPaginationConfig();
        $this->checkPositionAction();
        $this->initRowsData($this->activeMode);

      //  $this->setActiveMode();

        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'name'         => __( 'VPN', 'topvpn' ),
            'sysName'      => __( 'Системное имя', 'topvpn' ),
            'rating'       => __( 'Рейтинг', 'topvpn' ),
            'price'        => __( 'Прайс', 'topvpn' ),
            'lang'         => __( 'Язык', 'topvpn' ),
            'positionUp'     => __( 'Позиция', 'topvpn' ),
            'positionDown'   => __( 'Позиция', 'topvpn' ),
            'status'       => __( 'Статус', 'topvpn' ),
            'created'       => __( 'Создано<br/>(Г-м-д)', 'topvpn' ),
            'updated'       => __( 'Изменено<br/>(Г-м-д)', 'topvpn' ),
            'edit'         => __( 'Редакт.', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        return $this;
    }

    public function render() : object{

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Список VPN');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelector($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), 'topvpnlist');
        $output .= '<tbody>';

        if ( $this->getRowsCount() > 0 ) {
            for ( $i = 0; $i < $this->getRowsCount(); $i++ ) {

                $result = $this->getRowsData()[$i];

                $name_suffix = '';
                $class_status = '';

                $output .= '<tr class="' . $class_status . ( $i % 2 == 0 ? 'even' : 'odd' ) . '">';

                $output .= "<td class='topvpn-id'>";
                $output .= $result['id'];
                $output .= "</td>";

                $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $result['vpn_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='topvpnSysName'>" . stripslashes( wp_filter_nohtml_kses( $result['vpn_sys_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='rating'>".$result['rating']."</td>";
                $output .= "<td class='price'>".$result['price']."</td>";
                $output .= "<td class='lang'>".$result['lang']."</td>";
                // $output .= "<td class='position'>".$result['position']."</td>";

                $output .= "<td class='position'><a href='" . $this->getCurrentURL() . "&position_set=up&item_id=" . $result['id'] . "'>Вверх</a></td>";
                $output .= "<td class='position'><a href='" . $this->getCurrentURL() . "&position_set=down&item_id=" . $result['id'] . "'>Вниз</a></td>";

                $output .= "<td class='status'>".$this->getStatusTitle($result['active'])."</td>";
                $output .= "<td class='created'>".$result['created']."</td>";
                $output .= "<td class='updated'>".$result['updated']."</td>";
                $output .= "<td class='edit'><a href='" . add_query_arg( 'paged', $this->getPaged(), $this->getCurrentURL() ) . "&action=edit&item_id=" . $result['id'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit.png'/></a></td>";
                $output .= "<td class='remove'>" .
                    "<a href='" . $this->getCurrentURL() . "&action=delete&item_id=" . $result['id'] . "' alt='" . __( 'Удалить', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/remove.png'/></a>" . "</td>";

                $output .= '</tr>';


            }
        } else {
            $output .= '<tr><td colspan="' . $this->getRowsCount() . '">' . __('Нет результатов.', 'topvpn' ) . '</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';

        $output .= AdminHtmlFormInputs::renderAdminPagination($this->getRowsCount(), $this->getPaginationCount());
        $output .= AdminHtmlFormInputs::renderAdminFormButton('Добавить новый VPN', 'Добавить новый VPN', 'button add', $this->getCurrentURL(), '&action=add');
        $this->render = $output;
        return $this;
    }
}