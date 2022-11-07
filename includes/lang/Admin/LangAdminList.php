<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class LangAdminList extends AdminList
{

    public function init() : object
    {
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->initRowsCount($this->activeMode);
        $this->setPaginationCount();
        $this->initPaginationConfig();
        $this->setModelPaginationConfig();
        $this->checkPositionAction();
        $this->initRowsData($this->activeMode);
        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'name'         => __( 'Язык', 'topvpn' ),
            'sysName'      => __( 'Системное имя', 'topvpn' ),
            'positionUp'     => __( 'Позиция', 'topvpn' ),
            'positionDown'   => __( 'Позиция', 'topvpn' ),
            'status'       => __( 'Статус', 'topvpn' ),
            'edit'         => __( 'Редактировать', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Список Языков');
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames());
        if ( $this->getRowsCount() > 0 ) {
            for ( $i = 0; $i < $this->getRowsCount(); $i++ ) {

                $result = $this->getRowsData()[$i];

                $name_suffix = '';
                $class_status = '';

                $output .= '<tr class="' . $class_status . ( $i % 2 == 0 ? 'even' : 'odd' ) . '">';

                $output .= "<td class='topvpn-id'>";
                $output .= $result['id'];
                $output .= "</td>";

                $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $result['lang_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='topvpnSysName'>" . stripslashes( wp_filter_nohtml_kses( $result['lang_sys_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='position'><a href='" . $this->getCurrentURL() . "&position_set=up&item_id=" . $result['id'] . "'>Вверх</a></td>";
                $output .= "<td class='position'><a href='" . $this->getCurrentURL() . "&position_set=down&item_id=" . $result['id'] . "'>Вниз</a></td>";

                $output .= "<td class='status'>".$this->getStatusTitle($result['active'])."</td>";

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
        $output .= AdminHtmlFormInputs::renderAdminFormButton('Добавить новый язык', 'Добавить новый язык', 'button add', $this->getCurrentURL(), '&action=add');
        $this->render = $output;
        return $this;
    }
}