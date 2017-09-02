twitter-news
============

# Installation
* `git clone git@github.com:andrew45105/twitter-news.git`
* `cd twitter-news`
* `composer install`
* `bower install` (установка зависимостей фронтенда - jq, bootstrap)
* `doctrine:database:create`
* `doctrine:schema:update --force`
* прописать в parameters.yml данные по twitter_credentials
* `app:user-create` (создание пользователя, логин и пароль - project_user в parameters.yml)
* `app:tweets-parse` (начальный парсинг твитов с целевого аккаунта в базу)
* поставить на cron `app:tweets-parse` с интервалом от 10 до 30 минут
* настроить локально сервер для проекта.

# Using
* зайти на главную старницу ("/"), ввести логин и пароль созданнного пользователя (project_user в parameters.yml)

# Futures
* авторизация для пользователей
* вывод списка твитов в хронологическом порядке с датой, ссылками, картинками и тегами
* групировка твитов по дням
* отображение популярных тегов и порядке убывания популярности
* страница с выводом твитов по отдельным тегам
* поиск по твитам с подсветкой поисковой фразы в найденных результатах
* простая пагинация по страницам
