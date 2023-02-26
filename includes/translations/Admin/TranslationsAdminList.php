<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'translations/Model/TranslationsModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TranslationsAdminList extends AdminList
{

    public function init( array $atts = []) : object
    {
        $this->selectLanguageAdm();
    //    $this->switchMultiLangMode();
        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->setPaginationCount();
        $this->initRows($this->atts);
        $this->initRowsCount($this->activeMode);
        $this->initPaginationConfig();
        $this->checkPositionAction();
        $this->initRowsData($this->activeMode);
        $this->setLogoPath(V_PLUGIN_INCLUDES_DIR . 'images/lang/');
        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'logo' => __('Текст по умолчанию', 'topvpn'),
            'translations'   => __( 'Переведено на языки', 'topvpn' ),
            'positionUp'     => __( 'Позиция', 'topvpn' ),
            'positionDown'   => __( 'Позиция', 'topvpn' ),
            'status'       => __( 'Статус', 'topvpn' ),
            'created'      => __( 'Создано (Г-м-д)', 'topvpn' ),
            'edit'         => __( 'Редакт.', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Список Переводов');
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), 'langlist');
        if ( count($this->getRowsData()) > 0 ) {
            for ( $i = 0; $i < count($this->getRowsData()); $i++ ) {
                $result = $this->getRowsData()[$i];

                $name_suffix = '';
                $class_status = '';

                $output .= '<tr class="' . $class_status . ( $i % 2 == 0 ? 'even' : 'odd' ) . '">';

                $output .= "<td class='topvpn-id'>";
                $output .= $result['id'];
                $output .= "</td>";
           //     $output .= "<td class='topvpnLogo'><img src='". V_CORE_URL ."includes/images/lang/".$result['lang_logo']."' width='16px' height='11px'></td>";
                $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $result['lang_name'] ) ) . $name_suffix . "</td>";
                $output .= "<td class='topvpnSysName'>" . stripslashes( wp_filter_nohtml_kses( $result['lang_sys_name'] ) ) . $name_suffix . "</td>";
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
        $output .= AdminHtmlFormInputs::renderAdminFormButton('Добавить новый язык', 'Добавить новый язык', 'button button-primary', $this->getCurrentURL(), '&action=add');
        $output .= AdminHtmlFormInputs::renderAdminManageForm($this->countAllRowsFromCustomTable('lang_logo'), $this->countFiles());
        $this->render = $output;
        return $this;
    }

    protected function getTranlationFlags($id){

        $allLanguages = $this->getAllLanguageAdm();
        foreach ($allLanguages as $language) {

            $output .= '';
        }
    }
}