#Moysklad PHP

**WIP**

Библиотека для сервиса "Мой склад" JSON API 1.1. Некоторые примеры можно найти в "tests". Все ещё далека от завершения.

**Установка:**<br />
    composer require tooyz/moysklad
    
**Запуск тестов:**<br />
    1) composer global require phpunit/phpunit<br />
    2) cd tests<br />
    3) composer update<br />
    4) Отредактировать Config.php <br />
    5) phpunit --configuration="./phpunit.xml" <ИЛИ> npm run test
    

#Класс MoySklad

Используется для авторизации, явно передается параметром в большинство компонентов т.к. возможно использование нескольких аккаунтов моегосклада одновременно.

`$sklad = MoySklad::getInstance($login, $password);`

#Сущности

**Основной объект библиотеки**

```
$product = new Product($sklad, [
    "name" => "Банан"
]);
```

**Можно, например, сделать так**

```
$product = new Product($sklad, [
    "id" => "12345-654321-123456-54321",
    "name" => "Банан"
]);
$product->fresh(Expand::create(['country']));
```

**Или так**

`$product->transformToClass(Counterparty::class);`

**Или не сделать. Большая часть логики делегирована другим классам.**
**Можно прицепить картинки.**
```
$product->attachImage(ImageField::createFromUrl(
    "http://url.ru/img.jpg"
));
```
или
```
$product->attachImage(ImageField::createFromPath(
    "images/123.jpg",
    "renamed_image.jpg"
));
```

#Получение сущностей

**Получение всех сущностей:**

`$list = Product::query($sklad)->getList();`

**Можно добавить параметры запроса. Описание параметров в описании класса QuerySpecs.**

```
$list = Product::query($sklad, QuerySpecs::create([
    "offset" => 15, 
    "maxResults" => 50,
]))->getList();
```

**Фильтрация. Описание методов FilterQuery в комментариях.**

```
$filteredList = Product::query($sklad)->filter(
    (new FilterQuery())
        ->eq("article", 12345)
);
```

**Поиск по строке. К Query-объекту можно прицепить expand для получения связей с указанными названиями**

`$searchedList = Product::query($sklad)->withExpand(Expand::create(['owner']))->search("трусы");`

**Функции выше возвращают объект EntityList.**

**Получение по id.**

`$product = Product::query($sklad)->byId("12345-654321-123456-54321");`

#Создание, обновление

```
$counterparty = (new Counterparty($sklad, [
    "name" => "Васян"
]))->create();
```
                   
**Некоторым сущностям нужно указать связи при создании. Например для customerorder нужно указание counterparty и organization,
 и опционально массив позиций**

```
$order = (new CustomerOrder($this->sklad))->buildCreation()
    ->addCounterparty($counterparty)
    ->addOrganization($organization)
    ->addPositionList($positions)
    ->execute();
```
    
**Для обновления то-же самое**

`$product->buildUpdate()->addCountry($country)->execute();`

#Удаление

`$product->delete()`;

#Связи

**Чаще всего сущность полученная через api имеет какие-то связи**

`$product->relations;`

**Зная что, к примеру, у продукта есть связанный employee, но не зная название этого поля можно получить его так**

`$employee = $product->relations->find(Employee::class)`

**Так как связи обычно приходят в формате meta-объекта для получения полного объекта можно сделать так**

`$group = $product->relations->group->fresh()`

**А если связь - массив объектов типо такого, то на нем можно сделать операции описанные в разделе "Получение сущностей"**

`$products = $order->relationListQuery("positions")->getList()`

#Список сущностей

**EntityList - обертка для массива для работы с апи**

**Например получение assortment и превращение элементов в нужный тип**

`$differentProductsAndStuff = Assortment::query($sklad)->getList()->transformItemsToMetaClass();`

**Или массовое создание сущностей**

```
$neko = new Product($sklad, ["name" => "Кот"]);
$doge = new Product($sklad, ["name" => "Пёс"]);
$el = new EntityList($sklad, [$neko, $doge])->each(function($e) use($vasyan){
    $e->buildCreation()->addEmployee($vasyan);
})->massCreate();
```

**Можно превратить в массив**

`$el->toArray()`;

#Отчеты

**Они есть. Лежат в Moysklad\Entities\Reports неймспейсе. Некоторым можно указать особые поисковые запросы типо CounterpartyReportQuerySpecs**

#Что не доделано и может работать не верно

Комментарии где-то есть, где-то нет. Но это поправится.

Далеко не всем сущностям прописаны обязательные поля для создания. Большинство сущностей даже не 
тестировались, т.к. не хватает времени, так что я не уверен что у вас ничего не отвалится.

Не проверялись различные параметры для поиска, указываемые в QuerySpecs и его потомках.

