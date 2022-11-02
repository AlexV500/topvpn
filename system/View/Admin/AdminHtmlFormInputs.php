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
            __($label, 'fxreviews') .
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

    public static function selectManyToOne($label, $name, array $formFill, array $params, $required = ''): string
    {

        $img = '';
        $checked = [];
        $output = '<div class="field">' .
            '<label class="field-label">' .
            '<span class="label ' . $required . '">' .
            __($label, 'fxreviews') .
            '</span>' .
            '</label>' .
            '</div>';

        if(isset($params['checked'])){
            $checked = array_column($params['checked'], 'id');
        }

        if (count($formFill) > 0) {
            $formFill = array_chunk($formFill, ceil(count($formFill) / 2));

            $output .= '<table>';
            $output .= '<tr>';
            foreach ($formFill as $i => $items) {
                $output .= '<td width="25%">';
                $output .= '<ul class="list-unstyled">';

                foreach ($items as $item):

                    if ((isset($params['image_name'])) && (isset($params['image_path']))) {
                        $logo = V_PLUGIN_URL . $params['image_path'] . $item[$params['image_name']];
                        $img = '<img src="' . $logo . '" alt="">';
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
        }
        return $output;
    }

    public static function textarea($label, $name, $formFill, $required = ''): string
    {

        $output = '<div class="field">' .
            '<label class="field-label ' . $required . '">' .
            '<span class="label">' .
            __($label, 'fxreviews') .
            '</span>' .
            '</label>' .
            '</div>' .
            '<textarea rows="10" cols="90" name="' . $name . '" class="">' .
            stripslashes($formFill['short_description']) .
            '</textarea>';
        return $output;
    }

    public static function renderAdminLanguageSelector(array $getAllLanguageAdm, string $languageSysName): string
    {

        $output = '';
        $output .= '<select name="languageSysName">';
        $output .= '<option value="">Выбрать язык</option>';
        foreach ($getAllLanguageAdm as $language) {
            $selected = $language['language_sys_name'] == $languageSysName ? ' selected="selected" ' : '';
            $output .= '<option ' . $selected . ' value="' . $language['language_sys_name'] . '">' . $language['language_name'] . '</option>';
        }
        $output .= '</select>';
        $output .= '<input type="hidden" value="selectLanguageAdm" name="selectLanguageAdm"/>';
        $output .= '<input class="button button-primary" type="submit" value="' . __('Выбрать', 'fxreviews') . '"/>
                    </form><br/>';
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

    public static function renderAdminHeadOfTableList(array $columnDisplayNames): string
    {

        $output = '';
        $output .= '<thead>';
        $output .= '<tr>';

        foreach ($columnDisplayNames as $key => $columnDisplayName) {

            $class = $key;
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

    public static function renderAdminPagination($paginationCount, $rowsCount): string
    {

        $output = '';
        require_once(FXREVIEWS_CORE_LIB . 'system/Utils/Pagination.php');
        $pagination = new FxReviews_Pagination($paginationCount, null, $rowsCount);
        $output .= '<div class="tablenav bottom">';
        $output .= $pagination->pagination('bottom');
        $output .= '</div>';
        return $output;
    }
}