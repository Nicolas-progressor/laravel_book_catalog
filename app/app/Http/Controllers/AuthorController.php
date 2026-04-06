<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\AuthorSubscription;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::paginate(20);
        return view('author.index', compact('authors'));
    }

    /**
     * AJAX загрузка авторов.
     */
    public function more(Request $request)
    {
        $page = $request->get('page', 2);
        
        $authors = Author::orderBy('id')
            ->skip(($page - 1) * 20)
            ->take(20)
            ->get();
        
        return view('author._list', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('author.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'birth_year' => 'nullable|integer',
            'death_year' => 'nullable|integer',
        ]);

        Author::create($validated);

        return redirect()->route('author_index')->with('success', 'Автор добавлен.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::with('books')->findOrFail($id);
        $isSubscribed = false;
        if (Auth::check()) {
            $isSubscribed = AuthorSubscription::where('user_id', Auth::id())
                ->where('author_id', $id)
                ->exists();
        }

        $limit = 20;
        $books = $author->books()->paginate($limit);
        $totalBooks = $books->total();
        $hasMore = $books->hasMorePages();

        return view('author.show', compact('author', 'isSubscribed', 'books', 'totalBooks', 'hasMore', 'limit'));
    }

    /**
     * Превью книг автора.
     */
    public function booksPreview(string $id, Request $request)
    {
        $author = Author::findOrFail($id);
        $books = $author->books()->limit(5)->get();
        return view('author._books_preview', compact('books'));
    }

    /**
     * AJAX загрузка книг автора.
     */
    public function booksMore(string $id, Request $request)
    {
        $page = $request->get('page', 1);
        $author = Author::findOrFail($id);
        $books = $author->books()->paginate(10, ['*'], 'page', $page);
        return view('book._list', compact('books'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $author = Author::findOrFail($id);
        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $author = Author::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'birth_year' => 'nullable|integer',
            'death_year' => 'nullable|integer',
        ]);

        $author->update($validated);

        return redirect()->route('author_show', $author->id)->with('success', 'Автор обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        return redirect()->route('author_index')->with('success', 'Автор удален.');
    }

    /**
     * Подписаться на автора.
     */
    public function subscribe(string $id)
    {
        $author = Author::findOrFail($id);
        $subscription = AuthorSubscription::firstOrCreate([
            'user_id' => Auth::id(),
            'author_id' => $author->id,
        ]);
        return back()->with('success', 'Вы подписались на автора.');
    }

    /**
     * Отписаться от автора.
     */
    public function unsubscribe(string $id)
    {
        $author = Author::findOrFail($id);
        AuthorSubscription::where('user_id', Auth::id())
            ->where('author_id', $author->id)
            ->delete();
        return back()->with('success', 'Вы отписались от автора.');
    }
}
