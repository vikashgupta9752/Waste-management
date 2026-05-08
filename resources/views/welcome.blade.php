<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EcoWaste - Smart Waste Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body { background: url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed; background-size: cover; color: white; }
            .overlay { background-color: rgba(44, 62, 80, 0.8); position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
            .content { position: relative; z-index: 1; height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; }
            .btn-custom { padding: 15px 40px; font-size: 1.2rem; border-radius: 30px; }
        </style>
    </head>
    <body>
        <div class="overlay"></div>
        <div class="content container">
            <h1 class="display-3 fw-bold mb-4"><i class="fa-solid fa-leaf text-success"></i> EcoWaste</h1>
            <p class="lead fs-3 mb-5">Smart Waste Collection, Transportation, and Segregation System</p>
            
            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-success btn-custom mx-2">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-custom mx-2">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-custom mx-2">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
            
        </div>
    </body>
</html>
