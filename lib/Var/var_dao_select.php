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
        $id = $this->getArg('id', 0, true);
        if (!in_array($this->getContext(), ['module', 'action']) || !is_numeric($id) || $id < 1 || $id > 20) {
            return false;
        }

        $value = $this->getContextData()->getValue('value' . $id);

        if ($this->hasArg('isset') && $this->getArg('isset')) {
            return $value ? 'true' : 'false';
        }

        if ($this->hasArg('widget') && $this->getArg('widget')) {
            if (!$this->environmentIs(self::ENV_INPUT)) {
                return false;
            }
            $select = new rex_select();
            if ($this->hasArg('multiple') && $this->getArg('options')) {
                $select->setName('REX_INPUT_VALUE[' . $id . '][]');
                $select->setMultiple();
                $select->setSelected(rex_var::toArray($value));
            } else {
                $select->setName('REX_INPUT_VALUE[' . $id . ']');
                $select->setSelected($value);
            }
            if ($this->hasArg('options') && $this->getArg('options')) {
                $options = $this->getArg('options');
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
            }
            $widget = '<div class="rex-select-style">' . $select->get() . '</div>';

            if ($this->hasArg('output') && $this->getArg('output')) {
                $label = $this->hasArg('label') ? $this->getArg('label') : '';
                $widget = Dao::getForm($widget, $label, $this->getArg('output'));
            }
            return self::quote($widget);
        }

        return self::quote(htmlspecialchars($value));
    }
}
