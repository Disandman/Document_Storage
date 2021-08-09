<?php

use yii\helpers\Url;

class ChartTestCest
{
    public function checkFilterByMonth(AcceptanceTester $I)
    {
        // По опыту, лучше описывать, что проверяется на каждом шаге текстом.
        // Особенно если в команде есть люди, которые с программированием не связаны напрямую
        // и им нужен просто отчет, чтобы видеть, где сломалось
        $I->comment('Открыть страницу со статистикой загруженных файлов');
        $I->amOnPage('/chart/index-alt');

        // Чуть подождем после перехода на страницу, чтобы всё подгрузилось,
        // потому что обычно система не локально тестируется, а через тестовый сервер
        // => могут быть задержки на загрузку страницы
        $I->wait(3); 

        // Лучше однозначно определять элементы на странице. Если caret будет несколько,
        // может быть неоднозначность результатов тестирования
        $I->comment('Начать выбор интервала выгрузки');
        $I->click("#chartsearch-date-container"); 
        $I->wait(1);

        $I->comment('Выбрать интервал выгрузки за месяц');
        $stringInterval = $this->getMonthRangeString();
        $I->click("//li[contains(.,'За месяц')]");
        $I->wait(5);
        
        // И проверяем, что фильтр применился (в URL будет строка вида http://localhost:8003/chart/index-alt?ChartSearch[date]=2021-07-09+-+2021-08-09)
        $I->comment('Проверить, что фильтр применился');
        $I->seeInCurrentUrl($stringInterval);
    }

    /**
     * Undocumented function
     *
     * @param string $separator Разделитель дат. Необязательный. По умолчанию '+-+'
     * @param string $format Формат даты. Необязательный. По умолчанию 'Y-m-d'
     * @return string
     */
    private function getMonthRangeString($separator = '+-+', $format = 'Y-m-d'): string
    {
        $rightDate = new \DateTime('now');
        $leftDate = new \DateTime('now');

        // Тут можно было бы воспользоваться функцией ->modify('-1 month'), но 
        // с месяцами эта функция может себя не так, как ожидалось

        $leftDateDay = $leftDate->format('d');
        $leftDateMonth = $leftDate->format('m') - 1;
        $leftDateYear = $leftDate->format('Y');

        if($leftDateMonth < 1) {
            $leftDateMonth = 12;
            $leftDateYear--;
        }

        $leftDate->setDate($leftDateYear, $leftDateMonth, $leftDateDay);

        $leftDate = $leftDate->format($format);
        $rightDate = $rightDate->format($format);

        return "{$leftDate}{$separator}{$rightDate}";
    }
}