<!DOCTYPE html>
<html>
<head>
    <title>New post published - {{ $details['post_title'] }}</title>
</head>
<body>
    <h1>Post: {{ $details['post_title'] }} (ID: {{ $details['post_id'] }})</h1>
    <p><b>Description:</b></p>
    <p>{{ $details['body'] }}</p>

    <p>Thank you</p>
</body>
</html>
