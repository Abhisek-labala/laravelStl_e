<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-container {
            text-align: center;
        }
        h1 {
            font-size: 48px;
            color: #666;
        }
        p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Session Expired......</h1>
        <p>Your session has expired.</p>
        <p>Please <a href="{{url('/')}}">login</a> again to continue.</p>
    </div>
</body>
</html>
