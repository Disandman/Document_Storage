<?php

namespace app\models;

use kartik\daterange\DateRangeBehavior;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * UploadSearch represents the model behind the search form of `app\models\Upload`.
 */
class UploadSearch extends Upload
{

    public function validateDate($attribute, $param)
    {
        if(!empty($this->$attribute)) { // проверка на заполнение. Если не заполняли, считаем, что всё ок

            $date = explode(' - ', $this->$attribute); // разбиваем содержимое атрибута

            if(!isset($date[0]) || !isset($date[1])) { //проверяем, что интервал передан корректно
                $this->addError($attribute, 'Неверный формат интервала дат'); // Если некорректно - добавляем ошибку
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type'], 'integer'],
            [['name', 'size', 'user_id', 'date'], 'safe'],
            ['date', 'validateDate'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->can('admin')) {
                $query = Upload::find()
                    ->joinWith('uploadUsers');
            } else {
                $query = Upload::find()
                    ->where(
                        [
                            "or",
                            ['user_id' => \Yii::$app->user->id],
                            ['type' => 0],
                        ]
                    )
                    ->orWhere(['and',
                        ['type' => 1]])
                    ->joinWith('uploadUsers');
            }
        } else {
            $query = Upload::find()
                ->where(['type' => 0])
                ->joinWith('uploadUsers');
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
        ]);

        $query
            ->andFilterWhere(['like', 'upload.name', $this->name])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'user.username', $this->user_id]);

        if ($this->date != '') {
            $date = explode(' - ', $this->date);

            $query->andWhere(['>=', 'date', date('Y-m-d', strtotime($date[0]))]);
            $query->andWhere(['<=', 'date', date('Y-m-d', strtotime($date[1]))]);
        }
        return $dataProvider;
    }
}
