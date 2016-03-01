<?php

/**
 * REX_DAO_CATEGORY_SELECT.
 *
 * Attribute:
 *   - label     => Formlabel in der Moduleingabe
 *   - output    => form | form:2:10 | form:col-sm-5:col-sm-7 | link | url
 *   - root      => 5
 *   - widget    => Anzeige des Widgets
 *
 * @package redaxo\structure
 */
class rex_var_dao_category_select extends rex_var
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
            $select = new rex_category_select();
            if ($this->hasArg('multiple') && $this->getArg('multiple')) {
                $select->setName('REX_INPUT_VALUE[' . $id . '][]');
                $select->setMultiple();
                $select->setSelected(rex_var::toArray($value));
            } else {
                $select->setName('REX_INPUT_VALUE[' . $id . ']');
                $select->setSelected($value);
            }
            if ($this->hasArg('root') && $this->getArg('root')) {
                $select->setRootId(explode(',', $this->getArg('root')));
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
