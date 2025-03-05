<?php

namespace App;

use App\Models\{User};
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use Illuminate\Database\Eloquent\Builder;

trait SoftUsers
{
    public static function bootSoftUsers()
    {
        static::addGlobalScope("user", function (Builder $builder) {
            $userId = \request()->user()?->id;

            if ($userId)
                $builder->where("user_id", $userId);

        });

        static::creating(function ($model) {
            if (\request()->user()?->id)
                $model->user_id = \request()->user()?->id;

        });
    }
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
