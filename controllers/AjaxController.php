<?php
/**
 * Created by PhpStorm.
 * User: dendude
 * Date: 09.03.15
 * Time: 21:55
 */

namespace app\controllers;

use app\helpers\Normalize;
use app\models\Ads;
use app\models\Base;
use app\models\BaseItems;
use app\models\Fields;
use app\models\forms\CampAbout;
use app\models\forms\CampClient;
use app\models\forms\CampContacts;
use app\models\forms\CampContract;
use app\models\forms\CampMedia;
use app\models\forms\CampOpts;
use app\models\forms\CampPlacement;
use app\models\forms\UploadForm;
use app\models\LocCities;
use app\models\LocRegions;
use app\models\Orders;
use app\models\UsersRecommends;
use app\models\UsersSubscribes;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class AjaxController extends Controller
{
    
    public function beforeAction($action) {
        if (!Yii::$app->request->isAjax) die;
        
        return parent::beforeAction($action);
    }
    
    public function actionAlias() {
        $str = Yii::$app->request->post('str', '');
        echo Normalize::alias($str);
    }
    
    public function actionUpload() {
        $upload_form = new UploadForm();
        $upload_form->setScenario(UploadForm::SCENARIO_CAMP);
        $upload_form->imageFile = UploadedFile::getInstances($upload_form, 'imageFile');
        
        if ($upload_form->upload(UploadForm::TYPE_CAMP)) {
            echo Json::encode([
                'file_name' => $upload_form->getImageName(),
            ]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
    
    public function actionOptions() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ['content' => ''];
        
        $id = Yii::$app->request->post('id', 0);
        
        switch (Yii::$app->request->post('type')) {
            case 'regions':
                $first = Yii::$app->request->post('first', '- Выбор региона -');
                $empty = Yii::$app->request->post('empty', '- Регионы не найдены -');
                
                $regions = LocRegions::getFilterList($id);
                if ($regions) {
                    $result['content'] .= Html::tag('option', $first, ['value' => '']);
                    foreach ($regions AS $k => $v) {
                        $result['content'] .= Html::tag('option', $v, ['value' => $k]);
                    }
                } else {
                    $result['content'] .= Html::tag('option', $empty, ['value' => '']);
                }
                break;
        }
        
        return $result;
    }
} 
