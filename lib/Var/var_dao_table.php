<?php

/**
 * REX_DAO_TABLE.
 *
 * Attribute:
 *   - label     => Formlabel in der Moduleingabe
 *   - cols      => Anzahl der Tabellenspalten
 *   - rows      => Anzahl der Tabellenreihen im <tbody>
 *   - trows     => TitleROWS - Anzahl der Tabellenzeilen (<tr>) im <thead>
 *   - tcols     => TitleCOLS - Anzahl der Tabellenspalten (<th>) im <tbody>
 *   - frows     => FooterROWS - Anzahl der Tabellenzeilen im <tfoot>
 *   - output    => form | form:2:10 | form:col-sm-5:col-sm-7
 *   - widget    => Anzeige der Tabelle als Eingabemaske
 *
 * @package redaxo\structure
 */
class rex_var_dao_table extends rex_var
{
    static $initHeadRows = 0;
    static $initFootRows = 0;
    static $initBodyRows = 2;
    static $initCols = 2;
    static $initTitleCols = 0;

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
            $value = rex_var::toArray($value);


            $initHeadRows = $this->hasArg('trows') ? $this->getArg('trows') : self::$initHeadRows;
            $initFootRows = $this->hasArg('frows') ? $this->getArg('frows') : self::$initFootRows;
            $initBodyRows = $this->hasArg('rows') ? $this->getArg('rows') : self::$initBodyRows;
            $initCols = $this->hasArg('cols') ? $this->getArg('cols') : self::$initCols;
            $initTitleCols = $this->hasArg('tcols') ? $this->getArg('tcols') : self::$initTitleCols;

            if (count($value) == 0) {
                $appendCols = array_fill(1, $initCols, '');
                if ($initHeadRows > 0) {
                    $value['thead'] = array_fill(1, $initHeadRows, $appendCols);
                } else {
                    $value['thead'] = [];
                }

                if ($initFootRows > 0) {
                    $value['tfoot'] = array_fill(1, $initFootRows, $appendCols);
                } else {
                    $value['tfoot'] = [];
                }
                $value['tbody'] = array_fill(1, $initBodyRows, $appendCols);
            }

            $table = '';
            $table .= '<table class="dao-table table table-bordered">';

            $rows = $value['thead'];
            $table .= $this->getTableGroup($id, 'thead', $rows, $initHeadRows, $initCols);

            $rows = $value['tfoot'];
            $table .= $this->getTableGroup($id, 'tfoot', $rows, $initFootRows, $initCols);

            $rows = $value['tbody'];
            $table .= $this->getTableGroup($id, 'tbody', $rows, $initBodyRows, $initCols, $initTitleCols);

            $table .= '</table>';

            $table .= '
            <style>
            .dao-table,
            .dao-table > thead > tr > th,
            .dao-table > thead > tr > td,
            .dao-table > tbody > tr > th,
            .dao-table > tbody > tr > td,
            .dao-table > tfoot > tr > th,
            .dao-table > tfoot > tr > td {
                padding: 0;
                border-color: #ccc;
            }
            .dao-table textarea {
                width: 100%;
                padding: 5px;
                border: 0;
                font-size: 1.2rem;
                resize: none;
            }
            .dao-table textarea:focus {
                outline: 0;
            }
            .dao-table > thead > tr > th,
            .dao-table > thead > tr > td,
            .dao-table > thead textarea {
                background-color: #ebebeb;
            }
            .dao-table > thead > tr > th,
            .dao-table > thead > tr > td {
                border-width: 1px;
            }
            .dao-table > thead > tr:last-child > th,
            .dao-table > thead > tr:last-child > td {
                border-bottom-color: #999;
            }
            .dao-table > tbody > tr > th,
            .dao-table > tbody > tr > th > textarea {
                background-color: #f5f5f5;
            }
            .dao-table > tfoot > tr > th,
            .dao-table > tfoot > tr > td,
            .dao-table > tfoot textarea {
                background-color: #fff;
            }
            .dao-table > tfoot > tr:first-child > th,
            .dao-table > tfoot > tr:first-child > td {
                border-top-color: #999;
                border-top-width: 2px;
            }
            </style>
            ';

            $widget = $table;

            if ($this->hasArg('output') && $this->getArg('output')) {
                $label = $this->hasArg('label') ? $this->getArg('label') : '';
                $widget = Dao::getForm($widget, $label, $this->getArg('output'));
            }
            return self::quote($widget);
        }

        return self::quote(htmlspecialchars($value));
    }


    protected function getTableGroup($id, $groupTag, $rows, $initRows, $initCols, $initTitleCols = 0)
    {
        $return = '';

        $rowCounter = 0;
        if (count($rows)) {
            foreach ($rows as $rowCounter => $row) {
                $return .= '<tr>';

                $colCounter = 0;
                foreach ($row as $colCounter => $cell) {
                    $cellTag = (($initTitleCols > 0 && $colCounter <= $initTitleCols) || ($groupTag == 'thead')) ? 'th' : 'td';
                    $return .= '<' . $cellTag . '><textarea name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $rowCounter . '][' . $colCounter . ']">' . $cell . '</textarea></' . $cellTag . '>';
                }
                if ($colCounter < $initCols) {
                    for ($i = ($colCounter + 1); $i <= $initCols; $i++) {
                        $cellTag = (($initTitleCols > 0 && $colCounter <= $initTitleCols) || ($groupTag == 'thead')) ? 'th' : 'td';
                        $return .= '<' . $cellTag . '><textarea name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $rowCounter . '][' . $i . ']"></textarea></' . $cellTag . '>';
                    }
                }

                $return .= '</tr>';
            }
        }
        if ($rowCounter < $initRows) {
            for ($i = ($rowCounter + 1); $i <= $initRows; $i++) {
                $return .= '<tr>';
                for ($j = 1; $j <= $initCols; $j++) {
                    $cellTag = (($initTitleCols > 0 && $j <= $initTitleCols) || ($groupTag == 'thead')) ? 'th' : 'td';
                    $return .= '<' . $cellTag . '><textarea name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $i . '][' . $j . ']"></textarea></' . $cellTag . '>';
                }
                $return .= '</tr>';
            }
        }

        $return = '<' . $groupTag . '>' . $return . '</' . $groupTag . '>';

        return $return;
    }
}
