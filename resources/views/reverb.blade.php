<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Reverb Test</title>

    <style>
        .notif {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            border-radius: 6px;
            background: #f8f8f8;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<h1>Realtime Notification Test</h1>

<ul id="notif-list"></ul>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        Echo.private(`user.notifications.1`)
            .listen('.notification.created', (e) => {
                console.log('Notification Received:', e);
            });

        Echo.channel(`user.notifications.global`)
            .listen('.notification.created', (e) => {
                console.log('Notification Received:', e);
            });
    });
</script>

</body>
</html>
