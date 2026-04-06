@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-start">
    <h1>{{ $author->name }}</h1>
    <div>
        @auth
            @if(!auth()->user()->hasRole('ROLE_ADMIN'))
                @if($isSubscribed)
                    <form method="post" action="{{ route('author_unsubscribe', $author->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Отписаться</button>
                    </form>
                @else
                    <form method="post" action="{{ route('author_subscribe', $author->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">Подписаться</button>
                    </form>
                @endif
            @endif
            @if(auth()->user()->hasRole('ROLE_ADMIN'))
                <div class="btn-group" role="group">
                    <a href="{{ route('author_edit', $author->id) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                    <form method="post" action="{{ route('author_delete', $author->id) }}" class="d-inline" onsubmit="return confirm('Удалить автора и все его книги?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>
<p class="text-muted">{{ $author->lifespan }}</p>

@if($author->biography)
    <hr>
    <h4>Биография</h4>
    <p>{{ $author->biography }}</p>
@endif

<hr>
<div class="d-flex justify-content-between align-items-center">
    <h4>Книги автора ({{ $totalBooks }})</h4>
    @auth
        @if(auth()->user()->hasRole('ROLE_ADMIN'))
            <a href="{{ route('book_new_with_author', $author->id) }}" class="btn btn-sm btn-primary">Добавить книгу</a>
        @endif
    @endauth
</div>

@if($books->isEmpty())
    <p>Нет книг</p>
@else
    <div id="books-container" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">
        @foreach($books as $book)
            <div class="col">
                <div class="card book-card h-100">
                    @if($book->image_name)
                        <img src="{{ $book->image_link ?: asset('storage/books_covers/' . $book->image_name) }}" class="card-img-top" alt="{{ $book->title }}">
                    @elseif($book->image_link)
                        <img src="{{ $book->image_link }}" class="card-img-top" alt="{{ $book->title }}">
                    @else
                        <div class="bg-light text-dark d-flex align-items-center justify-content-center" style="height: 250px;">
                            <span>Нет обложки</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title">{{ $book->title }}</h6>
                        <p class="card-text">
                            <small class="text-muted">{{ $book->year }}</small>
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('book_show', $book->id) }}" class="btn btn-sm btn-outline-primary w-100">Подробнее</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($hasMore)
    <div class="text-center mt-3">
        <button id="load-more-books" class="btn btn-outline-primary">Показать ещё</button>
    </div>
    @endif

    <div id="loading-books" style="display: none;">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
    </div>
@endif

<a href="{{ route('author_index') }}" class="btn btn-outline-secondary mt-4">Назад к списку</a>

@if($hasMore)
@push('scripts')
<script>
$(document).ready(function() {
    let currentLimit = {{ $limit }};
    const totalBooks = {{ $totalBooks }};
    let isLoading = false;

    $('#load-more-books').on('click', function() {
        if (isLoading) return;

        isLoading = true;
        $('#loading-books').show();
        $(this).hide();

        currentLimit += 20;

        $.get(`/author/{{ $author->id }}/books-more?limit=${currentLimit}`, function(html) {
            $('#books-container').html(html);
            $('#loading-books').hide();
            isLoading = false;

            if (currentLimit >= totalBooks) {
                $('#load-more-books').hide();
            } else {
                $('#load-more-books').show();
            }
        }).fail(function() {
            isLoading = false;
            $('#loading-books').hide();
            $('#load-more-books').show();
        });
    });
});
</script>
@endpush
@endif
@endsection