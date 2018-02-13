<?php
/**
 * Created by PhpStorm.
 * User: dendude
 * Date: 30.07.16
 * Time: 0:50
 */
namespace app\modules\manage\widgets;

use yii\base\Widget;

class FroalaEditorWidget extends Widget {
    
    public $model;
    public $field;
        
    public $imageUploadUrl;
    public $filesUploadUrl;
    public $filesManagerUrl;
    
    public function run() {
        parent::run();
        
        return $this->render('FroalaEditorWidget', [
            'model' => $this->model,
            'field' => $this->field,
            
            'imageUploadUrl' => $this->imageUploadUrl,
            'filesUploadUrl' => $this->filesUploadUrl,
            'filesManagerUrl' => $this->filesManagerUrl,
        ]);
    }
}