<?php

namespace app\modules\manage\assets;

use yii\web\AssetBundle;

class DropZoneAsset extends AssetBundle
{
    public $basePath = '@bower/dropzone/dist';
    public $baseUrl = '/lib/dropzone/dist';

    public $css = [
        'basic.css',
        'dropzone.css',
    ];
    public $js = [
        'dropzone.js',
    ];

    public $depends = [
        'app\modules\manage\assets\ManageAsset',
    ];
}