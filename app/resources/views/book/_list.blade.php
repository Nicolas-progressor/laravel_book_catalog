@foreach($books as $book)
<div class="col book-item">
    <div class="card book-card h-100">
        @if($book->image_link)
            <img src="{{ $book->image_link }}" class="card-img-top" alt="{{ $book->title }}">
        @else
            <div class="bg-light text-dark d-flex align-items-center justify-content-center" style="height: 250px;">
                <span>Нет обложки</span>
            </div>
        @endif
        <div class="card-body">
            <h6 class="card-title">{{ $book->title }}</h6>
            <p class="card-text">
                <small class="text-muted">
                    @if($book->author)
                        <a href="{{ route('author_show', $book->author->id) }}">{{ $book->author->name }}</a>
                    @else
                        Неизвестен
                    @endif
                    , {{ $book->year ?? '—' }}
                </small>
            </p>
        </div>
        <div class="card-footer">
            <a href="{{ route('book_show', $book->id) }}" class="btn btn-sm btn-outline-primary w-100">Подробнее</a>
        </div>
    </div>
</div>
@endforeach