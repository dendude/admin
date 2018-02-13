<?php
/**
 * Created by PhpStorm.
 * User: dendude
 * Date: 30.07.16
 * Time: 0:50
 */
namespace app\modules\manage\widgets;

use app\modules\manage\assets\DropZoneAsset;
use yii\base\Widget;

class DropZoneWidget extends Widget {
    
    public $model;
    public $field;
    
    public $zone_id;
    public $max_files = 1;
    public $url;
    
    public $isDeleteBtn = true;
    
    public function run() {
        parent::run();
        
        DropZoneAsset::register(\Yii::$app->view);
        
        return $this->render('DropZoneWidget', [
            'model' => $this->model,
            'field' => $this->field,
            
            'isDeleteBtn' => $this->isDeleteBtn,
            
            'url' => $this->url,
            'zone_id' => $this->zone_id,
            'max_files' => $this->max_files,
        ]);
    }
}