<?php
// глобальный конфиг
define('CONFIG_DIR', __DIR__ . '/../../configs');
// ид проекта
define('PROJECT_ID', 4);

return array_merge(require(CONFIG_DIR . '/params.php'), [
    'sitename' => 'admin.test3w.ru',
    'pass_min_length' => 8,

    'fieldConfigDefault' => [
        'template' => '<div class="col-xs-12 col-md-4 text-right">{label}</div><div class="col-xs-12 col-md-8">{input}{error}</div>',
        'labelOptions' => ['class' => 'control-label']
    ],
    'fieldConfigAuth' => [
        'template' => '<div class="col-xs-12">{input}{error}</div>',
    ],
]);