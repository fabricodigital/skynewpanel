<?php

use Illuminate\Database\Seeder;

class UpdateModelsNamespaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::statement('UPDATE media SET model_type = REPLACE(model_type, "App\\\", "App\\\Models\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\%"');

            DB::statement('UPDATE messenger_messages SET receiver_model = REPLACE(receiver_model, "App\\\", "App\\\Models\\\") WHERE receiver_model NOT LIKE "App\\\\\\\Models\\\\\\\%"');

            DB::statement('UPDATE model_has_permissions SET model_type = REPLACE(model_type, "App\\\", "App\\\Models\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\%"');

            DB::statement('UPDATE model_has_roles SET model_type = REPLACE(model_type, "App\\\", "App\\\Models\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\%"');

            DB::statement('UPDATE revisions SET model_type = REPLACE(model_type, "App\\\", "App\\\Models\\\") WHERE model_type NOT LIKE "App\\\\\\\Models\\\\\\\%"');
        });
    }
}
