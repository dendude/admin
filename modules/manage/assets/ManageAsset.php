<?php

namespace app\modules\manage\assets;

use yii\web\AssetBundle;

class ManageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        '/lib/jquery-colorbox/example3/colorbox.css',
        
        'css/base.css?4',
        'css/manage.css?4',
    ];
    public $js = [
        '/lib/jquery-colorbox/jquery.colorbox.js',
        '/lib/jquery-colorbox/i18n/jquery.colorbox-ru.js',
        
        'js/site.js?7',
        'js/manage.js?7',
    ];

    public $depends = [
        'app\modules\manage\assets\LTEAsset',
        'yii\web\YiiAsset',
    ];
}