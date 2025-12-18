<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>New contact message</title>
</head>
<body>
  <h2>New contact message</h2>
  <p><strong>Name:</strong> {{ $msg->name }}</p>
  <p><strong>Email:</strong> {{ $msg->email }}</p>
  <p><strong>Phone:</strong> {{ $msg->phone ?? '—' }}</p>
  <p><strong>Message:</strong></p>
  <p>{{ nl2br(e($msg->message)) }}</p>

  <hr>
  <p>IP: {{ $msg->ip_address }}</p>
  <p>User Agent: {{ $msg->user_agent }}</p>
</body>
</html>
