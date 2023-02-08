<?php

namespace App\Models;

use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'article_id', 'id');
    }

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'description',
        'content',
        'video',
        'user_id',
        'category_id',
        'status_id',
    ];

    // protected $guarded = [];

    public $timestamps = true;
}
