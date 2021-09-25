<?php

namespace app\models;

use function GuzzleHttp\Psr7\str; // какой-то аппендикс?

/**
 * This is the model class for table "upload".
 *
 * @property int $id
 * @property int $user_id
 * @property int $role
 * @property string|null $name
 *  * @property string|null $unique_name
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

    public static function tableName(): string
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
            [['name', 'size', 'date', 'unique_name'], 'string'],
            [['file'], 'file', 'maxFiles' => 20, 'extensions' => 'docx, doc, pdf, xls, odt, ods, odp, rtf, txt ', 'maxSize' => '20000000']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
            'unique_name' => 'Уникальное имя'

        ];
    }

    /**
     * Связь с User
     *
     */
    public function getUploadUsers()
    {
        return $this->hasOne(\dektrium\user\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * Выборка за интервал времени
     *
     * @param $interval
     * @param $type
     * @return int
     */
    public static function countFilesByPeriodAndType($interval, $type): int
    {
        $query = Upload::find()
            ->select('COUNT(`id`)')
            ->where(['type' => $type]);

        $result = $query->andWhere('date >= DATE_SUB(CURRENT_DATE, INTERVAL ' . $interval . ')')->scalar();
        return empty($result) ? 0 : intval($result);
    }

    /**
     * Возвращает путь к файлу
     *
     * @param string $name
     * @return string
     * */
    public static function getPathToFile(string $name): string
    {
        return $_ENV['DOWNLOAD_PATH'] . $name;
    }

    /**
     * Возвращает расширение файла
     *
     * @return string
     * */
    public function getExtensionFile(): string
    {
        $name = $this->name;
        $matches = [];
        preg_match('/.*\.(.*)$/iu', $name, $matches);
        $extension = $matches[1] ?? '';
        switch ($extension) {
            case 'docx':
                return '/img/docx.png';
            case 'doc':
                return '/img/doc.png';
            case 'pdf':
                return '/img/pdf.png';
            case 'xls':
                return '/img/xls.png';
            case 'odt':
                return '/img/odt.png';
            case 'ods':
                return '/img/ods.png';
            case 'odp':
                return '/img/odp.png';
            case 'rtf':
                return '/img/rtf.png';
            case 'txt':
                return '/img/txt.png';
        }
    }


    /**
     * Генерация уникального имени
     * @return string
     */
    public function getUniqueName()
    {
        return  uniqid() .'.'. $this->file->getExtension();
    }

    /**
     * Размер файла
     * @return string
     */
    public function getFileSize()
    {
       return number_format($this->file->size / 1048576, 3) . ' ' . 'MB';
    }

}