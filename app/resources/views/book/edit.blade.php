@extends('layouts.app')

@section('title', isset($book) ? 'Редактирование книги' : 'Добавление книги')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ isset($book) ? 'Редактирование книги' : 'Добавление книги' }}</div>
            <div class="card-body">
                <form method="POST" action="{{ isset($book) ? route('book_update', $book->id) : route('book_store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($book))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label">Название</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="author_id" class="form-label">Автор</label>
                        <select class="form-select select2 @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                            <option value="">Выберите автора</option>
                            @foreach($authors as $authorOption)
                                <option value="{{ $authorOption->id }}" {{ (old('author_id', $book->author_id ?? (isset($author) ? $author->id : null)) == $authorOption->id) ? 'selected' : '' }}>
                                    {{ $authorOption->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Год издания</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', $book->year ?? '') }}" min="1000" max="2100">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}">
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $book->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cover" class="form-label">Обложка</label>
                        <input type="file" class="form-control @error('cover') is-invalid @enderror" id="cover" name="cover" accept="image/*">
                        @error('cover')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(isset($book) && $book->image_name)
                            <div class="mt-2">
                                <img src="{{ $book->image_link ?: asset('storage/books_covers/' . $book->image_name) }}" alt="{{ $book->title }}" style="max-height: 150px;">
                                <a href="{{ route('book_delete_cover', $book->id) }}" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Удалить обложку?');">Удалить обложку</a>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ isset($book) ? 'Сохранить' : 'Добавить' }}</button>
                        <a href="{{ isset($book) ? route('book_show', $book->id) : route('book_index') }}" class="btn btn-outline-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Выберите автора',
            allowClear: true,
            language: 'ru'
        });
    });
</script>
@endsection
