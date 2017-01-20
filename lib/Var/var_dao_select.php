<?php

/**
 * REX_DAO_SELECT.
 *
 * Attribute:
 *   - category  => Kategorie in die beim oeffnen der Linkmap gesprungen werden soll
 *   - label     => Formlabel in der Moduleingabe
 *   - options   => Please select|optGroup A:H1=h1,H2=h2|optGroup B:H3=h3,H4=h4"]
 *   - output    => form | form:2:10 | form:col-sm-5:col-sm-7 | link | url
 *   - widget    => Anzeige des Widgets
 *
 * @package redaxo\structure
 */
class rex_var_dao_select extends rex_var
{
    protected function getOutput()
    {
        $name = $this->getParsedArg('name', null, true);
        $multiple = $this->getParsedArg('multiple', false);
        $value = null;
        if (($this->environmentIs(self::ENV_INPUT) || $this->environmentIs(self::ENV_OUTPUT)) && $this->hasArg('id')) {
            $id = $this->getArg('id', 0, true);
            if (!in_array($this->getContext(), ['module', 'action']) || !is_numeric($id) || $id < 1 || $id > 20) {
                return false;
            }
            $name = 'REX_INPUT_VALUE[' . $id . ']';
            $value = $this->getContextData()->getValue('value' . $id);
            if ($this->hasArg('multiple') && $this->getArg('multiple')) {
                $value = rex_var::toArray($value);
            }
        } else {
            if ($this->hasArg('selected') && $this->getArg('selected')) {
                $value = $this->getArg('selected');
                if ($this->hasArg('multiple') && $this->getArg('multiple')) {
                    $value = explode(',', $this->getArg('selected'));
                }
            }
            if ($this->hasArg('name') && $this->getArg('name')) {
                $name = $this->getArg('name');
                if (substr($name, -2) == '[]') {
                    $name = substr($name, 0, -2);
                }
            }
        }

        if ($this->hasArg('isset') && $this->getArg('isset')) {
            return $value ? 'true' : 'false';
        }

        if (($this->environmentIs(self::ENV_OUTPUT) && !$this->hasArg('id') && $name === null) || (!$this->environmentIs(self::ENV_OUTPUT) && $name === null)) {
            return false;
        }

        if ($this->hasArg('options') && $this->getArg('options')) {
            $options = $this->getArg('options');
            $multiple = $multiple ? 1 : 0;

            if ($this->hasArg('widget') && $this->getArg('widget')) {
                $widget = '<div class="rex-select-style">' . self::getSelect($name, $options, $value, $multiple) . '</div>';

                if ($this->hasArg('output') && $this->getArg('output')) {
                    $label = $this->hasArg('label') ? $this->getArg('label') : '';
                    $widget = Dao::getForm($widget, $label, $this->getArg('output'));
                }
                return self::quote($widget);
            } elseif ($name !== null) {
                return __CLASS__ . '::getSelect("' . $name . '", "' . $options . '", "' . $value . '", ' . $multiple . ')';
            }
        }

        return self::quote(htmlspecialchars($value));
    }

    public static function getSelect($name, $options, $selected, $multiple)
    {
        if (substr($name, 0, 9) != 'REX_INPUT') {
            if ($multiple) {
                $requestValue = rex_request($name, 'array', 'DAO_DEFAULT');
            } else {
                $requestValue = rex_request($name, 'string', 'DAO_DEFAULT');
            }
            $selected = ($requestValue != 'DAO_DEFAULT') ? $requestValue : $selected;
        }

        $select = new rex_select();
        if ($multiple) {
            $select->setName($name . '[]');
            $select->setMultiple();
        } else {
            $select->setName($name);
        }
        $select->setSelected($selected);

        if (rex_sql::getQueryType($options) == 'SELECT') {
            $select->addSqlOptions($options);
        } else {
            $groups = explode('|', $options);
            if (count($groups)) {
                foreach ($groups as $group) {
                    $parseGroup = explode(':', $group);
                    $groupOptions = $parseGroup[0];
                    if (count($parseGroup) == 2) {
                        $select->addOptgroup($parseGroup[0]);
                        $groupOptions = $parseGroup[1];
                    }

                    if (rex_sql::getQueryType($groupOptions) == 'SELECT') {
                        $select->addSqlOptions($groupOptions);
                    } else {
                        $groupOptions = explode(',', $groupOptions);
                        if (count($groupOptions)) {
                            foreach ($groupOptions as $groupOption) {
                                $optionPair = explode('=', $groupOption);
                                if (count($optionPair) == 1) {
                                    $select->addOption($optionPair[0], $optionPair[0]);
                                } elseif (count($optionPair) == 2) {
                                    $select->addOption($optionPair[0], $optionPair[1]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $select->get();
    }
}
