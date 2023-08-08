<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];


    // Roles
    const AUTHOR = 'author';
    const CLIENT = 'client';
    public static $roles = [
        self::AUTHOR,
        self::CLIENT,
    ];

    // Statuses
    const ACTIVE   = 'active';
    const INACTIVE = 'inactive';
    public static $statuses = [
        self::ACTIVE,
        self::INACTIVE,
    ];

    /**
     * Get the documents for the author.
     *
     * @return HasMany
     */
    public function authorDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'user_id');
    }


    /**
     * Get the documents for the client.
     *
     * @return BelongsToMany
     */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_users')
            ->withPivot('last_viewed_version', 'diff')
            ->withTimestamps();
    }

    public function lastViewedVersion($document)
    {
        return $this->documents()
            ->where('document_id', $document->id)
            ->orderBy('last_viewed_version', 'desc')
            ->first();
    }
}
