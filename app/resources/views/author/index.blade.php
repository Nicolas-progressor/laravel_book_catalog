@extends('layouts.app')

@section('title', 'Авторы')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Каталог авторов</h1>
    @auth
        @if(auth()->user()->hasRole('ROLE_ADMIN'))
            <a href="{{ route('author_new') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Добавить автора
            </a>
        @endif
    @endauth
</div>

<div id="authors-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" data-current-page="1" data-total-pages="{{ $authors->lastPage() }}">
    @include('author._list')
</div>
@if($authors->hasMorePages())
<div class="text-center mt-4" id="load-more-container">
    <button class="btn btn-primary" id="load-more-authors">Загрузить ещё</button>
</div>
@endif

<div id="loading-authors" class="text-center mt-3" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Загрузка...</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var currentPage = 1;
    var totalPages = $('#authors-container').data('total-pages');
    var isLoading = false;
    
    // Функция загрузки авторов
    function loadMoreAuthors() {
        if (isLoading || currentPage >= totalPages) return;
        
        isLoading = true;
        currentPage++;
        
        $('#loading-authors').show();
        $('#load-more-container').hide();
        
        $.ajax({
            url: '{{ route("author_more") }}',
            type: 'GET',
            data: { page: currentPage },
            success: function(data) {
                $('#authors-container').append(data);
                $('#loading-authors').hide();
                isLoading = false;
                
                // Если ещё есть страницы - показываем кнопку, иначе скрываем
                if (currentPage < totalPages) {
                    $('#load-more-container').show();
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.status);
                $('#loading-authors').hide();
                $('#load-more-container').show();
                isLoading = false;
            }
        });
    }
    
    // Обработка скролла
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
            loadMoreAuthors();
        }
    });
    
    // Кнопка тоже может работать
    $('#load-more-authors').click(function() {
        loadMoreAuthors();
    });
});
</script>
@endsection