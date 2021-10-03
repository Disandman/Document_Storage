<?php

namespace app\modules\api\v1\models;

use yii\data\ActiveDataProvider;


class UploadModelSearch extends UploadModel
{

    public function search(array $params): ActiveDataProvider
    {
        $requestParams = \Yii::$app->getRequest()->getBodyParams(); // Собираем параметры запроса из тела
        if (empty($requestParams)) {
            $requestParams = \Yii::$app->getRequest()->getQueryParams(); // Если они пусты, мы берем их из URL
        }

        $dataFilter = new \yii\data\ActiveDataFilter([
            'searchModel' => UploadModel::className() // Готовим ActiveDataFilter, как упоминалось выше, с искомой моделью, являющейся UploadModel
        ]);
        if ($dataFilter->load($requestParams)) {
            $filter = $dataFilter->build(); // Объект ActiveDataFilter строится с использованием собранных параметров
            if ($filter === false) { // Если во время процесса сборки возвращается false это, означает, что есть ошибка (обычно неудачная проверка), поэтому мы возвращаем объект пользователю, чтобы увидеть список ошибок
                return $dataFilter;
            }
        }

        $query = UploadModel::find()
            ->where([
                'OR',
                ['=', 'type', 0],
                ['=', 'type', 1],
            ]);

        if (!empty($filter)) {
            $query->andWhere($filter); // Если фильтр не пустой, мы применяем его к запросу базы данных для UploadModel
        }

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]); // Настраиваем объект ActiveDataProvider для возврата отфильтрованной (с разбивкой на страницы и сортировкой, если применимо)

    }
}
