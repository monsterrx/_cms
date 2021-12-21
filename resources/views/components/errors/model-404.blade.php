<html>
<head>
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato', sans-serif;
        }

        a {
            color: #B0BEC5;
            font-weight: bold;
            text-decoration: none;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <img src="{{ asset('images/404.png') }}" width="150px" alt="monster-404">
        <div class="title">404: The data you were looking for wasn't found.</div>
        <a href="{{ route('home') }}">&lt;&lt; Return to Home</a>
    </div>
</div>
</body>
</html>
