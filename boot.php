<?php

/**
 * This file is part of the Url package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Convert some text to Markdown...
 */
function markdown($text)
{
    return (new ParsedownExtra())->text($text);
}

if (rex::isBackend()) {
    rex_view::addCssFile($this->getAssetsUrl('dao.css'));
    rex_view::addJsFile($this->getAssetsUrl('vendor/redips/redips-table.js'));

    if (rex_be_controller::getCurrentPage() == 'dao_var/readme') {
        rex_view::addJsFile($this->getAssetsUrl('prism.js'));
    }

    rex_extension::register('CHEATSHEET_PROVIDER', function(\rex_extension_point $ep) {
        $subject = $ep->getSubject();
        $subject[] = '\DaoVar\Cheatsheet\CheatsheetServiceProvider';
        $ep->setSubject($subject);
    });
}
