<?php
namespace app\helpers;

use app\models\Gallery;
use yii\helpers\Url;
use yii\helpers\Html;

class MenuHelper {
    
	public static function getGalleryContent($parent_id = 0) {
        /** @var $list Gallery[] */
        $list = Gallery::find()->byCurrentProject()->byParent($parent_id)->managed()->ordering()->all();
        if (!$list) return '';
        
        $result = '';
        foreach ($list AS $item) {
    
            $class = 'active-' . $item->status;
            $manage_class = 'manage-menu-recursive';
            
            if ($parent_id == 0) {
                $class .= ' strong';
                $manage_class .= ' m-b-5';
            }
            
            $result .= '<ul class="' . $manage_class . '"><li>';
                $result .= '<span class="menu-item">';
                    $result .= '<a class="btn btn-danger btn-xs btn-act" href="' . Url::to(['delete', 'id' => $item->id]) . '" title="Удалить"><i class="glyphicon glyphicon-trash"></i></a>';
                    $result .= '<a class="btn btn-default btn-xs btn-act-up" href="' . Url::to(['up', 'id' => $item->id]) . '" title="Переместить вверх"><i class="glyphicon glyphicon-chevron-up"></i></a>';
                    $result .= '<a class="btn btn-default btn-xs btn-act-down" href="' . Url::to(['down', 'id' => $item->id]) . '" title="Переместить вниз"><i class="glyphicon glyphicon-chevron-down"></i></a>';
                    $result .= '<a class="btn btn-default btn-xs" title="Скрыть" href="' . Url::to(['hide', 'id' => $item->id]) . '"><i class="glyphicon glyphicon-eye-close"></i></a>';
                    $result .= '<a class="btn btn-default btn-xs" title="Опубликовать" href="' . Url::to(['show', 'id' => $item->id]) . '"><i class="glyphicon glyphicon-eye-open"></i></a>';
                    $result .= '<a class="btn btn-warning btn-xs btn-act" title="Управление картинками" href="' . Url::to(['photos', 'id' => $item->id]) . '"><i class="glyphicon glyphicon-cog"></i></a>';
                    $result .= '<a class="btn btn-info btn-xs btn-act" title="Редактировать" href="' . Url::to(['edit', 'id' => $item->id]) . '"><i class="glyphicon glyphicon-pencil"></i></a>';
                    $result .= Html::a('<i class="glyphicon glyphicon-plus"></i>', ['add', 'id' => $item->id], ['class' => 'btn btn-success btn-xs btn-act', 'title' => 'Добавить подпункт меню']);
                    $result .= Html::tag('span', $item->name, ['class' => $class]);
                $result .= '</span>';
                
                if ($item->childs) $result .= self::getGalleryContent($item->id);
            
            $result .= '</li></ul>';
        }
        
        return $result;
    }
}