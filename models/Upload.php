<?php

namespace app\models;

/**
 * This is the model class for table "upload".
 *
 * @property int $id
 * @property int $user_id
 * @property int $role
 * @property string|null $name
 * @property string|null $size
 * @property int|null $type
 * @property string|null $date
 */
class Upload extends \yii\db\ActiveRecord
{
    const TYPE_PUBLIC = 0;
    const TYPE_CONDITIONALLY_PRIVATE = 1;
    const TYPE_PRIVATE = 2;

    const INTERVAL_DAY = '1 DAY';
    const INTERVAL_MONTH = '1 MONTH';
    const INTERVAL_YEAR = '1 YEAR';
    const INTERVAL_YEAR_5 = '5 YEAR';

    public static $typeNames = [
        self::TYPE_PUBLIC => 'Публичный',
        self::TYPE_CONDITIONALLY_PRIVATE => 'Условно-приватный',
        self::TYPE_PRIVATE => 'Приватный',
    ];

    public static $intervalNames = [
        self::INTERVAL_DAY => 'День',
        self::INTERVAL_MONTH => 'Месяц',
        self::INTERVAL_YEAR => 'Год',
        self::INTERVAL_YEAR_5 => '5 лет',
    ];

    public static function tableName()
    {
        return 'upload';
    }

    public $file;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'user_id'], 'integer'],
            [['name', 'size', 'date'], 'string'],
            [['file'], 'file', 'maxFiles' => 20, 'extensions' => 'docx, doc, pdf, xls, odt, ods, odp, rtf', 'maxSize' => '20000000']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'public' => 'Публичные',
            'user_id' => 'Владелец:',
            'file' => '',
            'name' => 'Имя файла',
            'size' => 'Размер',
            'type' => 'Тип документа',
            'date' => 'Дата создания файла',
            'role' => 'Роль пользователя',

        ];
    }

//////////////////////////////////////////////////Связь с USER//////////////////////////////////////////////
    public function getUploadUsers()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'user_id']);
    }

////////////////////////////////////////////////Выборка за интервалы времени////////////////////////////////
    public static function countFilesByPeriodAndType($interval, $type): int
    {
        // Сумму можно считать сразу со стороны БД с помощью функции COUNT()
        $query = Upload::find()
            ->select('COUNT(`id`)')
            ->where(['type' => $type]);

        // Тут по $interval определяем интервал выборки
        $result = $query->andWhere('date >= DATE_SUB(CURRENT_DATE, INTERVAL ' . $interval . ')')->scalar();
        return empty($result) ? 0 : intval($result);
    }
    /**
     * Возвращает путь к файлу
     *
     * @param string $name
     * @return string
     * */
    public static function getPathToFile(string $name): string {
        return $_ENV['DOWNLOAD_PATH'] . $name;
    }
}