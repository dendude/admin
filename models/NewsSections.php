<?php
namespace app\models;

use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use yii\db\ActiveRecord;
use app\helpers\Normalize;
use app\models\queries\NewsSectionsQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%news_sections}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $manager_id
 * @property string $photo
 * @property string $title
 * @property string $alias
 * @property string $meta_t
 * @property string $meta_d
 * @property string $meta_k
 * @property integer $created
 * @property integer $modified
 * @property integer $ordering
 * @property integer $status
 *
 * @property Users $manager
 */
class NewsSections extends ActiveRecord
{
    use GetManagerTrait;
    use BeforeValidateTrait;
    
    public static function tableName()
    {
        return '{{%news_sections}}';
    }

    public function rules()
    {
        return [
            [['title', 'alias', 'project_id', 'manager_id', 'created'], 'required'],

            // уникальность по алиасу и проекту
            ['alias', 'unique', 'targetAttribute' => ['alias', 'project_id']],
            
            [['project_id', 'manager_id', 'created', 'modified', 'ordering', 'status'], 'integer'],
            [['project_id', 'manager_id', 'created', 'modified', 'ordering', 'status'], 'default', 'value' => 0],

            [['title', 'alias'], 'string', 'max' => 100],
            [['photo', 'meta_t', 'meta_d', 'meta_k'], 'string', 'max' => 250],
        ];
    }
    
    public static function getFilterList()
    {
        $list = self::find()->managed()->byCurrentProject()->ordering()->all();
        return $list ? ArrayHelper::map($list, 'id', 'title') : [];
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        $this->photo = array_pop(explode('/', $this->photo));
        
        return parent::beforeValidate();
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'title' => 'Название',
        ]);
    }

    public static function find()
    {
        return new NewsSectionsQuery(get_called_class());
    }
}
