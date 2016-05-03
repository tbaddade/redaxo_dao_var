<?php

/**
 * REX_DAO_LINK.
 *
 * Attribute:
 *   - category  => Kategorie in die beim oeffnen der Linkmap gesprungen werden soll
 *   - label     => Formlabel in der Moduleingabe
 *   - output    => form | form:2:10 | form:col-sm-5:col-sm-7 | link | url
 *   - widget    => Anzeige des Widgets
 *
 * @package redaxo\structure
 */
class rex_var_dao_link extends rex_var
{
    protected function getOutput()
    {
        $id = $this->getArg('id', 0, true);
        if (!in_array($this->getContext(), ['module', 'action']) || !is_numeric($id) || $id < 1 || $id > 20) {
            return false;
        }

        $valueArray = rex_var::toArray($this->getContextData()->getValue('value' . $id));
        $value = isset($valueArray['value']) ? $valueArray['value'] : '';
        $clang = isset($valueArray['clang']) ? $valueArray['clang'] : '';
        $label = isset($valueArray['label']) ? $valueArray['label'] : '';

        if ($this->hasArg('isset') && $this->getArg('isset')) {
            return $value ? 'true' : 'false';
        }

        if ($this->hasArg('widget') && $this->getArg('widget')) {
            if (!$this->environmentIs(self::ENV_INPUT)) {
                return false;
            }
            $args = [];
            if (!$this->hasArg('linkmap')) {
                $args['linkmap'] = true;
            }
            if (!$this->hasArg('url')) {
                $args['url'] = false;
            }
            if (!$this->hasArg('text')) {
                $args['text'] = false;
            }
            foreach (['category', 'linkmap', 'url', 'text'] as $key) {
                if ($this->hasArg($key)) {
                    $args[$key] = $this->getArg($key);
                }
            }
            $widget = self::getWidget($id, 'REX_INPUT_VALUE[' . $id . '][value]', $valueArray, $args);

            $label = $this->hasArg('label') ? $this->getArg('label') : '';
            $widget = Dao::getForm($widget, $label, $this->getArg('output'));

            return self::quote($widget);
        } elseif($this->hasArg('output') && $this->getArg('output')) {
            if (is_numeric($value)) {
                if ($label == '') {
                    $art = rex_article::get($value);
                    if ($art instanceof rex_article) {
                        $label = $art->getName();
                    }
                }
                $value = rex_getUrl($value, $clang);
            } else {
                if ($label == '') {
                    $label = $value;
                }
            }
            if ($this->getArg('output') == 'link') {
                $class = '';
                if ($this->hasArg('class')) {
                    $class = ' class="' . $this->getArg('class') . '"';
                }
                return self::quote('<a' . $class . ' href="' . $value . '">' . $label . '</a>');
            } else {
                return self::quote($value);
            }
        }

        return self::quote(json_encode($valueArray));
    }

    public static function getWidget($id, $name, $valueArray, array $args = [])
    {
        $value = isset($valueArray['value']) ? $valueArray['value'] : '';
        $label = isset($valueArray['label']) ? $valueArray['label'] : '';
        $clang = isset($valueArray['clang']) ? $valueArray['clang'] : rex_clang::getCurrentId();

        $art_name = '';
        $art = rex_article::get($value, $clang);
        $category = 0;

        // Falls ein Artikel vorausgewählt ist, dessen Namen anzeigen und beim öffnen der Linkmap dessen Kategorie anzeigen
        if ($art instanceof rex_article) {
            $art_name = $art->getName();
            $category = $art->getCategoryId();
        }

        $open_params = '&clang=' . rex_clang::getCurrentId();
        if ($category || isset($args['category']) && ($category = (int) $args['category'])) {
            $open_params .= '&category_id=' . $category;
        }

        $class = ' rex-disabled';
        $open_func = '';
        $delete_func = '';
        if (rex::getUser()->getComplexPerm('structure')->hasStructurePerm()) {
            $class = '';
            $open_func = 'openLinkMap(\'REX_LINK_' . $id . '\', \'' . $open_params . '\');';
            $delete_func = 'deleteREXLink(' . $id . ');';
        }


        $e = [];
        $e['field'] = '';
        $e['functionButtons'] = '';
        if ($args['text']) {
            $e['field'] .= '<input style="width: 40%;" class="form-control" type="text" placeholder="Label" name="REX_INPUT_VALUE[' . $id . '][label]" value="' . htmlspecialchars($label) . '" />';
        }
        if ($args['linkmap'] && $args['url']) {
            $e['field'] .= '
                <input style="width: ' . ($args['text'] ? '40' : '80') . '%;" class="form-control" type="text" placeholder="Url / Id" name="' . $name . '" id="REX_LINK_' . $id . '" value="' . $value . '" />
                <input type="hidden" name="REX_INPUT_VALUE[' . $id . '][clang]" value="' . $clang . '" id="REX_LINK_' . $id . '_CLANG" />
                <input style="width: 20%;" class="form-control" type="text" readonly="readonly" name="REX_INPUT_VALUE[' . $id . '][name]" value="' . htmlspecialchars($art_name) . '" id="REX_LINK_' . $id . '_NAME" />';
            $e['functionButtons'] .= '
                <a href="#" class="btn btn-popup' . $class . '" onclick="' . $open_func . 'return false;" title="' . rex_i18n::msg('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>
                <a href="#" class="btn btn-popup' . $class . '" onclick="' . $delete_func . 'return false;" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';
        } elseif ($args['linkmap']) {
            $e['field'] .= '
                <input type="hidden" name="' . $name . '" id="REX_LINK_' . $id . '" value="' . $value . '" />
                <input type="hidden" name="REX_INPUT_VALUE[' . $id . '][clang]" value="' . $clang . '" id="REX_LINK_' . $id . '_CLANG" />
                <input style="width: ' . ($args['text'] ? '60' : '100') . '%;" class="form-control" type="text" readonly="readonly" name="REX_INPUT_VALUE[' . $id . '][name]" value="' . htmlspecialchars($art_name) . '" id="REX_LINK_' . $id . '_NAME" />';
            $e['functionButtons'] .= '
                <a href="#" class="btn btn-popup' . $class . '" onclick="' . $open_func . 'return false;" title="' . rex_i18n::msg('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>
                <a href="#" class="btn btn-popup' . $class . '" onclick="' . $delete_func . 'return false;" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';
        } elseif ($args['url']) {
            $e['field'] .= '
                <input style="width: ' . ($args['text'] ? '60' : '100') . '%;" class="form-control" type="text" placeholder="Url" name="' . $name . '" id="REX_LINK_' . $id . '" value="' . $value . '" />
            ';
            $e['functionButtons'] .= '
                <a href="#" class="btn btn-popup' . $class . '" onclick="' . $delete_func . 'return false;" title="' . rex_i18n::msg('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>';

        }
        $fragment = new rex_fragment();
        $fragment->setVar('elements', [$e], false);
        $media = $fragment->parse('core/form/widget.php');


        return $media;
    }
}
