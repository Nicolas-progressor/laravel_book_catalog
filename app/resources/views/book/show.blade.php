@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="row">
    <div class="col-md-4">
        @if($book->image_link)
        <img src="{{ $book->image_link }}" alt="{{ $book->title }}" class="img-fluid rounded">
        @elseif($book->image_name)
        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
            <span class="text-muted">Обложка: {{ $book->image_name }}</span>
        </div>
        @else
        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
            <span class="text-muted">Нет обложки</span>
        </div>
        @endif
    </div>
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>{{ $book->title }}</h1>
                <p class="text-muted">
                    <a href="{{ route('author_show', $book->author->id) }}">{{ $book->author->name }}</a>
                </p>
            </div>
            <div>
                @auth
                    @if(auth()->user()->hasRole('ROLE_ADMIN'))
                        <div class="btn-group" role="group">
                            <a href="{{ route('book_edit', $book->id) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                            <form method="post" action="{{ route('book_delete', $book->id) }}" class="d-inline" onsubmit="return confirm('Удалить книгу?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
        
        @if($book->year)
        <p><strong>Год издания:</strong> {{ $book->year }}</p>
        @endif
        
        @if($book->isbn)
        <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
        @endif
        
        @if($book->description)
        <hr>
        <h4>Описание</h4>
        <p>{{ $book->description }}</p>
        @endif
        
        <hr>
        <a href="{{ route('book_index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
</div>
@endsection
