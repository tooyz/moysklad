## Moysklad PHP

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/55ec86f9-d527-4bb7-8e4e-cdc30269728b/small.png)](https://insight.sensiolabs.com/projects/55ec86f9-d527-4bb7-8e4e-cdc30269728b)

Библиотека для сервиса "Мой склад" JSON API 1.2 (тестируется). Некоторые примеры можно найти в "tests". Все ещё далека от завершения.

## Установка<br />
    composer require tooyz/moysklad
    
**Запуск тестов:**<br />
    1) composer global require phpunit/phpunit<br />
    2) cd tests<br />
    3) composer update<br />
    4) Отредактировать Config.php <br />
    5) phpunit --configuration="./phpunit.xml" <ИЛИ> npm run test
    

## Класс MoySklad

Используется для авторизации, явно передается параметром в большинство компонентов т.к. возможно использование нескольких аккаунтов моегосклада одновременно.

`$sklad = MoySklad::getInstance($login, $password);`

## Сущности

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

## Получение сущностей

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

## Создание, обновление

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

## Удаление

`$product->delete()`;

## Связи

**Чаще всего сущность полученная через api имеет какие-то связи**

`$product->relations;`

**Зная что, к примеру, у продукта есть связанный employee, но не зная название этого поля можно получить его так**

`$employee = $product->relations->find(Employee::class)`

**Так как связи обычно приходят в формате meta-объекта для получения полного объекта можно сделать так**

`$group = $product->relations->group->fresh()`

**А если связь - массив объектов типо такого, то на нем можно сделать операции описанные в разделе "Получение сущностей"**

`$products = $order->relationListQuery("positions")->getList()`

## Список сущностей

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

## Работа с картинками

**Прицепление изображений к сущности**

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

**Скачивание изображения**

```
$product->image->download('/usr/images/cat.jpg', 'normal');
/* normal, miniature, tiny - размеры изображений */
```


## Specs

**Классы для конфигурации различных действий**

```
    <SpecsClass>::create(["field"=>"value"]);
```
В метод create передаются поля конфигурации

**LinkingSpecs** используется для описания связывания сущности и других сущностей учавствующих в обновлении/создании первой

Имеет следующие поля:

**name** - имя, которое получит новая связь. Если не указано - будет использоваться имя сущности в моемскладе

**multiple** - связь будет массивом сущностей, то есть можно указать несколько связей с одинаковым именем

**fields** - взять только указанные поля для создания связи

**excludedFields** - взять все поля, кроме указанных

Пример добавления доп. поля к контрагенту

```
$specs = LinkingSpecs::create([
                 "name" => "attributes", //в апи доп поля хранятся в поле attributes
                 "multiple" => true //и являются массивом
             ]);
$counterparty = $counterparty->buildUpdate()
    ->addAttribute($attribute, $specs)
    ->addAttribute($attribute2, $specs)
    ->execute();
```

**QuerySpecs** конфигурация EntityQuery и RelationQuery объектов

Поля:

**limit** - количество результатов в одном отправляемом запросе (100 по умолчанию)

**offset** - сдвиг результатов 

**maxResults** - максимальное возвращаемое количество результатов

**expand** - возможность получить результат с указанными связями (Expand объект)

**updatedFrom** - объекты, момент обновления которых меньше или равен значению, указанному в параметре (CommonDate объект)

**updatedTo** - объекты, момент обновления которых меньше или равен значению, указанному в параметре (CommonDate объект)

**updatedBy** -  В выборку попадут все объекты, автором последних изменений которых является пользователь с uid, указанным в значении параметра.

```
Product::query($sklad, QuerySpecs::create([
            'maxResults' => 25,
            'expand' => Expand::create([Employee::$entityName]),
            'updatedFrom' => new CommonDate("2017-01-01"),
            'updatedBy' => "admin@admin"
        ]))->getList();
```

## Публикации

**Документные сущности поддерживают публикации**

**Получение**

```
$publications = $customerOrder->getPublications(QuerySpecs::create())
```

**Создание**

```
$publication = $customerOrder->createPublication($customTemplate)
```

**Удаление**

```
$customerOrder->deletePublication($publication)
```

**Получение публикации по id**

```
$publication = $customerOrder->getPublicationById("123-456")
```

## Печать документов

**Документные сущности поддерживают печать**

**Создание**

При создании запроса на печать можно передать либо AbstractTemplate либо EntityList&lt;AbstractTemplate>
```
$export = $demand->createExport($templateEntity, 'xls');
$exports = $demand->createExport($templateList);
```

**Получение стандартных шаблонов**

```
$templates = $demand->getExportEmbeddedTemplates();
```

**Получение пользовательских шаблонов**

```
$templates = $demand->getExportCustomTemplates();
```

**Получение стандартного шаблона по id**

```
$templates = $demand->getExportEmbeddedTemplateById(123);
```

**Получение пользовательского шаблона по id**

```
$templates = $demand->getExportCustomTemplateById(123);
```

## Отчеты

**Содержат статические методы для получения отчетов.**
```
$report = DashboardReport::day($sklad);
```
**Некоторым можно указать особые поисковые запросы типо CounterpartyReportQuerySpecs**
```
$report = SalesReport::byEmployee($sklad, SalesReportQuerySpecs::create([
    "counterparty.id" => $cpId
]));
```

## Аудит

**История событий системы**

**Получить последние 5 контекстов по заказам покупателей**
```
$audits = Audit::query($this->sklad, QuerySpecs::create([
                  'maxResults' => 5
              ]))->filter((new FilterQuery())
                             ->eq("entityType", "customerorder")
                          );
```
**Получить события по контексту**
```
$events = $audit->getAuditEvents();
```

**Получить события по сущности**
```
$events = $customerOrder->getAuditEvents();
```

**Получить список фильтров**
```
$filters = Audit::getFilters($this->sklad);
```

## Отладка

Статический класс **RequestLog** содержит ограниченную историю запросов/ответов в апи.

Можно получить последний запрос/ответ
```
RequestLog::getLast()
```
Или все
```
RequestLog::getList()
```
По умолчанию для ограничения потребления памяти хранится 50 последних запросов,
по достижению лимита старые запросы удаляются. Изменить лимит можно так:
```
RequestLog::setStorageSize(500); // 500 запросов
RequestLog::setStorageSize(0); //Без лимита
```
Для остановки логирования можно вызвать
```
RequestLog::setEnabled(false);
```

## Другие библиотеки

* Ruby https://github.com/dapi/moysklad
* JavaScript/nodejs https://github.com/wmakeev/moysklad-client

