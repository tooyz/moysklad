#Moysklad PHP

**WIP**

Библиотека для сервиса "Мой склад" JSON API 1.1. Некоторые примеры можно найти в "tests". Все ещё далека от завершения.

**Что сейчас есть:**
    1) Получение списков сущностей ( не все сущности присутствуют )
    2) Обновление сущностей
    3) Удаление
    4) Фильтрация
    5) Создание заказов, контрагентов
    6) Вебхуки

**Запуск тестов:**
    1) composer global require phpunit/phpunit
    2) cd tests
    3) composer update
    4) phpunit --configuration="./phpunit.xml" **или** phpunit --configuration="./phpunit.xml" Cases/<ИМЯ_КЛАССА>