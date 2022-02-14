<?php

namespace App\Console\Commands;

use App\Libraries\CRUD\CRUDGenerator;
use Illuminate\Console\Command;

class CRUDCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all necessary files for Admin Panel CRUD';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->getRequiredParam('Enter filename (singular, CamelCase)');
        $permission = $this->getRequiredParam('Enter permissions namespace (plural, lowercase, snake_case)');
        $route = $this->getRequiredParam('Enter route URI segment (plural, lowercase, dash-separated case)');
        $titleSingular = $this->getRequiredParam('Enter title singular');
        $titlePlural = $this->getRequiredParam('Enter title plural');
        $columnName = $this->getRequiredParam('Enter CRUD column name (DB table column name)');

        $generator = new CRUDGenerator($name, $route, $permission, $titleSingular, $titlePlural, $columnName);

        $generator->generateModel();
        $this->info('Generating model completed successfully.');

        $generator->generateController();
        $this->info('Generating controller completed successfully.');

        $generator->generateTemplates();
        $this->info('Generating templates completed successfully.');

        $generator->addRoutes();
        $this->info('Generating routes completed successfully.');

        $generator->generatePolicy();
        $generator->addPermissions();
        $this->info('Adding permissions completed successfully.');

        $this->info('Finished!');
    }

    /**
     * @param $question
     * @return mixed|null
     */
    public function getRequiredParam($question)
    {
        $value = null;
        do {
            $value = $this->ask($question);
        } while(is_null($value));

        return $value;
    }
}
