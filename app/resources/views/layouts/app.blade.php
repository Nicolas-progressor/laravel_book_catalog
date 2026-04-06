<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Каталог книг')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/books.css') }}" rel="stylesheet">
    <link href="{{ asset('css/authors.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ru.js"></script>

    <!-- Иконки Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

</head>
<body class="d-flex flex-column min-vh-100">

<!-- Верхняя навигационная панель -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <!-- Логотип -->
        <a class="navbar-brand" href="{{ route('app_home') }}">
            <i class="bi bi-stack"></i> Каталог книг
        </a>

        <!-- Кнопка для мобильных устройств -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Основное меню -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('app_home') ? 'active' : '' }}" href="{{ route('app_home') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('book_index') ? 'active' : '' }}" href="{{ route('book_index') }}">Книги</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('author_index') ? 'active' : '' }}" href="{{ route('author_index') }}">Авторы</a>
                </li>
            </ul>

            <!-- Правая часть меню -->
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('app_register') }}">Регистрация</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('app_login') }}">Войти</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('app_notifications') }}" title="Уведомления">
                            <i class="bi bi-bell"></i>
                            @php
                                $unreadCount = auth()->user()->unreadNotificationsCount();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('app_profile') }}">Мой профиль</a></li>
                            <li><a class="dropdown-item" href="{{ route('app_notifications') }}">Уведомления</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('app_logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Основной контент -->
<main class="container mt-4 flex-grow-1">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>

<!-- Подвал -->
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Левый блок -->
            <div class="col-md-4">
                <h5><i class="bi bi-stack"></i> Каталог книг</h5>
            </div>

            <!-- Центральный блок -->
            <div class="col-md-4 text-center">
                &copy; {{ date('Y') }} Тестовое приложение для Laravel.
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS с Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')

</body>
</html>