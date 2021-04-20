<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>General Search Results - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('search.css') }}">
    @livewireStyles
</head>

<body>
    <div class="container">
        @livewire('gen-lib.search')
    </div>

    <script src="{{ asset('jquery-1.10.2.min.js') }}"></script>
    <script src="{{ asset('bootstrap.min.js') }}"></script>
    @livewireScripts
    <script type="text/javascript">
        window.addEventListener('book_download', event => {
            open(event.detail.url)
        })
    </script>
</body>

</html>
