<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="icon" href="/favicon.ico">
        <title>{{ env('APP_NAME') }}</title>
        <link rel="stylesheet" href="{{ url(mix('/css/app.css')) }}" type="text/css">
        <script src="{{ url(mix('/js/app.js')) }}" defer></script>
    </head>
    <body>
        @inertia
    </body>
</html>
