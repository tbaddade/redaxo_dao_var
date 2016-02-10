<?php

/**
 * This file is part of the Dao Var package.
 *
 * @author (c) Thomas Blum <thomas@addoff.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$vars = [
    [
        'var' => 'REX_DAO_LINK[]',
        'inp' => ['REX_DAO_LINK[id="1" widget="1"]'],
        'out' => [
            'REX_DAO_LINK[id="1"]' => '{"label":"$LABEL$","value":"$URL_ID$","name":"$ARTICLE_NAME$"}',
            'REX_DAO_LINK[id="1" output="url"]' => '$URL$',
            'REX_DAO_LINK[id="1" output="link"]' => '<a href="$URL$">$LABEL$</a>',
            'REX_DAO_LINK[id="1" output="link" class="btn"]' => '<a class="btn" href="$URL$">$LABEL$</a>',
        ]
    ], [
        'var' => 'REX_DAO_SELECT[]',
        'inp' => [
            'REX_DAO_SELECT[id="1" widget="1" options="Please select,H1=h1,H2=h2"]',
            'REX_DAO_SELECT[id="1" widget="1" options="Please select|optGroup A:H1=h1,H2=h2|optGroup B:H3=h3,H4=h4"]'],
        'out' => ['REX_DAO_SELECT[id="1"]',],
    ], [
        'var' => 'REX_DAO_CATEGORY_SELECT[]',
        'inp' => [
            'REX_DAO_CATEGORY_SELECT[id="1" widget="1"]',
            'REX_DAO_CATEGORY_SELECT[id="1" widget="1" root="5"]'],
        'out' => ['REX_DAO_CATEGORY_SELECT[id="1"]',],
    ]
];

$rows = '';
foreach ($vars as $var) {
    $rows .= '<tr>';
    $rows .= '<th><code>' . $var['var'] . '</code></th>';

    $rows .= '<td>';

    $rows .= '<dl class="dl-horizontal text-left"><dt>Eingabe:</dt>';
    foreach ($var['inp'] as $value) {
        $rows .= '<dd><p><b><code>' . htmlspecialchars($value) . '</code></b></p></dd>';
    }
    $rows .= '</dl>';

    $rows .= '<dl class="dl-horizontal text-left"><dt>Ausgabe:</dt>';
    foreach ($var['out'] as $key => $value) {
        if (is_int($key)) {
            $rows .= '<dd><b><code>' . htmlspecialchars($value) . '</code></b></dd>';
        } else {
            $rows .= '<dd><p><b><code>' . htmlspecialchars($key) . '</code></b><br /><i class="fa fa-hand-o-right"></i>&nbsp;&nbsp;<code>' . htmlspecialchars($value) . '</code></p></dd>';
        }
    }
    $rows .= '</dl>';

    $rows .= '</td>';
    $rows .= '</tr>';

}

$content = '
<table class="table">
    <thead>
        <tr>
            <th>Dao Var</th>
            <th>' . rex_i18n::msg('module') . '</th>
        </tr>
    </thead>
    <tbody>
        ' . $rows . '
    </tbody>
</table>
';

$fragment = new \rex_fragment();
$fragment->setVar('title', $this->i18n('title'), false);
$fragment->setVar('content', $content, false);
$content = $fragment->parse('core/page/section.php');

echo $content;
