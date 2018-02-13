<?php

namespace app\models\forms;

use app\helpers\Statuses;
use app\models\Users;
use Yii;
use yii\base\Model;

/**
 * Форма входа
 *
 *
 * @property string $email
 * @property string $pass
 * @property Users|null $_user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $email;
    public $pass;

    private $_user = false;

    public function rules()
    {
        return [
            [['email', 'pass'], 'required'],
            
            ['email', 'email'],
            ['pass', 'string', 'min' => Yii::$app->params['pass_min_length']],
            
            ['pass', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->pass)) {
                $this->addError($attribute, 'Пользователь не найден');
            } elseif ($user->status == Statuses::STATUS_DISABLED) {
                $this->addError($attribute, 'Учетная запись не активирована');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 3600*24*30);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::find()->where([
                'project_id' => PROJECT_ID,
                'email' => $this->email
            ])->one();
        }

        return $this->_user;
    }
    
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'pass' => 'Пароль',
        ];
    }
}
