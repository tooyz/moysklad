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