<html>
    <head></head>
    <body>
    <h2>{{ $title }}</h2>
    <p>{!! nl2br(htmlspecialchars($content)) !!}</p>
    </body>
</html>