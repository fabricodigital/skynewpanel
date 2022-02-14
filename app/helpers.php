<?php

use Illuminate\Support\Collection;

if(!function_exists('filter_direct_messages_count')) {
    function filter_direct_messages_count(Collection $messages) : int {
        return count($messages->filter(function ($val) {
            return $val->role_id == null;
        }));
    }
}

if(!function_exists('filter_help_messages_count')) {
    function filter_help_messages_count(Collection $messages) : int {
        return count($messages->filter(function ($val) {
            return $val->role_id != null;
        }));
    }
}

if(!function_exists('number_format_locale')) {
    function number_format_locale($number, $decimals = 2):string
    {
        if(Auth::check()) {
            setlocale(LC_ALL, Auth::user()->locale);
        }
        $locale = localeconv();
        if(Auth::check()) {
            setlocale(LC_ALL, "en");
        }
        return number_format($number,$decimals,
            $locale['decimal_point'],
            $locale['thousands_sep']);
    }
}

if(!function_exists('translate_labels_from_array')) {
    function translate_labels_from_array(array $labels)
    {
        $translated = [];
        foreach ($labels as $label) {
            $translated []= __($label);
        }

        return $translated;
    }
}

if(!function_exists('check_arrays_for_differences')) {
    function check_arrays_for_differences(array $array1, array $array2): bool
    {
        foreach ($array1 as $key => $val) {

            if(!isset($array2[$key])) {
                return true;
            }

            if(is_array($val)) {
                check_arrays_for_differences($val, $array2[$key]);
            }

            if($val != $array2[$key]) {
                return true;
            }
        }

        return false;
    }
}

if(!function_exists('get_latest_version')) {
    function get_latest_version() {
        $repoPath = config('main.repo_path');

        if(empty($repoPath)) {
            return '1.0.0';
        }

        if(config('app.env') != 'prod') {
            Cache::forget('app_version');
        }

        return Cache::rememberForever('app_version', function () use($repoPath) {
            if(config('app.env') == 'prod') {
                $branch = 'master';
            }else{
                $branch = 'develop';
            }

            $HEAD_hash = file_get_contents($repoPath . 'refs/heads/' . $branch); // or branch x

            $files = glob($repoPath . 'refs/tags/*');

            foreach($files as $file) {
                $contents = file_get_contents($file);

                if($HEAD_hash === $contents)
                {
                    return basename($file);
                }
            }

            foreach ($files as $key => $val) {
                $files[$key] = basename($val);
            }

            usort($files, 'version_compare');

            return basename(end($files));
        });
    }
}

if(!function_exists('eval_helper')) {
    function eval_helper($expression) {
        return eval('return ' . $expression . ';');
    }
}

if(!function_exists('handle_redirect_route_after_crud_action')) {
    function handle_redirect_route_after_crud_action(string $baseRouteString, $model) {
        if(\Illuminate\Support\Facades\Request::get('do_action_and_new')) {
            return route($baseRouteString . '.create');
        }

        return route($baseRouteString . '.edit', [$model]);
    }
}

if(!function_exists('format_input_name_with_dots')) {
    function format_input_name_with_dots($name) {
        return preg_replace(['/]\[/', '/\[/', '/]$/'], ['.', '.', ''], $name);
    }
}
