<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'author_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the author that is subscribed to.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
