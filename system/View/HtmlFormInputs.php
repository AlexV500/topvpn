<?php

class HtmlFormInputs
{
   public static function input($label, $name, $value, $class='', $required = ''){

       $output ='<div class="field">' .
       '<label class="field-label '.$required.'">' .
       '<span class="label">' .
       __( $label, 'fxreviews' ) .
       '</span>' .
       '</label>' .
       '</div>' .
       '<input id="name-field" name="'.$name.'" class="'.$class.'" type="text" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
       echo $output;
   }

    public static function select($label, $name, array $valueArray, $formFill, $required = ''){

        $output ='<div class="field">' .
        '<label class="field-label '.$required.'">' .
        '<span class="label">' .
        __( $label, 'fxreviews' ) .
        '</span>' .
        '</label>' .
        '</div>' .
        '<select name="'.$name.'">';
        foreach ( $valueArray as $arrayKey => $arrayValue ) {
            $valueSelected = $arrayKey == $formFill ? ' selected="selected" ' : '';
            $output .='<option ' . $valueSelected . ' value="'. $arrayKey .'">' . $label . '</option>';        }
        $output .='</select>';
        echo $output;
    }

    public static function selectManyToOne($label, $name, array $valueArray, $formFill, $required = ''){

        $output ='<div class="field">' .
            '<label class="field-label">' .
            '<span class="label '.$required.'">' .
            __( $label, 'fxreviews' ) .
            '</span>' .
            '</label>' .
            '</div>';
        if (!empty($formFill)) {
            $formFill = array_chunk($formFill, ceil(count($formFill) / 2));

            $output .= '<table>';
            $output .= '<tr>';
            foreach ((array)$formFill as $i => $items) {
                $output .= '<td width="25%">';
                $output .= '<ul class="list-unstyled">';?>
                <?php foreach ($items as $item):

                    $logo = FXREVIEWS_PLUGIN_URL . $valueArray['image_path'] . $item['logo'];
                    $img = '<img src="'.$logo.'" alt="">';

                    $output .= '<li>
                                       <div class="form-check">
                                           <input class="form-check-input" name="'.$name.'[]" type="checkbox" value="'.$item['id'].'"
                                                  id="my-result-'.$item['id'].'">
                                           <label class="form-check-label"
                                                  for="flexCheckDefault">'.$img. ' ' .$item['payment_name'].'</label>
                                       </div>
                                   </li>';

                    ?>
                <?php endforeach;
                $output .= '</ul>';
                $output .= '</td>';?>

                <?php
            }
            $output .= '</tr>';
            $output .= '</table>';
        }
        echo $output;
    }

    public static function textarea($label, $name, $formFill, $required = ''){

        $output ='<div class="field">' .
        '<label class="field-label '.$required.'">' .
        '<span class="label">' .
        __( $label, 'fxreviews' ) .
        '</span>' .
        '</label>' .
        '</div>' .
        '<textarea rows="10" cols="90" name="'.$name.'" class="">'.
        stripslashes( $formFill['short_description'] ).
        '</textarea>';
        echo $output;
    }

    public static function renderAdminLanguageSelector(array $getAllLanguageAdm, string $languageSysName){

        $output = '';
        $output .= '<select name="languageSysName">';
        $output .='<option value="">Выбрать язык</option>';
        foreach ( $getAllLanguageAdm as $language ) {
            $selected = $language['language_sys_name'] == $languageSysName ? ' selected="selected" ' : '';
            $output .='<option ' . $selected . ' value="'. $language['language_sys_name'] .'">' . $language['language_name'] . '</option>';
        }
        $output .= '</select>';
        $output .= '<input type="hidden" value="selectLanguageAdm" name="selectLanguageAdm"/>';
        $output .= '<input class="button button-primary" type="submit" value="' . __( 'Выбрать', 'fxreviews' ) . '"/>
                    </form><br/>';
        return $output;
    }

    public static function renderAdminHead(string $head){

        $output = '';
        $output .= '<h1>';
        $output .= $head;
        $output .= '</h1>';
        return $output;
    }

    public static function renderAdminHeadOfTableList(array $columnDisplayNames){

        $output = '';
        $output .= '<thead>';
        $output .= '<tr>';

        foreach ( $columnDisplayNames as $key => $columnDisplayName ) {

            $class = $key;
            $output .= "<th scope='col' class='$class'>$columnDisplayName</th>";
        }

        $output .= '</tr>';
        $output .= '</thead>';
        return $output;
    }
}