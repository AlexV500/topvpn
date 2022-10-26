<?php


class TopVPNAdminList extends AdminActions {

    protected $activeMode = false;

    public function __construct(string $model, string $dbTable)
    {
        parent::construct($model, $dbTable);
    }

    public function init(){

        $this->checkPositionAction();
        $this->selectLanguageAdm();
        $this->switchMultiLangMode();
        $this->initAllLanguageAdm();
        $this->setPaginationConfig();
        $this->setCurrentURL();
        $this->setRowsCount($this->activeMode);
        $this->setRowsData($this->activeMode);
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
            'edit'         => __( 'Редактировать', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        return $this;
    }

    public function render(){

        $output = '';
        $output .= HtmlFormInputs::renderAdminHead('Список VPN');
        $output .= '<form id="add-topvpn" enctype="" action="" method="post">';
        $output .= HtmlFormInputs::renderAdminLanguageSelector($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= HtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames());
        $output .= '<tbody>';

        if ( $this->getRowsCount() > 0 ) {
            for ( $i = 0; $i < count( $this->getRowsCount() ); $i++ ) {

                $result = $this->getRowsData()[$i];

                $name_suffix = '';
                $class_status = '';

                $output .= '<tr class="' . $class_status . ( $i % 2 == 0 ? 'even' : 'odd' ) . '">';

                $output .= "<td class='topvpn-id'>";
                $output .= $result['id'];
                $output .= "</td>";

                $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $result['topvpn_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='topvpnSysName'>" . stripslashes( wp_filter_nohtml_kses( $result['topvpn_sys_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='rating'>".$result['rating']."</td>";
                $output .= "<td class='lang'>".$result['lang']."</td>";
                // $output .= "<td class='position'>".$result['position']."</td>";

                $output .= "<td class='position'><a href='" . esc_url( $this->getCurrentURL() ) . "&position_set=up&topvpn_id=" . $result['id'] . "'>Вверх</a></td>";
                $output .= "<td class='position'><a href='" . esc_url( $this->getCurrentURL() ) . "&position_set=down&topvpn_id=" . $result['id'] . "'>Вниз</a></td>";

                $output .= "<td class='status'>".$this->getStatusTitle($result['active'])."</td>";

                $output .= "<td class='edit'><a href='" . esc_url( add_query_arg( 'paged', $this->getPaged(), $this->getCurrentURL() ) ) . "&action=edit&topvpn_id=" . $result['id'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". FXREVIEWS_PLUGIN_URL ."images/edit.png'/></a></td>";
                $output .= "<td class='remove'>" .
                    "<a href='" . esc_url( $this->getCurrentURL() ) . "&action=delete&topvpn_id=" . $result['id'] . "' alt='" . __( 'Удалить', 'topvpn') . "'><img src='". FXREVIEWS_PLUGIN_URL ."images/remove.png'/></a>" . "</td>";

                $output .= '</tr>';


            }
        } else {
            $output .= '<tr><td colspan="' . $this->getRowsCount() . '">' . __('Нет результатов.', 'topvpn' ) . '</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        
    }
}