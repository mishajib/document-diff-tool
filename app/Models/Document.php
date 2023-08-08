<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Statuses
    const ACTIVE   = 'active';
    const INACTIVE = 'inactive';
    public static $statuses = [
        self::ACTIVE,
        self::INACTIVE,
    ];

    /**
     * Get the versions for the document.
     *
     * @return HasMany
     */
    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    /**
     * Get the author for the document.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Get the clients for the document.
     *
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'document_users')
            ->withPivot('last_viewed_version', 'diff')
            ->withTimestamps();
    }

    public function getLastViewedVersionForUser($user)
    {
        return $this->clients()
            ->where('user_id', $user->id)
            ->orderBy('last_viewed_version', 'desc')
            ->first();
    }
}
