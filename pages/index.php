<?php

/**
 * This file is part of the Dao Var package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$subpage = rex_be_controller::getCurrentPagePart(2);

echo rex_view::title(rex_i18n::msg('dao_var_title'));

include rex_be_controller::getCurrentPageObject()->getSubPath();
