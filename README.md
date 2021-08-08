## Ревью от 08.08.2021

1. Немного про explode (я уже им замучила, наверное)

```php
    if ($this->date != '') {
        $date = explode(' - ', $this->date);

        if (empty ($date)) { 
            $query->andWhere(['>=', 'date', date('Y-m-d', strtotime($date[0]))]);
            $query->andWhere(['<=', 'date', date('Y-m-d', strtotime($date[1]))]);
        } else {
            $query->andWhere('date >= DATE_SUB(CURRENT_DATE, INTERVAL 5 YEAR)');
        }
    }
```

Согласно [документации](https://www.php.net/manual/ru/function.explode.php):

```
Если separator является пустой строкой (""), explode() возвращает false. ...
```

Поэтому проверка `if (empty ($date))` излишняя. Другое дело, что если в `$this->date` окажется строка без указанного разделителя, то код выше вызовет ошибку, т.к. 
`$date[1]` не будет существовать. [Пример в песочнице](http://sandbox.onlinephpfunctions.com/code/103f9f86b0feb13c54f4ce172abbea77cefc1bf6). 

Я ожидала примерно такую проверку:

```php
    if ($this->date != '') {
        $date = explode(' - ', $this->date);

        $query->andWhere(['>=', 'date', date('Y-m-d', strtotime($date[0]))]);

        if(isset($date[1])) {
            $query->andWhere(['<=', 'date', date('Y-m-d', strtotime($date[1]))]);
        }
    }
```

Можно даже пойти ещё дальше и сделать в rules своё правило для поля date:

```php

// ...

public function rules()
{
    return [
        // ...

        // Свой валидатор даты, название должно совпадать с именем функции
        ['date', 'validateDate'],
    ];
}

// ...

// Функция-валидатор даты
public function validateDate($attribute, $param)
{
    if(!empty($this->$attribute)) { // проверка на заполнение. Если не заполняли, считаем, что всё ок

        $date = explode(' - ', $this->$attribute); // разбиваем содержимое атрибута
        
        if(!isset($date[0]) || !isset($date[1]) { //проверяем, что интервал передан корректно
            $this->addError($attribute, 'Неверный формат интервала дат'); // Если некорректно - добавляем ошибку
        }
    }
}

// ...

// и дальше уже можно не проверять на isset, потому что ранее мы вызвали функцию validate, которая с помощью 
// определенного нами валидатора уже проверила данные
    if ($this->date != '') {
        $date = explode(' - ', $this->date);

        $query->andWhere(['>=', 'date', date('Y-m-d', strtotime($date[0]))]);
        $query->andWhere(['<=', 'date', date('Y-m-d', strtotime($date[1]))]);
    }


```

2. Фильтры доступа `as access` 

Это не является ошибкой, я просто полазила по исходникам `AccessRule` от `dektrium` и, как я поняла, он использует принцип "черного листа", т.е. если в правилах не запрещено, то разрешено.

Не самый удобный подход, потому что, н-р, пришлось мало того, что указывать `as access` в web.php, так еще и в контроллерах в `behaiors` явно
указывать, что смотреть можно только авторизованным пользователям. Но раз уж библиотека так себя ведёт, пусть будет так.


3. Тесты

В процессе проверки...