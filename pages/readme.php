<?php

/**
 * This file is part of the Dao Var package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$string = 'REX_DAO_LINK[]';
$var = '<code>' . $string . '</code>';

$string = 'REX_DAO_LINK[id="1" widget="1"]';
$varModuleInput = '<code>' . $string . '</code>';

$output = [
    'REX_DAO_LINK[id="1"]' => '{"label":"$LABEL$","value":"$URL_ID$","name":"$ARTICLE_NAME$"}',
    'REX_DAO_LINK[id="1" output="url"]' => '$URL$',
    'REX_DAO_LINK[id="1" output="link"]' => '<a href="$URL$">$LABEL$</a>',
    'REX_DAO_LINK[id="1" output="link" class="btn"]' => '<a class="btn" href="$URL$">$LABEL$</a>',
];
$varModuleOutput = '<table class="table">';
foreach ($output as $th => $td) {
    $varModuleOutput .= '<tr><th><code>' . $th . '</code></th><td><code>' . htmlspecialchars($td) . '</code></td></tr>';
}
$varModuleOutput .= '</table>';

$content = '
<table class="table">
    <thead>
        <tr>
            <th>Dao Var</th>
            <th>' . rex_i18n::msg('module') . ' ' . rex_i18n::msg('input') . '</th>
            <th>' . rex_i18n::msg('module') . ' ' . rex_i18n::msg('output') . '</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>' . $var . '</th>
            <td>' . $varModuleInput . '</td>
            <td>' . $varModuleOutput . '</td>
        </tr>
    </tbody>
</table>
';
$string = 'REX_DAO_LINK[]
REX_DAO_LINK[id="1" widget="1"]
REX_DAO_LINK[id="1"]    {"label":"Label","value":"Url/Id","name":"Articlename"}
REX_DAO_LINK[id="1" output="url"]
REX_DAO_LINK[id="1" output="link"]
REX_DAO_LINK[id="1" output="link" class="btn"]
';
$code = '';
$code .= rex_string::highlight($string);

$fragment = new \rex_fragment();
$fragment->setVar('title', $this->i18n('title'), false);
$fragment->setVar('content', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;
