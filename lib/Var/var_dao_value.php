<?php

/**
 * REX_DAO_VALUE[1],.
 *
 * @package redaxo\structure\content
 */
class rex_var_dao_value extends rex_var
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

        $classes = [];
        if ($this->hasArg('class') && $this->getArg('class')) {
            $classes[] = $this->getArg('class');
        }

        if ($this->hasArg('widget') && $this->getArg('widget')) {
            $value = htmlspecialchars($value);
            if (!$this->environmentIs(self::ENV_INPUT)) {
                $value = nl2br($value);
            }

            $classes[] = 'form-control';
            $class = ' class="' . implode(' ', $classes) . '"';

            $type = $this->getArg('type', 'text', true);
            switch ($type) {
                case 'textarea':
                    $widget = '<textarea' . $class . ' name="REX_INPUT_VALUE[' . $id . ']" rows="10">' . $value . '</textarea>';
                    break;
                default:
                    $widget = '<input' . $class . ' type="' . $type . '" name="REX_INPUT_VALUE[' . $id . ']" value="' . $value . '" />';
                    break;
            }

            if ($this->hasArg('output') && $this->getArg('output')) {
                $label = $this->hasArg('label') ? $this->getArg('label') : '';
                $widget = Dao::getForm($widget, $label, $this->getArg('output'));
            }

            return self::quote($widget);
        }

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

        return self::quote($value);
    }
}
