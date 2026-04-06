<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\AuthorSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('author')->paginate(20);
        return view('book.index', compact('books'));
    }

    /**
     * AJAX загрузка книг.
     */
    public function more(Request $request)
    {
        $page = $request->get('page', 2);
        
        // Используем простой подход - пропускаем нужное количество записей
        $books = Book::with('author')
            ->orderBy('id')
            ->skip(($page - 1) * 20)
            ->take(20)
            ->get();
        
        return view('book._list', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::all();
        return view('book.edit', compact('authors'));
    }

    /**
     * Show the form for creating a new resource with pre-selected author.
     */
    public function createWithAuthor($authorId)
    {
        $author = Author::findOrFail($authorId);
        $authors = Author::all();
        return view('book.edit', compact('authors', 'author'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'year' => 'nullable|integer',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:50',
            'cover' => 'nullable|image|max:2048',
        ]);

        $book = new Book($validated);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('books_covers', 'public');
            $book->image_name = basename($path);
            $book->image_link = Storage::url($path);
        }

        $book->save();

        // Создание уведомлений для подписчиков автора
        if ($book->author_id) {
            $subscriptions = AuthorSubscription::where('author_id', $book->author_id)->get();
            foreach ($subscriptions as $subscription) {
                $subscription->user->notifications()->create([
                    'title' => 'Новая книга автора ' . $book->author->name,
                    'message' => 'Вышла новая книга "' . $book->title . '"',
                ]);
            }
        }

        return redirect()->route('book_show', $book->id)->with('success', 'Книга добавлена.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::with('author')->findOrFail($id);
        return view('book.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        return view('book.edit', compact('book', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'year' => 'nullable|integer',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|max:50',
            'cover' => 'nullable|image|max:2048',
        ]);

        $book->fill($validated);

        if ($request->hasFile('cover')) {
            // Удалить старую обложку
            if ($book->image_name) {
                Storage::disk('public')->delete('books_covers/' . $book->image_name);
            }
            $path = $request->file('cover')->store('books_covers', 'public');
            $book->image_name = basename($path);
            $book->image_link = Storage::url($path);
        }

        $book->save();

        return redirect()->route('book_show', $book->id)->with('success', 'Книга обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        if ($book->image_name) {
            Storage::disk('public')->delete('books_covers/' . $book->image_name);
        }
        $book->delete();
        return redirect()->route('book_index')->with('success', 'Книга удалена.');
    }

    /**
     * Удалить обложку книги.
     */
    public function deleteCover(string $id)
    {
        $book = Book::findOrFail($id);
        if ($book->image_name) {
            Storage::disk('public')->delete('books_covers/' . $book->image_name);
            $book->image_name = null;
            $book->image_link = null;
            $book->save();
        }
        return back()->with('success', 'Обложка удалена.');
    }
}
