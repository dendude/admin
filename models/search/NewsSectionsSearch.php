<?php
namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\NewsSections;

class NewsSectionsSearch extends NewsSections {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'ordering', 'status'], 'integer'],
            [['alias', 'title', 'meta_t', 'meta_k', 'meta_d'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = parent::find()->managed()->byCurrentProject();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}