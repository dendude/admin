<?php
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\helpers\Normalize;
use app\models\Users;

class UsersSearch extends Users {

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['role', 'first_name', 'last_name', 'sur_name', 'email', 'phone'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = self::find()->managed()->byProject(PROJECT_ID);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        // load the seach form data and validate
        if (!$this->load($params)) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['role' => $this->role]);
        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['like', 'CONCAT(last_name,first_name,sur_name)', $this->first_name]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'phone', Normalize::clearPhone($this->phone)]);

        return $dataProvider;
    }
}