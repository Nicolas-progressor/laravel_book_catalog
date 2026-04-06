@foreach($authors as $author)
<div class="col author-item">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title">
                <a href="{{ route('author_show', $author->id) }}">{{ $author->name }}</a>
            </h5>
            <p class="card-text">
                <small class="text-muted">{{ $author->birth_year }} – {{ $author->death_year ?? 'н.в.' }}</small>
            </p>
            <p class="card-text">
                <small>Книг: {{ $author->books->count() }}</small>
            </p>
            
            <div class="author-books" data-author-id="{{ $author->id }}" data-limit="3">
                @foreach($author->books->take(3) as $book)
                    <div class="d-flex align-items-center mb-2">
                        @if($book->image_link)
                            <img src="{{ $book->image_link }}" class="img-thumbnail me-2" alt="{{ $book->title }}" style="width: 40px; height: 50px; object-fit: contain;">
                        @else
                            <div class="bg-light text-dark d-flex align-items-center justify-content-center me-2 rounded" style="width: 40px; height: 50px;">
                                <small>Нет</small>
                            </div>
                        @endif
                        <a href="{{ route('book_show', $book->id) }}" class="text-decoration-none small">{{ $book->title }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach