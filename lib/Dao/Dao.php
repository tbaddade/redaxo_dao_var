<?php

/**
 * This file is part of the Dao Var package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Dao
{
    public static function getForm($field, $label, $output)
    {
        $labelClass = 'col-md-2';
        $fieldClass = ($label == '') ? 'col-md-12' : 'col-md-10';

        $output = explode(':', $output);
        if (count($output) == 3) {
            $labelClass = (is_numeric($output[1])) ? 'col-md-' . $output[1] : $output[1];
            $fieldClass = (is_numeric($output[2])) ? 'col-md-' . $output[2] : $output[2];
        }

        $fragment = new rex_fragment();
        $fragment->setVar('label', $label, false);
        $fragment->setVar('field', $field, false);
        $fragment->setVar('labelClass', $labelClass, false);
        $fragment->setVar('fieldClass', $fieldClass, false);
        return $fragment->parse('dao/form.php');
    }
}
