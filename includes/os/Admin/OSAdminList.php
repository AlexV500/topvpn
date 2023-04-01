<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';

class OSAdminList extends AdminList{

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object
    {
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->setPaginationCount();
        $this->initRows($this->atts);
        $this->initRowsCount($this->activeMode);
        $this->initPaginationConfig();
        $this->checkPositionAction();
        $this->initRowsData($this->activeMode);
        $this->setLogoPath(V_PLUGIN_INCLUDES_DIR . 'images/os/');
        $this->unlinkAllUnusedImagesPostHandler('os_logo', $this->getLogoPath());
        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'logo' => __('Логотип', 'topvpn'),
            'name'         => __( 'OS', 'topvpn' ),
            'sysName'      => __( 'Системное имя', 'topvpn' ),
            'positionUp'     => __( 'Позиция', 'topvpn' ),
            'positionDown'   => __( 'Позиция', 'topvpn' ),
            'status'       => __( 'Статус', 'topvpn' ),
            'created'       => __( 'Создано (Г-м-д)', 'topvpn' ),
            'edit'         => __( 'Редакт.', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Список OS');
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), 'oslist');
        if ( count($this->getRowsData()) > 0 ) {
            for ( $i = 0; $i < count($this->getRowsData()); $i++ ) {

                $result = $this->getRowsData()[$i];

                $name_suffix = '';
                $class_status = '';

                $output .= '<tr class="' . $class_status . ( $i % 2 == 0 ? 'even' : 'odd' ) . '">';

                $output .= "<td class='topvpn-id'>";
                $output .= $result['id'];
                $output .= "</td>";
                if (isset($result['os_font_logo_size']) && (trim($result['os_font_logo_size']) !== '')){
                    $size = 'font-size: '.$result['os_font_logo_size'].';';
                } else {
                    $size = 'font-size: 1.4rem;';
                }
                if (isset($result['os_font_logo_color']) && (trim($result['os_font_logo_color']) !== '')){
                    $color = 'color: '.$result['os_font_logo_color'].';';
                } else {
                    $color = 'color: #6c737b;';
                }
                $style = $color .' '. $size;
                if($result['os_font_logo'] == ''){
                    $output .= "<td class='topvpnLogo'><img src='". V_CORE_URL .'includes/images/os/'.$result['os_logo']."' width='21px' height='21px'></td>";
                } else {
                    $output .= '<td class="topvpnLogo"><span class="os-font-logo" style="'.$style.'"><i class="'.$result['os_font_logo'].'"></i></td>';
                }

                $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $result['os_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='topvpnSysName'>" . stripslashes( wp_filter_nohtml_kses( $result['os_sys_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='position'><a href='" . $this->getCurrentURL() . "&position_set=up&item_id=" . $result['id'] . "'>Вверх</a></td>";
                $output .= "<td class='position'><a href='" . $this->getCurrentURL() . "&position_set=down&item_id=" . $result['id'] . "'>Вниз</a></td>";

                $output .= "<td class='status'>".$this->getStatusTitle($result['active'])."</td>";
                $output .= "<td class='created'>".$result['created']."</td>";

                $output .= "<td class='edit'><a href='" . add_query_arg( 'paged', $this->getPaged(), $this->getCurrentURL() ) . "&action=edit&item_id=" . $result['id'] . "' alt='" . __( 'Редакт.', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit2.png'/></a></td>";
                $output .= "<td class='remove'>" .
                    "<a href='" . $this->getCurrentURL() . "&action=delete&item_id=" . $result['id'] . "' alt='" . __( 'Удалить', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/remove2.png'/></a>" . "</td>";

                $output .= '</tr>';

            }
        } else {
            $output .= '<tr><td colspan="' . $this->getRowsCount() . '">' . __('Нет результатов.', 'topvpn' ) . '</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';

        $output .= AdminHtmlFormInputs::renderAdminPagination($this->getRowsCount(), $this->getPaginationCount());
        $output .= AdminHtmlFormInputs::renderAdminFormButton('Добавить новую OS', 'Добавить новую OS', 'button button-primary', $this->getCurrentURL(), '&action=add');
        $output .= AdminHtmlFormInputs::renderAdminManageForm($this->countAllRowsFromCustomTable('os_logo'), $this->countFiles($this->getLogoPath()));
        $this->render = $output;
        return $this;
    }
}