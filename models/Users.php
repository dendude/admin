<?php

namespace app\models;

use app\helpers\Normalize;
use app\models\queries\UsersQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $project_id
 * @property string $role
 * @property string $photo
 * @property string $first_name
 * @property string $last_name
 * @property string $sur_name
 * @property string $email
 * @property string $pass
 * @property string $phone
 * @property string $contacts
 * @property string $projects
 * @property string $confirm_code
 * @property string $restore_code
 * @property integer $created
 * @property integer $modified
 * @property integer $last_action
 * @property integer $status
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public $authKey;
    public $username;
    
    public $orig_pass;
    public $projects_arr = [];
    
    public static $profile;
    
    const ROLE_USER = 'user';
    const ROLE_PARTNER = 'partner';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';
    
    public static function getRoles() {
        return [
//            self::ROLE_USER => 'Пользователь',
//            self::ROLE_PARTNER => 'Партнер',
            self::ROLE_MANAGER => 'Менеджер',
            self::ROLE_ADMIN => 'Администратор',
        ];
    }
    
    public function getRoleName() {
        $list = self::getRoles();
        return isset($list[$this->role]) ? $list[$this->role] : 'not found';
    }
    
    public static function getManagersFilter() {
        $list = self::find()->managed()->andWhere([
            'role' => [self::ROLE_MANAGER, self::ROLE_ADMIN]
        ])->orderBy('role ASC, first_name ASC, last_name ASC')->all();
        
        return $list ? ArrayHelper::map($list, 'id', function (self $model){
            return "{$model->first_name} {$model->last_name} [{$model->getRoleName()}]";
        }) : [];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role', 'created', 'manager_id', 'project_id', 'first_name', 'email', 'pass'], 'required'],

            [['pass', 'orig_pass'], 'required', 'when' => function(self $model){
                return $model->isNewRecord;
            }, 'whenClient' => "function(attr, value){
                return " . ($this->isNewRecord ? 'true' : 'false') . ";
            }"],
            
            [['created', 'modified', 'last_action', 'status', 'manager_id', 'project_id'], 'integer'],
            [['created', 'modified', 'last_action', 'status', 'manager_id', 'project_id'], 'default', 'value' => 0],
            [['photo', 'first_name', 'last_name', 'sur_name', 'email', 'pass', 'phone', 'confirm_code', 'restore_code'], 'string', 'max' => 100],
            [['contacts', 'projects'], 'string'],
            
            ['email', 'email'],
            ['phone', 'string', 'min' => 10],
            ['role', 'in', 'range' => array_keys(self::getRoles())],
            
            ['projects', 'string'],
            ['projects_arr', 'each', 'rule' => ['integer']],
        ];
    }
    
    public function getRandomCode() {
        return preg_replace('/[^a-z0-9]/i', '', Yii::$app->security->generateRandomString());
    }
    
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->confirm_code = $this->getRandomCode();
        } else {
            $this->modified = time();
            if (!$this->created) $this->created = time();
        }
        
        $this->phone = Normalize::clearPhone($this->phone);
        $this->project_id = PROJECT_ID;
            
        if ($this->orig_pass) {
            // обновление пароля
            $this->pass = Yii::$app->security->generatePasswordHash($this->orig_pass);
        }
            
        if (Yii::$app->user->can(Users::ROLE_ADMIN)) {
            // админ работает с пользователем
            $this->manager_id = Yii::$app->user->id;
        
            if (is_array($this->projects_arr) && count($this->projects_arr)) {
                $this->projects = Json::encode($this->projects_arr);
            }
        }
        
        return parent::beforeValidate();
    }
    
    public function afterFind() {
        parent::afterFind();
        
        if ($this->projects) $this->projects_arr = Json::decode($this->projects, true);
    }
    
    public function getFullName() {
        return "{$this->first_name} {$this->last_name}";
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'role' => 'Роль',
            'photo' => 'Фото',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'sur_name' => 'Отчество',
            'email' => 'Email',
            'pass' => 'Пароль',
            'orig_pass' => 'Пароль',
            'phone' => 'Телефон',
            'contacts' => 'Контакты',
            'projects' => 'Проекты',
            'projects_arr' => 'Доступ к проектам',
            'confirm_code' => 'Confirm Code',
            'restore_code' => 'Restore Code',
            'last_action' => 'Активность',
        ]);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
    
    // profiles
    public static function selfProfile() {
        return self::findIdentity(Yii::$app->user->id);
    }
    public function validatePassword($password)    {
        return Yii::$app->security->validatePassword($password, $this->pass);
    }
    
    // identity methods
    public static function findIdentity($id)
    {
        if (!self::$profile) self::$profile = static::findOne($id);
        
        return self::$profile;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->authKey;
    }
    public function validateAuthKey($authKey)
    {
        return ($this->authKey === $authKey);
    }
}
