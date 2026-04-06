@extends('layouts.app')

@section('title', isset($author) ? 'Редактирование автора' : 'Добавление автора')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ isset($author) ? 'Редактирование автора' : 'Добавление автора' }}</div>
            <div class="card-body">
                <form method="POST" action="{{ isset($author) ? route('author_update', $author->id) : route('author_store') }}">
                    @csrf
                    @if(isset($author))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя автора</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $author->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="biography" class="form-label">Биография</label>
                        <textarea class="form-control @error('biography') is-invalid @enderror" id="biography" name="biography" rows="5">{{ old('biography', $author->biography ?? '') }}</textarea>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="birth_year" class="form-label">Год рождения</label>
                                <input type="number" class="form-control @error('birth_year') is-invalid @enderror" id="birth_year" name="birth_year" value="{{ old('birth_year', $author->birth_year ?? '') }}" min="1000" max="2100">
                                @error('birth_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="death_year" class="form-label">Год смерти (оставьте пустым, если жив)</label>
                                <input type="number" class="form-control @error('death_year') is-invalid @enderror" id="death_year" name="death_year" value="{{ old('death_year', $author->death_year ?? '') }}" min="1000" max="2100">
                                @error('death_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ isset($author) ? 'Сохранить' : 'Добавить' }}</button>
                        <a href="{{ isset($author) ? route('author_show', $author->id) : route('author_index') }}" class="btn btn-outline-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection