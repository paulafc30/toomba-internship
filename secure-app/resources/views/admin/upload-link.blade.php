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
    <x-mail::message>
        # Hello {{ $name }}!

        A temporary link has been created for you to upload files.

        Click the button below to access the upload form:

        <x-mail::button :url="$uploadLink">
            Upload Files
        </x-mail::button>

        @if ($password)
        If prompted, this is your temporary password: **{{ $password }}**
        @endif

        This link will expire on {{ $expirationDate }}.

        Thanks,
        {{ config('app.name') }}
    </x-mail::message>
</body>

</html>