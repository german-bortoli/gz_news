<?php

/**
 * GzNewsForm to add and edit news
 *
 * @author Bortoli German
 */
class GzNewsForm extends Form {

    static protected $session_values = array();

    static public function initForm() {
        $tmp_session_values = Session::newInstance()->_getForm('new_item');
        if (is_array($tmp_session_values) && !empty($tmp_session_values)) {
            self::setSessionValues($tmp_session_values);
        }
    }
    
    static public function destroyForm() {
        self::setSessionValues();
        Session::newInstance()->_dropKeepForm('new_item');
    }

    static public function getSessionValues() {
        return self::$session_values;
    }

    static public function setSessionValues($session_values = array()) {
        self::$session_values = $session_values;
    }

    static public function getSessionKey($key) {
        $session_values = self::getSessionValues();

        if (is_array($session_values) && !empty($session_values)) {
            if (array_key_exists($key, $session_values)) {
                return $session_values[$key];
            }
        }

        return NULL;
    }

    static public function setInputValueFor($name, & $value = NULL) {
        if (!$name) {
            return FALSE;
        }
        
        $session_value = self::getSessionKey($name);
        if ($session_value) {
            $value = $session_value;
        }
    }

    static public function title_input($value = NULL, $readOnly = false, $autocomplete = true) {
        $name = 'new_item[gn_title]';
        $maxLength = 249;

        self::setInputValueFor('gn_title', $value);

        parent::generic_input_text($name, $value, $maxLength, $readOnly, $autocomplete);
        return true;
    }

    static public function tags_input($value = NULL, $readOnly = false, $autocomplete = true) {
        $name = 'new_item[gn_tags]';
        $maxLength = 249;

        self::setInputValueFor('gn_tags', $value);
        
        parent::generic_input_text($name, $value, $maxLength, $readOnly, $autocomplete);
        return true;
    }

    static public function description_input($value = NULL) {
        $name = 'new_item[gn_description]';
        
        self::setInputValueFor('gn_description', $value);
        
        parent::generic_textarea($name, $value);
    }

    static public function language_selector($value = NULL) {
        $name = 'new_item[gn_lang]';

        self::setInputValueFor('gn_lang', $value);
        
        $locales = osc_all_enabled_locales_for_admin();

        if ($value == NULL) {
            $value = osc_current_user_locale();
        }


        if (count($locales) > 1) {

            echo '<select name="' . $name . '" id="news_lang_selector">';
            foreach ($locales as $locale) {
                $selected = '';
                if ($value == $locale['pk_c_code']) {
                    $selected = 'selected="selected"';
                };
                echo '<option value="' . $locale ['pk_c_code'] . '" ' . $selected . '>' . $locale['s_short_name'] . '</option>';
            }
            echo '</select>';
        } else {
            echo $locale['s_short_name'];
            echo '<input type="hidden" name="locale" value="' . $locales[0]["pk_c_code"] . '" />';
        }
    }

}
