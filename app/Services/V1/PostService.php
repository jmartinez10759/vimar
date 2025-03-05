<?php namespace App\Services\V1;

use App\Repositories\V1\{PostRepository};
use Illuminate\Support\Facades\{Cache, DB};
use Illuminate\Support\{Collection, Str, Arr};
use Illuminate\Database\Eloquent\Model;
use Inertia\Response;
use Inertia\Inertia;

class PostService extends PostRepository
{

    /**
     * Get the results of the data model Post with their respective filters
     *
     * @return mixed
     */
    public function getItems(): Response
    {

        DB::beginTransaction();

        try {

            $posts = $this->getAllRepository();
            if($posts->count() < 1)
                $posts = $this->getAndSaveHttp();

            DB::commit();

            return Inertia::render('Post/PostList', [
                'posts'     => $posts,
                'user'      => \request()->user()
            ]);

        } catch (\Throwable $e) {
            $error = $e->getMessage() . " " . $e->getLine() . " " . $e->getFile();
            \Log::error($error);
            DB::rollback();

            throw new \Exception($e->getMessage(),Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Store a newly created resource in storage and get resources.
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getAndSaveHttp(): mixed
    {
        $this->getHttpClientItems()->each(function($post){
            $this->saveRepository(Arr::only($post,["title","body"]));
        });

        return $this->getAllRepository();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Model|null
     * @throws \Exception
     */
    public function store(): ?Model
    {
        DB::beginTransaction();
        try {
            $payload = \request()->only(["title","body"]);
            $response = $this->saveRepository($payload);

            DB::commit();

            return $response;

        } catch (\Throwable $e) {
            $error = $e->getMessage() . " " . $e->getLine() . " " . $e->getFile();
            \Log::error($error);
            DB::rollback();

            throw new \Exception($e->getMessage(),Response::HTTP_BAD_REQUEST);
        }
    }

}
