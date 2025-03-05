<?php namespace App\Repositories\V1;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\{BaseRepository};
use App\Models\{Post};
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{Cache, DB, Http};

class PostRepository extends BaseRepository
{

    /**
     * @var string $api
     */
    private $api;

    /**
     * Construct PostRepository class
     */
    public function __construct()
    {
        parent::__construct(new Post);
        $this->api = "https://jsonplaceholder.typicode.com/posts";
    }

    /**
     * Get the results of the data model Post with their respective filters
     *
     * @return mixed
     */
    public function getAllRepository(): mixed
    {
        return $this->getRepository()
            ->query()
            ->when(\request("status"), function ($query){
                return $query->where("status",\request()->boolean("status"));
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();
    }

    /**
     * Get the results of the data model Post with their respective filters
     *
     * @return mixed
     */
    public function getHttpClientItems(): mixed
    {
        $response = Http::get("{$this->api}");
        if($response->successful())
            return $response->collect();

        return collect();
    }

    /**
     * Save resource in database
     *
     * @param array $attributes
     * @return Model|null
     *
     * @author Jorge Martinez Quezada
     */
    public function saveRepository(array $attributes): ?Model
    {
        return $this->save([
            "user_id" => auth()->id(),
            "title"   => $attributes["title"]
        ],$attributes);
    }

    /**
     * Update resource in database
     *
     * @param array $attributes
     * @param string $id
     *
     * @return Model|null
     *
     * @author Jorge Martinez Quezada
     */
    public function updateRepository(array $attributes,string $id): ?Model
    {
        return $this->save([
            "id" => $id
        ],$attributes);
    }

    /**
     * Saves the information of the entity and its relationship to the models
     *
     * @param Model $entity
     * @param array $attributes
     * @return Model|null
     */
    public function saveForEntityRepository(Model $entity, array $attributes): ?Model
    {
        return $entity->methodRelation()->updateOrCreate([
            "key" => $attributes["key"]
        ],$attributes);

    }

}
