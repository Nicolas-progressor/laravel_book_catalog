@extends('layouts.app')

@section('content')
<h1>Мой профиль</h1>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Основная информация</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('app_profile_update') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label for="name" class="form-label">Имя</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="username" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Роли:</strong></p>
                <p class="text-muted">
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{ str_replace('ROLE_', '', $role) }}</span>
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-warning bg-opacity-25">
        <h5 class="mb-0">Смена пароля</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('app_profile_update') }}" class="row g-3">
            @csrf
            <div class="col-12">
                <label for="current_password" class="form-label">Текущий пароль</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="new_password" class="form-label">Новый пароль</label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="new_password_confirmation" class="form-label">Подтверждение нового пароля</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-warning">Изменить пароль</button>
            </div>
        </form>
    </div>
</div>
@endsection