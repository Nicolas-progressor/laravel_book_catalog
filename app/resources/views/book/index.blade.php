@extends('layouts.app')

@section('title', 'Книги')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Каталог книг</h1>
    @auth
        @if(auth()->user()->hasRole('ROLE_ADMIN'))
            <a href="{{ route('book_new') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Добавить книгу
            </a>
        @endif
    @endauth
</div>

<div id="books-container" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3" data-current-page="1" data-total-pages="{{ $books->lastPage() }}">
    @include('book._list')
</div>
@if($books->hasMorePages())
<div class="text-center mt-4" id="load-more-container">
    <button class="btn btn-primary" id="load-more-books">Загрузить ещё</button>
</div>
@endif

<div id="loading" class="text-center mt-3" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Загрузка...</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var currentPage = 1;
    var totalPages = $('#books-container').data('total-pages');
    var isLoading = false;
    
    // Функция загрузки книг
    function loadMoreBooks() {
        if (isLoading || currentPage >= totalPages) return;
        
        isLoading = true;
        currentPage++;
        
        $('#loading').show();
        $('#load-more-container').hide();
        
        $.ajax({
            url: '{{ route("book_more") }}',
            type: 'GET',
            data: { page: currentPage },
            success: function(data) {
                $('#books-container').append(data);
                $('#loading').hide();
                isLoading = false;
                
                // Если ещё есть страницы - показываем кнопку, иначе скрываем
                if (currentPage < totalPages) {
                    $('#load-more-container').show();
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.status);
                $('#loading').hide();
                $('#load-more-container').show();
                isLoading = false;
            }
        });
    }
    
    // Обработка скролла
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
            loadMoreBooks();
        }
    });
    
    // Кнопка тоже может работать
    $('#load-more-books').click(function() {
        loadMoreBooks();
    });
});
</script>
@endsection