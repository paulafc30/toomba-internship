<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your File Upload Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container py-5">
        <p>Hello <strong>{{ $recipientName }}</strong>,</p>

        <p>Here's your secure file upload link:</p>

        <p>
            <a href="{{ $uploadLink }}" class="text-blue-500" target="_blank">{{ $uploadLink }}</a>
        </p>

        <p>
            This link will expire on 
            <strong>
                {{ $temporaryLink->expires_at 
                    ? (new DateTime($temporaryLink->expires_at, new DateTimeZone('Europe/Madrid')))->format('Y-m-d H:i:s') 
                    : 'N/A' }}
            </strong>.
        </p>

        <p>Thank you!</p>
    </div>
</body>

</html>
