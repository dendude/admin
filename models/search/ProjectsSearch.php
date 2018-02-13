<?php
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projects;

class ProjectsSearch extends Projects {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'ordering', 'status', 'created', 'modified'], 'integer'],
            [['site_name', 'site_url'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = self::find()->managed();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
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
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['status' => $this->status]);
        
        $query->andFilterWhere(['like', 'site_name', $this->site_name]);
        $query->andFilterWhere(['like', 'site_url', $this->site_url]);

        return $dataProvider;
    }
}