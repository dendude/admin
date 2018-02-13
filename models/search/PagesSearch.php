<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pages;

class PagesSearch extends Pages {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'is_sitemap', 'is_shared'], 'integer'],
            [['alias', 'title', 'meta_t', 'meta_k', 'meta_d'], 'string'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Pages::find()->managed()->byCurrentProject();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'modified' => SORT_DESC,
                    'created' => SORT_DESC,
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
        $query->andFilterWhere(['is_sitemap' => $this->is_sitemap]);

        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}