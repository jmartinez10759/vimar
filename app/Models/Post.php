<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\{CreatedByObserver};
use App\SoftUsers;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftUsers;

    public $guarded = [];
    public array $dates = [
        'deleted_at'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:00',
        'updated_at' => 'datetime:Y-m-d H:00'
    ];

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe([new CreatedByObserver]);
    }
}
