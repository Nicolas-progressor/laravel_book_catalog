<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'biography',
        'birth_year',
        'death_year',
    ];

    /**
     * Get the books for the author.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the subscriptions for the author.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(AuthorSubscription::class);
    }

    /**
     * Get the lifespan string.
     */
    public function getLifespanAttribute(): string
    {
        $birth = $this->birth_year;
        $death = $this->death_year ?? 'н.в.';
        return "$birth – $death";
    }
}
