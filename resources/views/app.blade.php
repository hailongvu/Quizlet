<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title" content="Let Learn">
    <meta name="description" content="Let Learn">
    <meta name="author" content="Let Learn Team">
    <meta name="keywords" content="Let Learn">
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <title>Let Learn</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome Icons -->
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" />
    <!-- CKEditor 5 -->
    <script src="/editor/ckeditor.js"></script>
</head>

<body>
    <div id="app" class="g-sidenav-show"></div>
</body>

</html>
