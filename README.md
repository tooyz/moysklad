#Moysklad PHP

**WIP**

Библиотека для сервиса "Мой склад" JSON API 1.1. Некоторые примеры можно найти в "tests". Все ещё далека от завершения.

**Что сейчас есть:**<br />
    1) Получение списков сущностей ( не все сущности присутствуют )<br />
    2) Обновление сущностей<br />
    3) Удаление<br />
    4) Фильтрация<br />
    5) Создание заказов, контрагентов<br />
    6) Вебхуки<br />


**Запуск тестов:**<br />
    1) composer global require phpunit/phpunit<br />
    2) cd tests<br />
    3) composer update<br />
    4) phpunit --configuration="./phpunit.xml" **или** phpunit --configuration="./phpunit.xml" Cases/<ИМЯ_КЛАССА><br />