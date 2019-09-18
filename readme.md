**Привет.**

Это мой учебный проект, который строится по мере получения мною знаний программирования в стеке
PHP, PostgreSQL с использованием всех возможностей Symfony 4 и подглядыванием в Google

В этом проекте я хочу реализовать "e-commerce" площадку ...iSponsor!,
не разбивая приложение на бандлы, при этом не забывая и про закон Деметры.

Приложение должно получиться монолитным (поскольку не планируется повторное использование его частей, не планируется создание собственного фреймворка, собственной CMS и т.д.). Тем не менее, логические части проекта должны получиться слабо связанными, контролеры тонкими, запросы в базу короткими с использованием индексов, ленивых запросов и кэша. 

Сейчас ведется работа над разработкой архитектуры бизнес-логики, которая должна быть хорошо представлена сущностями (вместе с их методами доступа и отношениями).
В результате мы получим полноценную схему базы данных, а в месте с этим будем иметь представление, куда двигаемся дальше.

Далее я планирую приступить к контролерам (уже сейчас базовые CRUD используются).

По мере движения вперед подключаю в проект необходимые сервисы, которые встречаю в сети, как популярные(рекомендуемые).

В какой-то момент я приступлю к нанесению слоя безопасности и слоя кеширования.

И в конечном счете поработаю над общим внешним видом, протестирую и попробую отправить в production.

**Что такое ...iSponsor!**

**...iSponsor!** - это идея краудфандинговой площадки, достаточно автоматизированной, чтобы снизить порог входа, с плавающей комиссией и персональной кармой участников процесса.

Планируется, как максимально открытый сервис с использованием децентрализованных подходов к защите информации от подделок.

Проект показал себя, как интересный, на Open Source с использованием CMS.

Теперь хочется построить профессиональное приложение для реального использования. 


**p.s.** По мере работы над проектом буду наполнять readme. Сказано далеко не все, но без этого файлика как-то не комильфо.

  