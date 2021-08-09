<?php

namespace tests\models;

use app\models\ChartSearch;
use app\models\UploadCounter;
use app\tests\fixtures\UploadFixture;
use app\tests\fixtures\UserFixture;
use Codeception\Test\Unit;

/**
 * Этот тест проверяет корректность работы модели UploadCounter
 */
class UploadCounterTest extends Unit
{
    /**
     * @var UploadCounter $uploadCounter
     */
    private $uploadCounter = null;

    /**
     * @inheritDoc
     */
    protected function _before()
    {
        $this->uploadCounter = new UploadCounter();
    }

    public function _fixtures()
    {
        return [
            'upload' => [
                'class' => UploadFixture::class
            ]
        ];
    }

    public function testPercentPrivate()
    {
        $this->uploadCounter = (new ChartSearch())->searchCounter([]);
        
        // В данном случае лучше вообще не использовать фикстуры, чтобы избежать зависимостей в тестах,
        // а просто задать набор данных прямо здесь, например, так:
        // $this->uploadCounter->countPublic = 2;
        // $this->uploadCounter->countProtected = 2;
        // $this->uploadCounter->countPrivate = 6;

        $this->assertEquals(60.0, $this->uploadCounter->getPercentPrivate(), 'Проверка, что верно вычисляется процент приватных документов');
        
    }

    public function testDivisionByZero()
    {
        $this->uploadCounter->countPublic = 0;
        $this->uploadCounter->countProtected = 0;
        $this->uploadCounter->countPrivate = 0;

        $this->assertIsScalar($this->uploadCounter->getPercentPublic(), 'Проверка, что возвращаемое значение - скалярное');
        $this->assertEquals(0, $this->uploadCounter->getPercentPublic(), 'Проверка, что результат - ноль');
    }
}