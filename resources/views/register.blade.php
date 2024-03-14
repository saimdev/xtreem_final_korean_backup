<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>HONOR - Tachyon DEMO</title>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Login</h3>
                </div>
                <div class="card-body">
                    <form action="signup" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" id="username" name='id' placeholder="ID">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name='password' placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        @if(session()->has('message'))
                            <script>
                                alert("{{ session('message') }}");
                            </script>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
