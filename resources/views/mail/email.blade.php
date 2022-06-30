<html>
    <head>
        <title>Welcome letter</title>
    </head>
    <body>
        <h1>Welcome, {{ $name }}</h1>
        <p>Click the link down below if you want verificate your email</p>
        <a href="localhost/verification?hash={{ $hash }}">Click</a>
    </body>
</html>
