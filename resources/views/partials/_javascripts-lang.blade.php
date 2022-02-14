<script>
    window._t = function (string, arguments) {
        var strings = JSON.parse({!! file_exists(resource_path('/lang/' . app()->getLocale() . '.json')) ? json_encode(file_get_contents(resource_path('/lang/' . app()->getLocale() . '.json'))) : json_encode([]) !!});
        arguments = arguments || {};

        if(strings.hasOwnProperty(string)) {
            string = strings[string];
        }

        for (var arg in arguments) {
            string = string.replace(':' + arg, arguments[arg]);
        }

        return string;
    };
</script>