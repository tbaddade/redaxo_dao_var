<?php

$requestIndex = rex_request('index', 'string', '');

$sidebar = '';
$content = '';

$navigation = [
    '' => 'Allgemein',
    'use' => 'Benutzung',
];

$nav = [];
foreach ($navigation as $index => $label) {
    $navAttributes = [
        'href' => rex_url::currentBackendPage(['index' => $index]),
    ];
    if ($index == $requestIndex) {
        $navAttributes['class'][] = 'active';
    }
    if (strpos($index, '/') !== false) {
        $navAttributes['class'][] = 'is-plugin';
    }
    $nav[] = '<a' . rex_string::buildAttributes($navAttributes) . '>' . $label . '</a>';
}

$fragment = new rex_fragment();
$fragment->setVar('title', rex_i18n::msg('watson_cheatsheet_docs_title'));
$fragment->setVar('body', '<nav class="cheatsheet-docs-navigation"><ul><li>' . implode('</li><li>', $nav) . '</li></ul></nav>', false);
$sidebar = $fragment->parse('core/page/section.php');



if ($requestIndex == 'use') {

} else {
    $body = \rex_markdown::factory()->parse(\rex_file::get(\rex_path::addon('dao_var', 'README.md')));
    $body = str_replace(['<pre>', '<code>'], ['<pre class="language-php">', '<code class="language-php">'], $body);

    $fragment = new rex_fragment();
    $fragment->setVar('title', $navigation[$requestIndex]);
    $fragment->setVar('body', $body, false);
    $content .= $fragment->parse('core/page/section.php');

}

echo '
<section class="cheatsheet-docs">
    <div class="cheatsheet-docs-sidebar">' . $sidebar . '</div>
    <div class="cheatsheet-docs-content">' . $content . '</div>
</section>';
