<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $url
 * @property string $author
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $user_id
 * @property User $user
 */
class Preview extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'url',
        'author',
        'title',
        'description',
        'image',
        'user_id'
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
