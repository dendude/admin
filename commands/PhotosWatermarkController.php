<?php
namespace app\commands;

use app\models\forms\UploadForm;
use app\models\Projects;
use Yii;
use yii\console\Controller;

/**
 * водяные знаки на фото
 * @package app\commands
 */
class PhotosWatermarkController extends Controller
{
    public function actionInit()
    {
//        foreach ([UploadForm::TYPE_PAGES, UploadForm::TYPE_GALLERY, UploadForm::TYPE_NEWS, UploadForm::TYPE_ACTIONS, ''] AS $type) {
//
//            $dir_path = Yii::getAlias('@app') . "/../xn----7sbbhp2bgisp.xn--p1ai/web/images/{$type}";
//
//            if (!file_exists($dir_path)) continue;
//
//            $dir = new \DirectoryIterator($dir_path);
//
//            while ($dir->valid()) {
//                if ($dir->isDot() || $dir->isDir() || $dir->getMTime() > (time() - 7200)) {
//                    $dir->next();
//                    continue;
//                }
//
//                if (strpos($dir->getFilename(), '.') === 0) {
//                    $dir->next();
//                    continue;
//                }
//
//                $file_path = $dir->getPath() . '/' . $dir->getFilename();
//
//                echo $file_path . PHP_EOL;
//
//                $project = Projects::findOne(Projects::MAKEBODY_ID);
//
//                // подпись-маркер
//                $uf = new UploadForm(['project_id' => Projects::MAKEBODY_ID]);
//                $uf->setTextMark($project->mark_text, $file_path);
//
//                $dir->next();
//            }
//        }
    }
}