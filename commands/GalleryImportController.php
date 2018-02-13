<?php
namespace app\commands;

use app\models\Gallery;
use app\models\Projects;
use Yii;
use yii\console\Controller;
use app\models\Pages;
use yii\helpers\Json;

/**
 * @package app\commands
 */
class GalleryImportController extends Controller
{
    public function actionInit()
    {
        /** @var $gallery Gallery[] */
        $gallery = Gallery::find()->all();
        foreach ($gallery AS $g) {
            
            $db = mysqli_connect('localhost', 'dp', 'n3N6zTGy', 'dp');
            mysqli_set_charset($db, 'utf8');
            
            $result = mysqli_query($db, "SELECT * FROM dp_slideshow_photos WHERE id_show = {$g->id} ORDER BY ordering ASC");
            if (mysqli_num_rows($result)) {
                
//                $images = [
//                    'f' => [],
//                    't' => [],
//                    'a' => [],
//                ];
                
                while ($row = mysqli_fetch_assoc($result)) {
//                    $images['f'][] = $row['img_name'] . '.jpg';
//                    $images['t'][] = $row['title'];
//                    $images['a'][] = !empty($row['img_alt']) ? $row['img_alt'] : $row['title'];
                    
                    foreach (['_sm', '_xs'] AS $w) {
                        copy(
                            '/var/www/dendude/data/www/dom-prestarelyh-ramenskoe.ru/images/pages/' . $row['img_name'] . '_m200.jpg',
                            '/var/www/dendude/data/www/dom-prestarelyh-ramenskoe.ru/images/gallery/' . $row['img_name'] . $w . '.jpg'
                        );
                    }
                }
    
//                $g->updateAttributes([
//                    'images' => Json::encode($images)
//                ]);
            }
        }
    }
}