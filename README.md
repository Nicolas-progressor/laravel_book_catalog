# Laravel Book Catalog

Веб-приложение для управления каталогом книг и авторов, построенное на Laravel с использованием Docker.

## Возможности

- 📚 **Каталог книг** — просмотр с автоподгрузкой при скролле
- 👤 **Каталог авторов** — список авторов с книгами, пагинация
- 🔔 **Подписки на авторов** — получайте уведомления о новых книгах любимых авторов
- 👤 **Регистрация и вход** — создание аккаунта пользователя
- ⚙️ **Управление (для админов)** — полный CRUD для книг и авторов
- 🖼️ **Обложки книг** — загрузка и управление изображениями
- 🔔 **Уведомления** — персональные оповещения о новых книгах

## Технологии

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade шаблоны, Bootstrap, CSS
- **База данных**: PostgreSQL (основная), возможна миграция на MySQL/SQLite
- **Контейнеризация**: Docker, Docker Compose
- **Веб-сервер**: Nginx

## Структура проекта

```
laravel_book_catalog/
├── app/                    # Laravel приложение
│   ├── app/               # Код приложения (модели, контроллеры и т.д.)
│   ├── bootstrap/         # Файлы начальной загрузки
│   ├── config/            # Конфигурации
│   ├── database/          # Миграции, сидеры
│   ├── public/            # Публичные файлы
│   ├── resources/         # Шаблоны, CSS, JS
│   ├── routes/            # Маршруты
│   └── storage/           # Логи, кэш, загруженные файлы
├── demo-data/             # Демонстрационные данные (книги, авторы, обложки)
├── docker/                # Docker конфигурации
│   ├── nginx/             # Конфигурация Nginx
│   └── php/               # Dockerfile для PHP
├── docker-compose.yml     # Docker Compose конфигурация
└── README.md              # Этот файл
```

## Быстрый старт с Docker

### Предварительные требования

- Docker и Docker Compose установлены
- Git (для клонирования репозитория)

### Шаги развертывания

1. **Клонирование репозитория**
   ```bash
   git clone https://github.com/Nicolas-progressor/laravel_book_catalog.git
   cd laravel_book_catalog
   ```

2. **Настройка переменных окружения**
   ```bash
   cp app/.env.example app/.env
   ```
   При необходимости отредактируйте `app/.env` (например, настройки базы данных). По умолчанию настроена работа с PostgreSQL внутри Docker.

3. **Запуск контейнеров**
   ```bash
   docker-compose up -d
   ```
   Запустятся три сервиса: `php` (приложение Laravel), `nginx` (веб-сервер), `database` (PostgreSQL).

4. **Установка зависимостей Laravel**
   ```bash
   docker-compose exec app composer install
   ```

5. **Генерация ключа приложения**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Запуск миграций и сидеров** (см. раздел "Миграции базы данных")

7. **Доступ к приложению**
   - Веб-приложение: http://localhost:8084
   - База данных PostgreSQL: localhost:5434 (логин: laravel, пароль: secret, база: laravel)

## Миграции базы данных

### Настройка базы данных

По умолчанию используется PostgreSQL, настроенный в Docker Compose. Параметры подключения:
- Хост: `database` (имя сервиса в Docker)
- Порт: `5432` (внутри сети Docker)
- База данных: `laravel`
- Пользователь: `laravel`
- Пароль: `secret`

Эти параметры уже прописаны в `app/.env.example`. Если вы хотите использовать другую СУБД, измените настройки в `app/.env`.

### Выполнение миграций

1. **Запуск миграций** (создание таблиц)
   ```bash
   docker-compose exec app php artisan migrate
   ```

2. **Запуск сидеров** (демонстрационные данные)
   ```bash
   docker-compose exec app php artisan db:seed
   ```

3. **Импорт демонстрационных книг и авторов** (опционально)
   ```bash
   docker-compose exec app php artisan import:books
   ```
   Эта команда импортирует книги и авторов из файлов `demo-data/books.json` и `demo-data/authors.json`, а также копирует обложки книг в хранилище.

### Список миграций

- `0001_01_01_000000_create_users_table` - Пользователи
- `0001_01_01_000001_create_cache_table` - Кэш
- `0001_01_01_000002_create_jobs_table` - Очереди заданий
- `2026_03_09_084700_add_username_and_roles_to_users_table` - Добавление username и ролей
- `2026_03_09_084717_create_authors_table` - Авторы
- `2026_03_09_084740_create_books_table` - Книги
- `2026_03_09_084757_create_notifications_table` - Уведомления
- `2026_03_09_084814_create_author_subscriptions_table` - Подписки на авторов

## Пользовательские Artisan команды

### Создание администратора
```bash
docker-compose exec app php artisan add:admin
```
Создаёт пользователя с ролью администратора (email: admin@example.com, пароль: password).

### Создание тестового пользователя
```bash
docker-compose exec app php artisan create:test-user
```
Создаёт обычного пользователя (email: user@example.com, пароль: password).

### Импорт книг из демо-данных
```bash
docker-compose exec app php artisan import:books
```
Импортирует книги и авторов из файлов `demo-data/books.json` и `demo-data/authors.json`.

## Разработка без Docker

### Предварительные требования

- PHP 8.2+
- Composer
- Node.js 18+ (для сборки фронтенда)
- PostgreSQL (или другая поддерживаемая СУБД)

### Установка

1. **Установка зависимостей PHP**
   ```bash
   cd app
   composer install
   ```

2. **Установка зависимостей Node.js**
   ```bash
   npm install
   ```

3. **Настройка окружения**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Отредактируйте `.env`, указав параметры подключения к вашей базе данных.

4. **Настройка базы данных**
   Создайте базу данных в PostgreSQL (например, `laravel`), затем:
   ```bash
   php artisan migrate --seed
   ```

5. **Запуск сервера разработки**
   ```bash
   php artisan serve
   ```

6. **Сборка фронтенда** (в отдельном терминале)
   ```bash
   npm run dev
   ```

## Доступные маршруты

- `/` - Главная страница
- `/login` - Страница входа
- `/register` - Страница регистрации
- `/books` - Список книг
- `/books/{id}` - Просмотр книги
- `/authors` - Список авторов
- `/authors/{id}` - Просмотр автора
- `/profile` - Профиль пользователя
- `/profile/notifications` - Уведомления пользователя

## Роли пользователей

- **Администратор**: Полный доступ ко всем функциям, управление книгами и авторами.
- **Пользователь**: Просмотр книг, подписка на авторов, получение уведомлений.

## Демонстрационные данные

В папке `demo-data/` содержатся:
- `authors.json` - Список авторов с биографиями
- `books.json` - Список книг с описаниями и ссылками на авторов
- `books_covers/` - Обложки книг в формате JPG

## Устранение неполадок

### Проблемы с правами доступа к файлам
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Очистка кэша
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Просмотр логов
```bash
docker-compose logs app
docker-compose logs nginx
docker-compose logs database
```

### Пересборка контейнеров
```bash
docker-compose down
docker-compose up -d --build
```

### Проблемы с подключением к базе данных
Убедитесь, что контейнер `database` запущен:
```bash
docker-compose ps
```
Если база данных не запускается, проверьте, не занят ли порт 5434:
```bash
sudo lsof -i :5434
```

## Лицензия

Проект использует лицензию MIT. Подробнее см. в файле [LICENSE](app/LICENSE).

## Контакты

- Репозиторий: [https://github.com/Nicolas-progressor/laravel_book_catalog](https://github.com/Nicolas-progressor/laravel_book_catalog)
- Вопросы и предложения: создавайте issues в репозитории
