<?php

/**
 * REX_DAO_GROUP[1],.
 *
 * @package redaxo\structure\content
 */
class rex_var_dao_group extends rex_var
{
    protected function getOutput()
    {
        $id = $this->getArg('id', 0, true);
        if (!in_array($this->getContext(), ['module', 'action']) || !is_numeric($id) || $id < 1 || $id > 20) {
            return false;
        }

        $value = $this->getContextData()->getValue('value' . $id);

        if ($this->hasArg('isset') && $this->getArg('isset')) {
            return $value ? 'true' : 'false';
        }


        if ($this->hasArg('widget') && $this->getArg('widget')) {
            $value = $this->toArray($value);
            echo '<pre>';
            print_r($value);
            echo '</pre>';

            $amount = ($this->hasArg('amount')) ? $this->getArg('amount') : 1;
            $output = ($this->hasArg('output')) ? $this->getArg('output') : 'tabs';

            $groups = [];
            for ($i = 0; $this->hasArg($i); ++$i) {
                $arg = $this->getArg($i);
                echo $arg;
                if (preg_match('/(?P<daovar>REX_DAO_[A-Z_]+).*?name=(\'|")+(?P<name>.*?)\2+/s', $arg, $matches)) {
                    // einzelne DAO_VARS modifizieren
                    echo '<pre>';
                    print_r($matches['daovar']);
                    echo '</pre>';
                    $search = [];
                    $replace = [];
                    switch ($matches['daovar']) {
                        case 'REX_DAO_LINK':
                            $search = [
                                'REX_INPUT_VALUE[' . $id . ']',
                                'REX_LINK_' . $id,
                            ];
                            $replace = [
                                'REX_INPUT_VALUE[' . $id . '][{{ amountArg }}][' . $matches['name'] . ']',
                                'REX_LINK_' . $id . '_{{ amountArg }}',
                            ];
                            break;
                        case 'REX_DAO_MEDIA':
                            $search = [
                                'REX_INPUT_MEDIA[' . $id . ']',
                            ];
                            $replace = [
                                'REX_INPUT_VALUE[' . $id . '][{{ amountArg }}][' . $matches['name'] . ']',
                            ];
                            break;
                        case 'REX_DAO_CATEGORY_SELECT':
                        case 'REX_DAO_VALUE':
                            $search = [
                                'REX_INPUT_VALUE[' . $id . ']',
                            ];
                            $replace = [
                                'REX_INPUT_VALUE[' . $id . '][{{ amountArg }}][' . $matches['name'] . ']',
                            ];
                            break;
                        default:
                            break;
                    }
                    $string = str_replace($search, $replace, $this->getParsedArg($i));

                    // modifizierte DAO_VARS die gespeicherten Values zuweisen
                    for ($a = 1; $a <= $amount; ++$a) {
                        $element = str_replace('{{ amountArg }}', $a, $string);
                        if (isset($value[$a]) && isset($value[$a][$matches['name']])) {
                            $search = [];
                            $replace = [];
                            switch ($matches['daovar']) {
                                case 'REX_DAO_CATEGORY_SELECT':
                                    echo '<pre>' . $a; print_r($value[$a][$matches['name']]); echo '</pre>';
                                    $search = [
                                        '/(<option[^>]+value="(?!' . (is_array($value[$a][$matches['name']]) ? implode('|', $value[$a][$matches['name']]) : $value[$a][$matches['name']]) . ')+")(.*?>)/',
                                    ];
                                    $replace = [
                                        '${1} selected="selected"${2}',
                                    ];
                                    break;
                                case 'REX_DAO_LINK':
                                    $varArray = $value[$a][$matches['name']];
                                    foreach ($varArray as $varKey => $varValue) {
                                        $search[] = '/(<input[^>]+name="REX_INPUT_VALUE\[' . $id . '\]\[' . $a . '\]\[' . $matches['name'] . '\]\[' . $varKey . '\]".*?value=").*?(".*?>)/';
                                        $replace[] = '${1}' . $varValue . '${2}';
                                    }
                                    break;
                                default:
                                    $search = [
                                        '/(<input[^>]+value=").*?(".*?>)/',
                                        '/(<textarea[^>]+>).*?(<\/textarea>)/',
                                    ];
                                    $replace = [
                                        '${1}' . $value[$a][$matches['name']] . '${2}',
                                        '${1}' . $value[$a][$matches['name']] . '${2}',
                                    ];
                                    break;
                            }
                            $element = preg_replace($search, $replace, $element);
                        }
                        $groups[$a][] = $element;
                    }
                } else {
                    //$string = $this->getParsedArg($i);
                }

                //$widgets[] = $string;
            }
            //echo '<pre>'; print_r($tabs); echo '</pre>';

            //$widget = implode('.', $widgets);
            /*
            $amount = ($this->hasArg('amount')) ? $this->getArg('amount') : 1;

            for ($i = 1; $i <= $amount; ++$i) {
                foreach ($widgets as $widget) {
                    $tabs[] = str_replace('{{ amountArg }}', $i, $widget);
                }
            }
            */
            //echo '<pre>'; print_r($values); echo '</pre>';
            /*
            if ($this->hasArg('output') && $this->getArg('output')) {
                $label = $this->hasArg('label') ? $this->getArg('label') : '';
                $widget = Dao::getForm($widget, $label, $this->getArg('output'));
            }
            */

            $return = [];
            switch ($output) {
                case 'tabs':
                    $navigation = [];
                    foreach ($groups as $groupId => $groupElements) {
                        $array = array_merge(
                            [self::quote(str_replace('{{ groupId }}', $groupId, '<div class="tab-pane fade in' . (count($navigation) < 1 ? ' active' : '') . '" id="tab-content-{{ groupId }}">'))],
                            $groupElements,
                            [self::quote('</div>')]
                        );
                        $groups[$groupId] = implode('.', $array);
                        $navigation[] = '<li' . (count($navigation) < 1 ? ' class="active"' : '') . '><a href="#tab-content-' . $groupId . '" data-toggle="tab">Home</a></li>';
                    }
                    $return = [
                        self::quote('<div><ul class="nav nav-tabs">' . implode('', $navigation) . '</ul><div class="tab-content">'),
                        implode('.', $groups),
                        self::quote('</div></div>'),
                    ];
                    break;
            }

            return implode('.', $return);
        }
        /*
        $output = $this->getArg('output');
        if ($output == 'php') {
            if ($this->environmentIs(self::ENV_BACKEND)) {
                $value = rex_string::highlight($value);
            } else {
                return 'rex_var::nothing(require rex_stream::factory(substr(__FILE__, 6) . \'/REX_VALUE/' . $id . '\', ' . self::quote($value) . '))';
            }
        } elseif ($output == 'html') {
            $value = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $value);
        } else {
            $value = htmlspecialchars($value);
            if (!$this->environmentIs(self::ENV_INPUT)) {
                $value = nl2br($value);
            }
        }
        */
        return self::quote($value);
    }
}
