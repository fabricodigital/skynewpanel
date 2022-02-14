<html lang="{{ app()->getLocale() }}">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>

    <body style="font-family: Avenir, Helvetica, sans-serif;">

        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center" bgcolor="#ffffff" 
            style="font-family: Avenir, Helvetica, sans-serif;">
            @include('emails.partials._header')
            
            <tr>
                <td style="text-align: center;">
                    <table cellspacing="0" cellpadding="0" border="0" width="800" align="center" bgcolor="#ffffff" 
                        style="font-family: Avenir, Helvetica, sans-serif;">
                        <tr>
                            <td style="text-align: left; padding: 15px 0;">
                                @yield('content')
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            @include('emails.partials._footer')
        </table>
        
    </body>

</html>