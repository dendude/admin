<?php

namespace app\modules\manage\assets;

use yii\web\AssetBundle;

class ManageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        '/lib/jquery-colorbox/example3/colorbox.css',
        
        'css/base.css?2',
        'css/manage.css?2',
    ];
    public $js = [
        '/lib/jquery-colorbox/jquery.colorbox.js',
        '/lib/jquery-colorbox/i18n/jquery.colorbox-ru.js',
        
        'js/site.js?3',
        'js/manage.js?3',
    ];

    public $depends = [
        'app\modules\manage\assets\LTEAsset',
        'yii\web\YiiAsset',
    ];
}