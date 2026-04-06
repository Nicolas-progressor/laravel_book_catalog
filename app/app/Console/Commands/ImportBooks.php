<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import authors and books from demo-data JSON files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basePath = '/var/www/demo-data';
        $authorsFile = $basePath . '/authors.json';
        $booksFile = $basePath . '/books.json';

        if (!File::exists($authorsFile) || !File::exists($booksFile)) {
            $this->error('Files not found: ' . $authorsFile . ' or ' . $booksFile);
            return Command::FAILURE;
        }

        $authorsData = json_decode(File::get($authorsFile), true);
        $booksData = json_decode(File::get($booksFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON format');
            return Command::FAILURE;
        }

        DB::transaction(function () use ($authorsData, $booksData) {
            // Import authors
            foreach ($authorsData as $authorData) {
                Author::updateOrCreate(
                    ['id' => $authorData['id']],
                    [
                        'name' => $authorData['name'],
                        'biography' => $authorData['biography'] ?? null,
                        'birth_year' => $authorData['birth_year'] ?? null,
                        'death_year' => $authorData['death_year'] ?? null,
                    ]
                );
            }

            // Import books
            foreach ($booksData as $bookData) {
                Book::updateOrCreate(
                    ['id' => $bookData['book_id']],
                    [
                        'title' => $bookData['title'],
                        'year' => $bookData['year'] ?? null,
                        'description' => $bookData['description'] ?? null,
                        'isbn' => $bookData['isbn'] ?? null,
                        'image_name' => $bookData['imagename'] ?? null,
                        'image_link' => $bookData['imagelink'] ?? null,
                        'author_id' => $bookData['author_id'],
                    ]
                );
            }
        });

        $this->info(sprintf('Imported %d authors and %d books', count($authorsData), count($booksData)));
        return Command::SUCCESS;
    }
}
