<?php

namespace App\Libraries\CRUD;

use Illuminate\Support\Facades\Artisan;

class CRUDGenerator
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $permission;

    /**
     * @var string
     */
    private $titleSingular;

    /**
     * @var string
     */
    private $titlePlural;

    /**
     * @var string
     */
    private $columnName;

    /**
     * CRUDGenerator constructor.
     * @param $name
     * @param $route
     * @param $permission
     * @param $titleSingular
     * @param $titlePlural
     * @param $columnName
     */
    public function __construct($name, $route, $permission, $titleSingular, $titlePlural, $columnName)
    {
        $this->name = $name;
        $this->route = $route;
        $this->permission = $permission;
        $this->titleSingular = $titleSingular;
        $this->titlePlural = $titlePlural;
        $this->columnName = $columnName;
    }

    /**
     *
     */
    public function generateModel()
    {
        $this->generateFile(
            app_path('Models/Admin/' . ucfirst($this->name) . '.php'),
            app_path('Libraries/CRUD/templates/app/Models/Admin/CRUD_filename.php')
        );

        $this->generateFile(
            app_path('Traits/DataTables/Admin/' . ucfirst($this->name) . 'DataTable.php'),
            app_path('Libraries/CRUD/templates/app/Traits/DataTables/CRUD_filenameDataTable.php')
        );

        $this->generateFile(
            app_path('Traits/Revisionable/Admin/' . ucfirst($this->name) . 'Revisionable.php'),
            app_path('Libraries/CRUD/templates/app/Traits/Revisionable/CRUD_filenameRevisionable.php')
        );

        $this->generateFile(
            app_path('Traits/Translations/Admin/' . ucfirst($this->name) . 'Translation.php'),
            app_path('Libraries/CRUD/templates/app/Traits/Translations/CRUD_filenameTranslation.php')
        );
    }

    /**
     *
     */
    public function generateController()
    {
        $this->generateFile(
            app_path('Http/Controllers/Admin/' . ucfirst($this->name) . 'Controller.php'),
            app_path('Libraries/CRUD/templates/app/Http/Controllers/Admin/CRUD_filenameController.php')
        );

        $this->generateFile(
            app_path('Http/Requests/Admin/Store' . ucfirst($this->name) . 'Request.php'),
            app_path('Libraries/CRUD/templates/app/Http/Requests/Admin/StoreCRUD_filenameRequest.php')
        );

        $this->generateFile(
            app_path('Http/Requests/Admin/Update' . ucfirst($this->name) . 'Request.php'),
            app_path('Libraries/CRUD/templates/app/Http/Requests/Admin/UpdateCRUD_filenameRequest.php')
        );
    }

    /**
     *
     */
    public function generateTemplates()
    {
        $viewsDir = resource_path('views/admin/' . strtolower($this->route));
        if (!file_exists($viewsDir)) {
            mkdir($viewsDir);
        }

        $this->generateFile(
            $viewsDir . '/create.blade.php',
            app_path('Libraries/CRUD/templates/resources/views/create.blade.php')
        );

        $this->generateFile(
            $viewsDir . '/edit.blade.php',
            app_path('Libraries/CRUD/templates/resources/views/edit.blade.php')
        );

        $this->generateFile(
            $viewsDir . '/index.blade.php',
            app_path('Libraries/CRUD/templates/resources/views/index.blade.php')
        );

        $this->generateFile(
            $viewsDir . '/show.blade.php',
            app_path('Libraries/CRUD/templates/resources/views/show.blade.php')
        );
    }

    /**
     *
     */
    public function generatePolicy()
    {
        $this->generateFile(
            app_path('Policies/Admin/' . ucfirst($this->name) . 'Policy.php'),
            app_path('Libraries/CRUD/templates/app/Policies/Admin/CRUD_filenamePolicy.php')
        );
    }

    /**
     *
     */
    public function addRoutes()
    {
        $resourceRoute = "
    Route::resource('CRUD_route', 'CRUD_filenameController');";

        $this->addToFile(
            $resourceRoute,
            base_path('routes/web.php'),
            '/* crud:create add resource route */'
        );

        $datatableRoute = "
        Route::post('CRUD_route', ['uses' => 'CRUD_filenameController@datatable', 'as' => 'CRUD_route']);";

        $this->addToFile(
            $datatableRoute,
            base_path('routes/web.php'),
            '/* crud:create add datatable route */'
        );

        $datatableRoute = "
        Route::post('CRUD_route/restore/{id}', ['uses' => 'CRUD_filenameController@restore', 'as' => 'CRUD_route.restore']);";

        $this->addToFile(
            $datatableRoute,
            base_path('routes/web.php'),
            '/* crud:create add restore route */'
        );
    }

    /**
     *
     */
    public function addPermissions()
    {
        $policy = "
        'App\Models\Admin\CRUD_filename' => 'App\Policies\Admin\CRUD_filenamePolicy',";

        $this->addToFile(
            $policy,
            app_path('Providers/AuthServiceProvider.php'),
            '/* crud:create add policy */'
        );

        $section = "
    [
        'label' => \"__('CRUD_title_plural')\",
        'permission_target' => 'CRUD_permission',
        'permissions' => [
            'create' => \"__('create-permission')\",
            'view_all' => \"__('view_all-permission')\",
            'view_own' => \"__('view_own-permission')\",
            'view_deleted' => \"__('view_deleted-permission')\",
            'update_all' => \"__('update_all-permission')\",
            'update_own' => \"__('update_own-permission')\",
            'delete_all' => \"__('delete_all-permission')\",
            'delete_own' => \"__('delete_own-permission')\",
            'delete_forever' => \"__('delete_forever-permission')\",
            'restore_all' => \"__('restore_all-permission')\",
            'restore_own' => \"__('restore_own-permission')\",
            'export' => \"__('export-permission')\",
        ]
    ],";

        $this->addToFile(
            $section,
            config_path('sections.php'),
            '/* crud:create add section */'
        );

        exec('php ' . base_path('artisan') . ' db:seed --class=PermissionsTableSeeder');

        exec('php ' . base_path('artisan') . ' db:seed --class=RolePermissionsTableSeeder');
    }

    /**
     * @param $toFile
     * @param $fromFile
     */
    private function generateFile($toFile, $fromFile)
    {
        $data = file_get_contents($fromFile);
        $data = $this->rename($data);

        if (!file_exists($toFile)) {
            file_put_contents($toFile, $data);
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function rename($data)
    {
        $data = str_replace('CRUD_filename', ucfirst($this->name), $data);
        $data = str_replace('CRUD_lcfirst', lcfirst($this->name), $data);
        $data = str_replace('CRUD_permission', strtolower($this->permission), $data);
        $data = str_replace('CRUD_route', strtolower($this->route), $data);
        $data = str_replace('CRUD_title_singular', ucfirst($this->titleSingular), $data);
        $data = str_replace('CRUD_title_plural', ucfirst($this->titlePlural), $data);
        $data = str_replace('CRUD_column_name', strtolower($this->columnName), $data);

        return $data;
    }

    /**
     * @param $code
     * @param $file
     * @param $where
     */
    private function addToFile($code, $file, $where)
    {
        $code = $this->rename($code);

        $data = file_get_contents($file);

        if (!stripos($data, $code) && stripos($data, $where)) {
            $wherePos = stripos($data, $where) + strlen($where);
            if ($wherePos) {
                $data = substr_replace($data, $code, $wherePos, 0);
                file_put_contents($file, $data);
            }
        }
    }
}
