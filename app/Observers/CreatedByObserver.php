<?php

namespace App\Observers;

class CreatedByObserver
{
    /**
     * @var mixed $user_id
     */
    public mixed $user_id;

    /**
     * Construct CreatedByObserver class
     *
     * @author Jorge Martinez Quezada
     */
    public function __construct()
    {
        $this->user_id = \request()->user()?->getKey();
    }

    /**
     * @param $model
     * @return void
     *
     * @author Jorge Martinez Quezada
     */
    public function created($model): void
    {
        $model->user_id = $this->user_id;
        $model->save();

    }

}
