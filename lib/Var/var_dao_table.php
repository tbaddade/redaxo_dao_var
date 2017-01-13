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
    public static $initHeadRows = 0;
    public static $initFootRows = 0;
    public static $initBodyRows = 2;
    public static $initCols = 2;
    public static $initTitleCols = 0;

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
            echo '<pre>';
            print_r($value);
            echo '</pre>';

            $initHeadRows = $this->hasArg('trows') ? $this->getArg('trows') : self::$initHeadRows;
            $initFootRows = $this->hasArg('frows') ? $this->getArg('frows') : self::$initFootRows;
            $initBodyRows = $this->hasArg('rows') ? $this->getArg('rows') : self::$initBodyRows;
            $initCols = $this->hasArg('cols') ? $this->getArg('cols') : self::$initCols;
            $initTitleCols = $this->hasArg('tcols') ? $this->getArg('tcols') : self::$initTitleCols;

            if (count($value) == 0) {
                $appendCols = array_fill(1, $initCols, '');
                if ($initHeadRows > 0) {
                    $value['thead'] = array_fill(1, $initHeadRows, $appendCols);
                }

                if ($initFootRows > 0) {
                    $value['tfoot'] = array_fill(1, $initFootRows, $appendCols);
                }

                $value['tbody'] = array_fill(1, $initBodyRows, $appendCols);
            }

            $table = '';
            $table .= '<table id="toolbox">
				<tbody>
					<tr>
						<td>
							<span class="btn btn-apply" onclick="redips.merge()" title="Merge marked table cells horizontally and verically">Merge</span>
						</td>
						<td>
							<span class="btn btn-apply" onclick="redips.split(\'h\')" title="Split marked table cell horizontally">Split H</span>
							<span class="btn btn-apply" onclick="redips.split(\'v\')" title="Split marked table cell vertically">Split V</span>
						</td>
						<td>
							<span class="btn btn-apply" onclick="redips.row(\'insert\')" title="Add table row">Row +</span>
							<span class="btn btn-apply" onclick="redips.row(\'delete\')" title="Delete table row">Row -</span>
						</td>
						<td>
							<span class="btn btn-apply" onclick="redips.column(\'insert\')" title="Add table column">Col +</span>
							<span class="btn btn-apply" onclick="redips.column(\'delete\')" title="Delete table column">Col -</span>
						</td>
					</tr>
				</tbody>
			</table>';
            $table .= '<table id="dao-table" class="dao-table table table-bordered">';

            $rows = isset($value['thead']) ? $value['thead'] : [];
            $table .= $this->getTableGroup($id, 'thead', $rows, $initHeadRows, $initCols);

            $rows = isset($value['tfoot']) ? $value['tfoot'] : [];
            $table .= $this->getTableGroup($id, 'tfoot', $rows, $initFootRows, $initCols);

            $rows = $value['tbody'];
            $table .= $this->getTableGroup($id, 'tbody', $rows, $initBodyRows, $initCols, $initTitleCols);

            $table .= '</table>';
            $table .= '
            <script>

//$(document).on("rex:ready", function (event, container) {
//jQuery(function($) {
getCellCoords = function (table) {
		var matrix = [],
			matrixrow,
			lookup = {},
			c,			// current cell
			ri,			// row index
			rowspan,
			colspan,
			firstAvailCol,
			tr,			// TR collection
			i, j, k, l;	// loop variables
		// set HTML collection of table rows
		tr = table.rows;
		// open loop for each TR element
		for (i = 0; i < tr.length; i++) {
			// open loop for each cell within current row
			for (j = 0; j < tr[i].cells.length; j++) {
				// define current cell
				c = tr[i].cells[j];
				// set row index
				ri = c.parentNode.rowIndex;
				// define cell rowspan and colspan values
				rowspan = c.rowSpan || 1;
				colspan = c.colSpan || 1;
				// if matrix for row index is not defined then initialize array
				matrix[ri] = matrix[ri] || [];
				// find first available column in the first row
				for (k = 0; k < matrix[ri].length + 1; k++) {
					if (typeof(matrix[ri][k]) === "undefined") {
						firstAvailCol = k;
						break;
					}
				}
				// set cell coordinates and reference to the table cell
				lookup[ri + "-" + firstAvailCol] = c;
				for (k = ri; k < ri + rowspan; k++) {
					matrix[k] = matrix[k] || [];
					matrixrow = matrix[k];
					for (l = firstAvailCol; l < firstAvailCol + colspan; l++) {
						matrixrow[l] = "x";
					}
				}
			}
		}
		return lookup;
	};

    // create redips container
    var redips = {};
    var tbl = document.getElementById("dao-table");
    var id = ' . $id . ';


    // REDIPS.table initialization
    redips.init = function () {
        // define reference to the REDIPS.table object
        var rt = REDIPS.table;
        // activate onmousedown event listener on cells within table with id="mainTable"
        rt.onmousedown(tbl, true);
        // show cellIndex (it is nice for debugging)
        // rt.cell_index(false);
        // define background color for marked cell
        rt.color.cell = "#9BB3DA";
        redips.updateTable("init");
    };


    // function merges table cells
    redips.merge = function () {
        // first merge cells horizontally and leave cells marked
        REDIPS.table.merge("h", false);
        // and then merge cells vertically and clear cells (second parameter is true by default)
        REDIPS.table.merge("v", false);
        redips.updateTable("merge");
    };


    // function splits table cells if colspan/rowspan is greater then 1
    // mode is "h" or "v" (cells should be marked before)
    redips.split = function (mode) {
        REDIPS.table.split(mode);
        redips.updateTable("split");
    };


    // insert/delete table row
    redips.row = function (type) {
        REDIPS.table.row(tbl, type);
        redips.updateTable(type);
    };


    // insert/delete table column
    redips.column = function (type) {
        REDIPS.table.column(tbl, type);
        redips.updateTable(type);
    };


    redips.updateTable = function (type) {
        console.log(getCellCoords(tbl));
        if (tbl.tHead !== null && tbl.tHead.rows.length > 0) {
            var thead_tr = tbl.tHead.rows;
            redips.updateCell(type, "thead", thead_tr);
        }

        if (tbl.tBodies !== null && tbl.tBodies[0].rows.length > 0) {
            var tbody_tr = tbl.tBodies[0].rows;
            redips.updateCell(type, "tbody", tbody_tr);
        }

        if (tbl.tFoot !== null && tbl.tFoot.rows.length > 0) {
            var tfoot_tr = tbl.tFoot.rows;
            redips.updateCell(type, "tfoot", tfoot_tr);
        }

    }
    redips.updateCell = function (type, group, rows) {
        // local variable
        var r, c, numberOfRows, numberOfCells, cell, cellContent;
        numberOfRows = rows.length;
        // loop through all TD elements
        for (r = 0; r < numberOfRows; r++) {
            numberOfCells = rows[r].cells.length;
            // loop through all TD elements
            var $cIndex = -1;
            for (c = 0; c < numberOfCells; c++) {
                cell = rows[r].cells[c];
                $cIndex++;
                if (cell.colSpan > 1) {
                    $cIndex = $cIndex + cell.colSpan - 1;
                }

                // ignore thead and tfoot
                // no merge and split
                if (group == "thead" || group == "tfoot") {
                    REDIPS.table.cell_ignore(cell);
                }

                if (type == "init" || type == "insert" || type == "split") {
                    if (cell.innerHTML == "") {
                        //cell.innerHTML += \'<input type="hidden" name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ c + \'][colspan]" value="\' + cell.colSpan + \'" />\';
                        //cell.innerHTML += \'<input type="hidden" name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ c + \'][rowspan]" value="\' + cell.rowSpan + \'" />\';
                        cell.innerHTML += \'<textarea name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ $cIndex + \'][cell]"></textarea>\';
                    }
                }
                if (type == "merge" && cell.redips && cell.redips.selected === true) {
                    var textarea = cell.getElementsByTagName("TEXTAREA");
                    var j;
                    var content = "";
                    for (j = 0; j < textarea.length; j++) {
                        content += textarea[j].value;
                    }
                    cell.innerHTML = "";
                    //cell.innerHTML += \'<input title="" name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ c + \'][colspan]" value="\' + cell.colSpan + \'" type="hidden" />\';
                    //cell.innerHTML += \'<input    name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ c + \'][rowspan]" value="\' + cell.rowSpan + \'" type="hidden" />\';
                    cell.innerHTML += \'<textarea name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ $cIndex + \'][cell]">\' + content + \'</textarea>\';
                    REDIPS.table.mark(false, cell);
                }

                cellContent = cell.getElementsByTagName("TEXTAREA")[0].outerHTML;
                cell.innerHTML = "";
                cell.innerHTML += \'<input name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ $cIndex + \'][colspan]" value="\' + cell.colSpan + \'" type="hidden" />\';
                cell.innerHTML += \'<input name="REX_INPUT_VALUE[\'+ id + \'][\'+ group + \'][\'+ r + \'][\'+ $cIndex + \'][rowspan]" value="\' + cell.rowSpan + \'" type="hidden" />\';
                cell.innerHTML += cellContent;


                /*
                // if table cell is selected
                if (td[i].redips && td[i].redips.selected === true) {
                    // set value from selected item to the cell content
                    td[i].innerHTML = "text";
                    // unselect TD
                    REDIPS.table.mark(false, td[i]);
                }
                */
            }
        }
    }

    // add onload event listener
    if (window.addEventListener) {
        window.addEventListener("load", redips.init, false);
    }
    else if (window.attachEvent) {
        window.attachEvent("onload", redips.init);
    }
    $(document).on("rex:ready", function (event, container) {
        redips.init();
    });
//});
            </script>';

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

                $rIndex = -1;
                $cIndex = -1;
                foreach ($row as $colCounter => $cell) {
                    $cellTag = (($initTitleCols > 0 && $colCounter <= $initTitleCols) || ($groupTag == 'thead')) ? 'th' : 'td';
                    $cellTag = 'td';
                    $colspan = isset($cell['colspan']) ? $cell['colspan'] : 1;
                    $rowspan = isset($cell['rowspan']) ? $cell['rowspan'] : 1;
                    $content = isset($cell['cell']) ? $cell['cell'] : '';

                    ++$cIndex;
                    if ($colspan > 1) {
                        $cIndex = $cIndex + $colspan - 1;
                    }
                    ++$rIndex;
                    if ($rowspan > 1) {
                        $rIndex = $rIndex + $rowspan - 1;
                    }
/*
                            <input title="php" type="hidden" name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $rIndex . '][' . $cIndex . '][colspan]" value="' . $colspan . '" />
                            <input title="php" type="hidden" name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $rIndex . '][' . $cIndex . '][rowspan]" value="' . $rowspan . '" />*/
                    $return .= '
                        <' . $cellTag . ' colspan="' . $colspan . '" rowspan="' . $rowspan . '">
                            <textarea title="php" name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $rIndex . '][' . $cIndex . '][cell]">' . $content . '</textarea>
                        </' . $cellTag . '>';
                }
                /*
                if ($colCounter < $initCols) {
                    for ($i = ($colCounter + 1); $i <= $initCols; $i++) {
                        $cellTag = (($initTitleCols > 0 && $colCounter <= $initTitleCols) || ($groupTag == 'thead')) ? 'th' : 'td';
                        $cellTag = 'td';
                        $return .= '<' . $cellTag . '><textarea name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $rowCounter . '][' . $i . ']"></textarea></' . $cellTag . '>';
                    }
                }
                */

                $return .= '</tr>';
            }
        }
        /*
        if ($rowCounter < $initRows) {
            for ($i = ($rowCounter + 1); $i <= $initRows; $i++) {
                $return .= '<tr>';
                for ($j = 1; $j <= $initCols; $j++) {
                    $cellTag = (($initTitleCols > 0 && $j <= $initTitleCols) || ($groupTag == 'thead')) ? 'th' : 'td';
                    $cellTag = 'td';
                    $return .= '<' . $cellTag . '><textarea name="REX_INPUT_VALUE[' . $id . '][' . $groupTag . '][' . $i . '][' . $j . ']"></textarea></' . $cellTag . '>';
                }
                $return .= '</tr>';
            }
        }
        */

        $return = ($return != '') ? '<' . $groupTag . '>' . $return . '</' . $groupTag . '>' : '';

        return $return;
    }
}
