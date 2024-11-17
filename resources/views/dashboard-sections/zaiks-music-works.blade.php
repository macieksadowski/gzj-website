<!-- resources/views/zaiks-music-works.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAiKS Music Works Search</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Search ZAiKS Music Works</h1>
        
        <form id="demo-form" action="{{ route('zaiks-music-works.search') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="query">Search Query</label>
                <input type="text" class="form-control" id="query" name="query" required>
            </div>
            <button class="g-recaptcha" 
                data-sitekey="{{ config('services.recaptcha.site_key') }}" 
                data-callback='onSubmit' 
                data-action='submit'>Submit
            </button>
        </form>
    </div>

    <script>
        function onSubmit(token) {
          document.getElementById("demo-form").submit();
        }
      </script>

</body>
</html>