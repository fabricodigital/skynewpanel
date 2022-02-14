<?php

use Illuminate\Database\Seeder;

class UpdateAdminModelsNamespaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::statement('UPDATE media SET model_type = REPLACE(model_type, "App\\\Models\\\", "App\\\Models\\\Admin\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\Admin\\\\\\\%"');

            DB::statement('UPDATE messenger_messages SET receiver_model = REPLACE(receiver_model, "App\\\Models\\\", "App\\\Models\\\Admin\\\") WHERE receiver_model NOT LIKE "App\\\\\\\Models\\\\\\\Admin\\\\\\\%"');

            DB::statement('UPDATE model_has_permissions SET model_type = REPLACE(model_type, "App\\\Models\\\", "App\\\Models\\\Admin\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\Admin\\\\\\\%"');

            DB::statement('UPDATE model_has_roles SET model_type = REPLACE(model_type, "App\\\Models\\\", "App\\\Models\\\Admin\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\Admin\\\\\\\%"');

            DB::statement('UPDATE revisions SET model_type = REPLACE(model_type, "App\\\Models\\\", "App\\\Models\\\Admin\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\Admin\\\\\\\%"');
        });
    }
}
