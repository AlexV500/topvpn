<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/Translations/TranslationsModel.php';
require_once V_CORE_LIB . 'Utils/Collection.php';

class TranslationsAdminList extends AdminList{

    protected $langSysName;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('foreign_id'));
        $this->addItemToCollection(new LangModel('topvpn_lang'), 'langModel');
        $this->setOrderColumn('tkey');
        $this->setOrderDirection('ASC');
        $this->setPaginationCount(15);
        $this->initRows($this->atts);
        $this->initRowsCount($this->activeMode);
        $this->initPaginationConfig();
        $this->initRowsData($this->activeMode);
        return $this;
    }

    public function render() : object
    {
        $langModel = $this->getItemFromCollection('langModel');
        $lang = $langModel->getRowById($this->getId());
        $defaultLocale = $langModel->getDefaultLocale();
        $tag = ($defaultLocale['lang_sys_name'] === $lang['lang_sys_name']) ? 'translations-default' : 'translations';
        if($defaultLocale['lang_sys_name'] === $lang['lang_sys_name']){
            $this->setColumnDisplayNames(array(
                'id' => __( 'id', 'topvpn' ),
                'phrase'         => __( 'Фраза', 'topvpn' ),
                'status'       => __( 'Статус', 'topvpn' ),
                'created'       => __( 'Создано<br/>(Г-м-д)', 'topvpn' ),
                'updated'       => __( 'Изменено<br/>(Г-м-д)', 'topvpn' ),
                'delete'       => __( 'Удалить', 'topvpn' ),
            ));
        } else {
            $this->setColumnDisplayNames(array(
                'id' => __( 'id', 'topvpn' ),
                'phrase'         => __( 'Фраза', 'topvpn' ),
                'translation'         => __( 'Перевод', 'topvpn' ),
                'status'       => __( 'Статус', 'topvpn' ),
                'created'       => __( 'Создано<br/>(Г-м-д)', 'topvpn' ),
                'updated'       => __( 'Изменено<br/>(Г-м-д)', 'topvpn' ),
                'edit'         => __( 'Редакт.', 'topvpn' ),
            ));
        }
        $class_status = '';
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Список Переводов (' . $lang['lang_name'] . ')');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), $tag);
        $output .= '<tbody>';

        if (count($this->getRowsData()) > 0) {
            for ($i = 0; $i < count($this->getRowsData()); $i++) {

                $result = $this->getRowsData()[$i];
                $output .= '<tr class="' . $class_status . ( $i % 2 == 0 ? 'even' : 'odd' ) . '">';

                $output .= "<td class='topvpn-id'>";
                $output .= $result['id'];
                $output .= "</td>";
                $output .= "<td class='phrase'>".$result[$defaultLocale['lang_sys_name']]."</td>";
                if($defaultLocale['lang_sys_name'] !== $lang['lang_sys_name']){
                    $output .= "<td class='translation'>".$result[$lang['lang_sys_name']]."</td>";
                }
                $output .= "<td class='status'>".$this->getStatusTitle($result['active'])."</td>";
                $output .= "<td class='created'>".$result['created']."</td>";
                $output .= "<td class='updated'>".$result['updated']."</td>";
                if($defaultLocale['lang_sys_name'] !== $lang['lang_sys_name']) {
                    $output .= "<td class='edit'><a href='" . $this->getCurrentURL() . "&action=edit_translations&item_id=" . $result['id'] . "&foreign_id=" . $this->getId() . "' alt='" . __('Редактировать', 'topvpn') . "'><img src='" . V_CORE_URL . "admin/images/edit2.png'/></a></td>";
                }
                if($defaultLocale['lang_sys_name'] === $lang['lang_sys_name']) {
                    $output .= "<td class='remove'>" .
                        "<a href='" . $this->getCurrentURL() . "&action=delete_translations&item_id=" . $result['id'] . "&foreign_id=" . $this->getId() . "' alt='" . __('Удалить', 'topvpn') . "'><img src='" . V_CORE_URL . "admin/images/remove2.png'/></a>" . "</td>";
                }
                $output .= '</tr>';

            }
        } else {
            $output .= '<tr><td colspan="' . $this->getRowsCount() . '">' . __('Нет результатов.', 'topvpn') . '</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $output .= AdminHtmlFormInputs::renderAdminPagination($this->getRowsCount(), $this->getPaginationCount());
        $this->render = $output;
        return $this;
    }

    protected function setLangSysName($langSysName){

        $this->langSysName = $langSysName;
        return $this;
    }
}