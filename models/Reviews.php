<?php

namespace app\models;

use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use Yii;
use yii\db\ActiveRecord;
use app\helpers\Normalize;
use app\models\queries\ReviewsQuery;

/**
 * This is the model class for table "{{%reviews}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $project_id
 * @property string $img_logo
 * @property string $img_base
 * @property string $img_name
 * @property string $user_name
 * @property string $user_email
 * @property string $user_review
 * @property string $manager_answer
 * @property string $meta_k
 * @property integer $status
 * @property integer $created
 * @property integer $modified
 * @property integer $ordering
 * @property string $meta_d
 * @property string $meta_t
 * @property string $bread_name
 *
 * @property Users $manager
 */
class Reviews extends ActiveRecord
{
    use GetManagerTrait;
    use BeforeValidateTrait;
    
    public $send_answer = false;
    
    public static function tableName()
    {
        return '{{%reviews}}';
    }
    
    public static function getImagesIcons()
    {
        return [
            'Grandfаther.svg',
            'Grandmother.svg',
            'Family-grandfather.svg',
            'Family-grandmother.svg',
            'Family.svg',
        ];
    }

    public function rules()
    {
        return [
            [['project_id', 'created', 'user_name', 'user_email', 'user_review'], 'required'],
            
            ['user_email', 'email'],
            
            [['manager_id', 'project_id', 'status', 'created', 'modified', 'ordering'], 'integer'],
            [['manager_id', 'project_id', 'status', 'created', 'modified', 'ordering'], 'default', 'value' => 0],
            
            [['img_logo', 'img_base', 'img_name', 'user_name', 'user_email', 'bread_name'], 'string', 'max' => 200],
            
            [['meta_k', 'meta_d', 'meta_t'], 'string', 'max' => 250],
            [['user_review', 'manager_answer'], 'string'],
            
            ['send_answer', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'img_logo' => 'Логотип',
            'img_base' => 'Фото-иконка',
            'img_name' => 'Фото отзыва',
            'user_name' => 'Имя автора',
            'user_email' => 'Email автора',
            'user_review' => 'Текст отзыва',
            'manager_answer' => 'Ответ на отзыв',
            'bread_name' => 'Текст хлебной крошки',
            'send_answer' => 'Отправить ответ автору'
        ]);
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        
        foreach (['img_name', 'img_logo'] AS $field_name) {
            if ($this->{$field_name}) { // убираем полный путь к фото
                $this->{$field_name} = array_pop(explode('/', $this->{$field_name}));
            }
        }
        
        return parent::beforeValidate();
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        $is_new = $this->isNewRecord;
        $saved = parent::save($runValidation, $attributeNames);
        
        if ($saved) {
            $type_id = $is_new ? 5 : 6;
            Actions::add($type_id, [
                'id' => $this->id,
                'user_name' => $this->user_name,
                'user_email' => $this->user_email,
                'user_review' => $this->user_review,
            ]);
        }
        
        return $saved;
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->send_answer && $this->manager_answer) {
            // @todo отправка ответа автору отзыва
        }
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public static function find()
    {
        return new ReviewsQuery(get_called_class());
    }
}
