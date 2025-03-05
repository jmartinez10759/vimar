<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class link to model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $repositoryName = $this->argument('repository');
        $pathRepository = "app/Repositories";
        $version        = \str($this->anticipate('¿Ingrese version del Repositorio?',["v1","v2"]))->upper();
        if($version == "")
            $version = "V1";

        $this->components->task("Creating repository {$pathRepository}/{$version}/{$repositoryName}.php", function() use ($pathRepository,$repositoryName,$version)
        {

            $fullPathRepo = "{$pathRepository}/{$version}";

            if (!File::exists($fullPathRepo))
                File::makeDirectory($fullPathRepo,0775,true);

            if (!File::exists("{$pathRepository}/Interfaces"))
                File::makeDirectory("{$pathRepository}/Interfaces",0775,true);

            if (!File::exists("{$pathRepository}/Interfaces/RepositoryInterface.php")){
                $schema = $this->schemaFileRepositoryInterface();
                File::put("{$pathRepository}/Interfaces/RepositoryInterface.php",$schema);
            }
            if (!File::exists("{$pathRepository}/BaseRepository.php")){
                $schemaExtends = $this->schemaFileRepositoryExtends();
                File::put("{$pathRepository}/BaseRepository.php",$schemaExtends);
            }
            if(!File::exists("{$fullPathRepo}/{$repositoryName}.php")){
                $schema = $this->schemaFileRepository($repositoryName,$version);
                File::put("{$fullPathRepo}/{$repositoryName}.php",$schema);
            }

        });

    }

    /**
     * @return string
     */
    private function schemaFileRepositoryInterface(): string
    {
        return
            '<?php namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface RepositoryInterface
{
   public function all(): Collection;

    public function save(array $data, array $attributes);

    public function getOrSave(array $data, array $attributes);

    public function delete($id);

    public function getById($id,array $columns);

    public function filterIn(string $field, array $data);

    public function getBy(array $attributes, array $columns);

    public function filterBy(array $data, array $columns): Collection;

}';

    }

    /**
     * @return string
     */
    private function schemaFileRepositoryExtends(): string
    {
        return
            '<?php namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\{RepositoryInterface};

class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model table
     */
    private Model $_repository;

    public function __construct(Model $model)
    {
        $this->setRepository($model);
    }

    public function all(): Collection
    {
        // TODO: Implement all() method.
        return $this->_repository->query()->get();
    }

    public function save(array $data, array $attributes): ?Model
    {
        // TODO: Implement create() method.
        return $this->_repository->query()->updateOrCreate($data,$attributes);
    }

    public function getOrSave(array $data, array $attributes): ?Model
    {
        // TODO: Implement create() method.
        return $this->_repository->query()->firstOrCreate($data,$attributes);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        return $this->_repository->query()->find($id)->delete();
    }

    public function getById($id, array $columns = ["*"]): ?Model
    {
        // TODO: Implement find() method.
        return $this->_repository->query()->find($id, $columns);
    }

    public function filterIn(string $field, array $data): ?Collection
    {
        // TODO: Implement filterIn() method.
        return $this->_repository->query()->whereIn($field, $data)->get();
    }

    public function getBy(array $attributes, array $columns = ["*"]): ?Model
    {
        // TODO: Implement filterIn() method.
        return $this->_repository->query()->where($attributes)->first($columns);
    }

    public function filterBy(array $data, array $columns = ["*"]): Collection
    {
        // TODO: Implement filters() method.
        return $this->_repository->query()->where($data)->get($columns);
    }

    protected function setRepository(Model $model): BaseRepository
    {
        // TODO: Implement setRepository() method.
        $this->_repository = $model;
        return $this;
    }

    protected function getRepository(): ?Model
    {
        // TODO: Implement getRepository() method.
        return $this->_repository;
    }

}';
    }

    /**
     * Make repositories by version
     *
     * @param string $repositoryName
     * @param string $version
     *
     * @return string
     */
    private function schemaFileRepository(string $repositoryName, string $version): string
    {
        $entityName     = \str($repositoryName)->replace("Repository","");
        $namespace      = "App\\Repositories\\{$version};";

        return
            '<?php namespace '.$namespace.'

use Illuminate\Database\Eloquent\Model;
use App\Repositories\{BaseRepository};
use App\Models\{'.$entityName.'};
use Illuminate\Support\Carbon;

class '.$repositoryName.' extends BaseRepository
{

    /**
     * Construct '.$repositoryName.' class
     */
    public function __construct()
    {
        parent::__construct(new '.$entityName.');
    }

    /**
     * Get the results of the data model '.$entityName.'with their respective filters
     *
     * @return mixed
     */
    public function getAllRepository(): mixed
    {
        return $this->getRepository()
            ->query()
            ->when(\request("key"), function ($query){
                return $query->where("key",\request("key"));
            })
            ->when(\request("status"), function ($query){
                return $query->where("status",\request()->boolean("status"));
            })
            ->filterByText(\request()->input("text"))
            ->filterByDateGlobal(\request()->input("filter"))
            ->latest()
            ->when(\request("page"), function ($query){
                return $query->paginate(\request("to"));
            },function ($query){
                return $query->get();
            });
    }

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function saveRepository(array $attributes): ?Model
    {
        return $this->save([
            "key" => $attributes["key"]
        ],$attributes);
    }

    /**
     * @param array $attributes
     * @param string $id
     * @return Model|null
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

}';
    }
}
