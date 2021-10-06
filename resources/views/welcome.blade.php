<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ secure_asset('images/system/logo.svg') }}" sizes="any">
    <title>Retailer</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('css/generals.css') }}" rel="stylesheet">
    
</head>

<body class="antialiased">
    <div id="app">
        <app-component></app-component>
    </div>
<script src="{{ mix('js/app.js') }}"></script>
<script>
    localStorage.setItem('user','{!! Auth::user() !!}');
</script>
</body>
</html>