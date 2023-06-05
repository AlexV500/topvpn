<?php

class AdminHtmlFormInputs
{
    public static function input($label, $name, $value, $class = '', $required = ''): string
    {

        $output = '<div class="field">' .
            '<label class="field-label ' . $required . '">' .
            '<span class="label">' .
            $label .
            '</span>' .
            '</label>' .
            '</div>' .
            '<input id="name-field" name="' . $name . '" class="' . $class . '" type="text" value="' . esc_attr(stripslashes($value)) . '"/>';
        return $output;
    }

    public static function file($label, $name, $class = '', $required = ''): string
    {

        $output = '<div class="field">' .
            '<label class="field-label ' . $required . '">' .
            '<span class="label">' .
            $label .
            '</span>' .
            '</label>' .
            '</div>' .
            '<input id="name-field" name="' . $name . '" class="' . $class . '" type="file"/>';
        return $output;
    }

    public static function select($label, $name, $formFill, array $valueArray, $required = ''): string
    {

        $output = '<div class="field">' .
            '<label class="field-label ' . $required . '">' .
            '<span class="label">' .
            __($label, 'topvpn') .
            '</span>' .
            '</label>' .
            '</div>' .
            '<select name="' . $name . '">';
        foreach ($valueArray as $arrayKey => $arrayValue) {
            $valueSelected = $arrayKey == $formFill ? ' selected="selected" ' : '';
            $output .= '<option ' . $valueSelected . ' value="' . $arrayKey . '">' . $arrayValue . '</option>';
        }
        $output .= '</select>';
        return $output;
    }

    public static function selectManyToOne($label, $name, array $rows, array $params, $required = ''): string
    {

        $img = '';
        $checked = [];
        $output = '<div class="field">' .
            '<label class="field-label">' .
            '<span class="label ' . $required . '">' .
            __($label, 'topvpn') .
            '</span>' .
            '</label>' .
            '</div>';

        if(isset($params['checked'])){
            $checked = array_column($params['checked'], 'id');
        }

        if ((count($rows) > 0)) {
            $rows = array_chunk($rows, ceil(count($rows) / 2));

            $output .= '<table>';
            $output .= '<tr>';
            foreach ($rows as $i => $items) {
                $output .= '<td width="200">';
                $output .= '<ul class="list-unstyled">';

                foreach ($items as $item):

                    if ((isset($params['image_name'])) && (isset($params['image_path']))) {
                        $logo = V_PLUGIN_URL .'images/'. $params['image_path'] . $item[$params['image_name']];
                        if (isset($params['font_logo_col_name']) && (trim($params['font_logo_col_name']) !== '')){
                            if (isset($params['font_logo_size_col_name']) && (trim($item[$params['font_logo_size_col_name']]) !== '')){
                                $size = 'font-size: '.$item[$params['font_logo_size_col_name']].';';
                            } else {
                                $size = 'font-size: 1.4rem;';
                            }
                            if (isset($params['font_logo_color_col_name']) && (trim($item[$params['font_logo_color_col_name']]) !== '')){
                                $color = 'color: '.$item[$params['font_logo_color_col_name']].';';
                            } else {
                                $color = 'color: #6c737b;';
                            }
                            $style = $color .' '. $size;
                            $img = '<span style="'.$style.'"><i class="'.$item[$params['font_logo_col_name']].'"></i></span>';
                        } else {
                            $img = '<img src="' . $logo . '" alt="" width="20px" height="20px">';
                        }

                    }
                    $formChecked = '';
                    if(isset($params['checked'])) {
                        if (in_array($item['id'], $checked)) {
                            $formChecked = ' checked';
                        }
                    }
                    $output .= '<li>
                                       <div class="form-check">
                                           <input class="form-check-input" name="' . $name . '[]" type="checkbox" value="' . $item['id'] . '"'.$formChecked.'
                                                  id="my-result-' . $item['id'] . '">
                                           <label class="form-check-label"
                                                  for="flexCheckDefault">' . $img . ' ' . $item[$name . '_name'] . '</label>
                                       </div>
                                   </li>';

                    ?>
                <?php endforeach;

                $output .= '</ul>';
                $output .= '</td>'; ?>

                <?php
            }
            $output .= '</tr>';
            $output .= '</table>';
        } else {
            $output .= '<input type="hidden" name="' . $name . '[]" value="_empty_">';
        }
        return $output;
    }

    public static function textarea($label, $name, $formFill, $required = ''): string
    {

        $output = '<div class="field">' .
            '<label class="field-label ' . $required . '">' .
            '<span class="label">' .
            __($label, 'topvpn') .
            '</span>' .
            '</label>' .
            '</div>' .
            '<textarea rows="10" cols="90" name="' . $name . '" class="">' .
            stripslashes($formFill) .
            '</textarea>';
        return $output;
    }

    public static function renderAdminLanguageSelector(array $getAllLanguageAdm, string $languageSysName): string
    {
        $output = '';
        $output .= '<form id="add-topvpn" enctype="" action="" method="post">';
        $output .= '<select name="languageSysName">';
        $output .= '<option value="no_lang">Выбрать язык</option>';
        foreach ($getAllLanguageAdm as $language) {
            $selected = $language['lang_sys_name'] == $languageSysName ? ' selected="selected" ' : '';
            $output .= '<option ' . $selected . ' value="' . $language['lang_sys_name'] . '">' . $language['lang_name'] . '</option>';
        }
        $output .= '</select>';
        $output .= '<input type="hidden" value="selectLanguageAdm" name="selectLanguageAdm"/>';
        $output .= '<input class="button button-primary" type="submit" value="' . __('Выбрать', 'topvpn') . '"/>
                    </form><br/>';
        return $output;
    }

    public static function renderAdminLanguageSelectorField(array $getAllLanguageAdm, string $languageSysName): string
    {
        $output = '<div class="field">' .
            '<label class="field-label">' .
            '<span class="label">' .
            __( 'Язык', 'fxreviews' ) .
            '</span>' .
            '</label>' .
            '</div>' .
            '<select name="lang">'.
            '<option value="">Выбрать язык</option>';

        foreach ($getAllLanguageAdm as $language) {
            $selected = $language['lang_sys_name'] == $languageSysName ? ' selected="selected" ' : '';
            $output .= '<option ' . $selected . ' value="' . $language['lang_sys_name'] . '">' . $language['lang_name'] . '</option>';
        }
        $output .= '</select>';
        return $output;
    }

    public static function renderAdminHead(string $head): string
    {

        $output = '';
        $output .= '<h1>';
        $output .= $head;
        $output .= '</h1>';
        return $output;
    }

    public static function renderAdminHeadOfTableList(array $columnDisplayNames, string $tag = ''): string
    {
        $output = '';
        $output .= '<thead>';
        $output .= '<tr>';

        foreach ($columnDisplayNames as $key => $columnDisplayName) {

            $divider = ($tag == '') ? '' : '-';
            $class = $tag.$divider.$key;
            $output .= "<th scope='col' class='$class'>$columnDisplayName</th>";
        }

        $output .= '</tr>';
        $output .= '</thead>';
        return $output;
    }

    public static function renderAdminFormButton(string $label, string $title, string $class, string $url, string $params): string
    {

        $output = '';
        $output .= '<div class="manage">';
        $output .= "<a title='" . $title . "' class='" . $class . "' href='" . esc_url($url) . $params . "'><span class='label'>" . $label . "</span></a>";
        $output .= '</div>';
        return $output;
    }

    public static function renderAdminFormSubmitButton($value) : string{
        $output = '<div class="field">' .
            '<label class="field-label">' .
            '<span class="label">' .
            __( '', 'fxreviews' ) .
            '</span>' .
            '</label>' .
            '</div>';
        $output .= '<input class="button button-primary" type="submit" value="' . $value . '"/>';
        return $output;
    }

    public static function renderAdminPagination($paginationCount, $rowsCount): string
    {

        $output = '';
        require_once(V_CORE_LIB . 'Utils/Pagination.php');
        $pagination = new Pagination($paginationCount, null, $rowsCount);
        $output .= '<div class="tablenav bottom">';
        $output .= $pagination->pagination('bottom');
        $output .= '</div>';
        return $output;
    }

    public static function renderAdminManageForm(string $countFromTable, string $countFiles): string
    {
        $output = '';
        $output .= '<form id="delete-img-topvpn" enctype="" action="" method="post">';
        $output .= '<div class="manage">';
        $output .= 'Записи изображений в б.д.: '.$countFromTable.'<br/>';
        $output .= 'Файлов изображений(логотипов) '.$countFiles.'<br/>';
        $output .= '<input type="hidden" value="logo" name="type"/>';
        $output .= '<input type="hidden" value="deleteLostImages" name="deleteLostImages"/>';
        if($countFiles > $countFromTable)
        $output .= '<br/><input class="button button-primary" type="submit" value="' . __('Удалить лишние изображения', 'topvpn') . '"/>
                    </form><br/>';
        $output .= '</div>';
        return $output;
    }

    public static function renderAdminManageForm2(string $countFromTable, string $countFiles): string
    {
        $output = '';
        $output .= '<form id="delete-img-topvpn-2" enctype="" action="" method="post">';
        $output .= '<div class="manage">';
        $output .= 'Записи изображений в б.д.: '.$countFromTable.'<br/>';
        $output .= 'Файлов изображений(скринов) '.$countFiles.'<br/>';
        $output .= '<input type="hidden" value="screen" name="type"/>';
        $output .= '<input type="hidden" value="deleteLostScreen" name="deleteLostScreen"/>';
        if($countFiles > $countFromTable)
            $output .= '<br/><input class="button button-primary" type="submit" value="' . __('Удалить лишние изображения', 'topvpn') . '"/>
                    </form><br/>';
        $output .= '</div>';
        return $output;
    }
}