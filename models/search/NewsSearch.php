<?php
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\News;

class NewsSearch extends News {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'ordering', 'section_id', 'is_slider'], 'integer'],
            [['alias', 'title', 'meta_t', 'meta_k', 'meta_d', 'pub_date'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = News::find()->managed()->byCurrentProject();

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

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['section_id' => $this->section_id]);
        $query->andFilterWhere(['is_slider' => $this->is_slider]);
    
        $query->andFilterWhere(['created' => $this->created]);
        $query->andFilterWhere(['modified' => $this->modified]);
        $query->andFilterWhere(['pub_date' => $this->pub_date]);
        $query->andFilterWhere(['ordering' => $this->ordering]);

        $query->andFilterWhere(['like', 'alias', $this->alias]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'about', $this->about]);

        return $dataProvider;
    }
}