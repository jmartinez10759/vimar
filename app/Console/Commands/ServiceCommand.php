<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

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
        $serviceName = $this->argument('service');
        $pathService = "app/Services";
        $version     = \str($this->anticipate('¿Ingrese version del Servicio?',["v1","v2"]))->upper();
        if($version == "")
            $version = "V1";

        $this->components->task("Creating service {$pathService}/{$version}/{$serviceName}.php", function() use ($pathService,$serviceName,$version) {

            $fullPathService = "{$pathService}/{$version}";

            if (!File::exists($fullPathService))
                File::makeDirectory($fullPathService,0775,true);

            $schema = $this->schemaFileService($serviceName,$version);
            File::put("{$fullPathService}/{$serviceName}.php",$schema);

        });

        return 0;

    }

    /**
     * Make schema service by version
     *
     * @param string $serviceName
     * @param string $version
     *
     * @return string
     */
    private function schemaFileService(string $serviceName, string $version): string
    {
        $entityName     = \str($serviceName)->replace("Service","");
        $service        = \str($entityName)->replace("Api","");
        $nameRepository = "{$service}Repository";
        $namespace      = "App\\Services\\{$version};";
        $namespaceRepo  = "App\\Repositories\\{$version}";

        return
            '<?php namespace '.$namespace.'

use '.$namespaceRepo.'\{'.$nameRepository.'};
use Illuminate\Support\Facades\{Cache, DB};
use Illuminate\Support\{Collection, Str};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\{Response};

class '.$serviceName.' extends '.$nameRepository.'
{
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
            $payload = \request()->only();
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

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return Model|null
     * @throws \Exception
     */
    public function update(string $id): ?Model
    {
        DB::beginTransaction();
        try {
            $payload = \request()->only();
             $response = $this->updateRepository(
                $payload,
                $id
            );

            DB::commit();

            return $response;

        } catch (\Throwable $e) {
            $error = $e->getMessage() . " " . $e->getLine() . " " . $e->getFile();
            \Log::error($error);
            DB::rollback();

            throw new \Exception($e->getMessage(),Response::HTTP_BAD_REQUEST);
        }
    }

}';
    }
}
