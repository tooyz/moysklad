#Moysklad PHP

**WIP**

Библиотека для сервиса "Мой склад" JSON API 1.1. Некоторые примеры можно найти в "tests". Все ещё далека от завершения.

**Установка:**<br />
    composer require tooyz/moysklad

**Что сейчас есть:**<br />
    1) Получение списков сущностей ( не все сущности присутствуют )<br />
    2) Создание<br />
    3) Обновление<br />
    4) Удаление<br />
    5) Фильтрация<br />
    6) Поиск<br />
    7) Получение связанных сущностей<br />
    8) Вебхуки<br />
    
**Запуск тестов:**<br />
    1) composer global require phpunit/phpunit<br />
    2) cd tests<br />
    3) composer update<br />
    4) Отредактировать Config.php <br />
    5) phpunit --configuration="./phpunit.xml" <ИЛИ> npm run test
    
#Общая информация

**Класс MoySklad**

Используется для авторизации, явно передается параметром в большинство компонентов т.к. возможно использование нескольких аккаунтов моегосклада одновременно.

$sklad = oySklad::getInstance($login, $password);

**Сущности**

Основные сущности моегосклада.

$product = new Product($sklad, [
    "name" => "Продукт"
]);

**Получение сущностей**

Получение всех сущностей:
$list = Product::query($sklad)->getList();

Можно добавить параметры запроса. Описание параметров в описании класса QuerySpecs.

$list = Product::query($sklad, QuerySpecs::create([
    "offset" => 15, 
    "maxResults" => 50,
]))->getList();

Фильтрация. Описание методов FilterQuery в комментариях.
$filteredList = Product::query($sklad)->filter(
    (new FilterQuery())
        ->eq("article", 12345)
);

Поиск по строке
$searchedList = Product::query($sklad)->search("название продукта");

Функции выше возвращают объект EntityList.

Получение по id.

$product = Product::query($sklad)->byId("12345-654321-123456-54321");

**Создание сущности**

$product = (new Product($sklad, [
                       "name" => "TestProduct"
                   ]))
                   ->buildCreation()
                   ->execute();
                   
Некоторым сущностям нужно указать связи при создании. Например для customerorder нужно указание counterparty и organization, и опционально массив позиций

$order = (new CustomerOrder($this->sklad))->buildCreation()
                     ->addCounterparty($counterparty)
                     ->addOrganization($organization)
                     ->addPositionList($positions)
                     ->execute();

#To be continued
