<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <title>{{ env('APP_NAME') }}</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}" type="text/css">
        <script src="{{ mix('/js/app.js') }}"></script>
    </head>
    <body>
        @inertia
    </body>
</html>
