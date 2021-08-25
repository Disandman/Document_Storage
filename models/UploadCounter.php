<?php

namespace app\models;

/**
 * Модель-счетчик для вывода количества документов во вью
 *
 * @property int $countPublic Количество публичных документов
 * @property int $countPrivate Количество приватных документов
 * @property int $countProtected Количество условно-приватных документов
 */
class UploadCounter extends \yii\base\Model
{
    public $countPublic = 0;
    public $countPrivate = 0;
    public $countProtected = 0;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['countPublic', 'countPrivate', 'countProtected'], 'integer', 'min' => 0],
            [['countPublic', 'countPrivate', 'countProtected'], 'default', 'value' => 0],
        ];
    }

    /**
     * Общее число документов
     *
     * @return int
     */
    public function getCountTotal(): int
    {
        return $this->countPublic +
            $this->countProtected +
            $this->countPrivate;
    }

    /**
     * Процент публичных документов от всего числа документов
     *
     * @return float
     */
    public function getPercentPublic()
    {
        return $this->countPublic / $this->getCountTotal() * 100;
    }

    public function getPercentProtected()
    {
        return $this->countProtected / $this->getCountTotal() * 100;
    }

    public function getPercentPrivate()
    {
        return $this->countPrivate / $this->getCountTotal() * 100;
    }

}