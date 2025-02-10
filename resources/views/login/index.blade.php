<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lina - Class Schedule Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1a1a1a;
            --secondary-dark: #2d2d2d;
            --accent-color: #0066cc;
            --text-color: #ffffff;
            --input-bg: rgba(255, 255, 255, 0.1);
            --placeholder-color: #bbbbbb;
        }

        body {
            background: var(--primary-dark);
            color: var(--text-color);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }


        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-container {
            background: var(--secondary-dark);
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #0066cc, #5856d6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-tabs {
            border: none;
            margin-bottom: 2rem;
        }

        .nav-tabs .nav-link {
            color: #888;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--text-color);
            background: none;
            border-bottom: 2px solid var(--accent-color);
        }

        .form-control {
            background: var(--input-bg);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: var(--text-color);
            margin-bottom: 1rem;
        }

        .form-control:focus {
            background: var(--input-bg);
            box-shadow: 0 0 0 2px var(--accent-color);
            color: var(--text-color);
        }

        /* Placeholder styles for different browsers */
        .form-control::placeholder {
            color: var(--placeholder-color);
            opacity: 1;
        }

        .form-control:-ms-input-placeholder {
            color: var(--placeholder-color);
        }

        .form-control::-ms-input-placeholder {
            color: var(--placeholder-color);
        }

        .btn-primary {
            background: var(--accent-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 500;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #0055aa;
            transform: translateY(-1px);
        }

        .form-text {
            color: #888;
            font-size: 0.875rem;
            text-align: center;
            margin-top: 1rem;
        }

        .form-text a {
            color: var(--accent-color);
            text-decoration: none;
        }
    </style>

    @section('additional-css')

        <link rel="stylesheet" href="{{ asset('resources/css/login.css') }}">

    @endsection
</head>
<body>
    <div class="main-wrapper">
        <div class="auth-container">
            <div class="logo">Lina</div>

            <ul class="nav nav-tabs" id="authTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Register</button>
                </li>
            </ul>

            <div class="tab-content" id="authTabContent">
                <div class="tab-pane fade show active" id="login" role="tabpanel">
                    <form>
                        <input type="email" class="form-control" placeholder="Email address">
                        <input type="password" class="form-control" placeholder="Password">
                        <button type="submit" class="btn btn-primary">Log In</button>
                        <div class="form-text">
                            Forgot your password? <a href="#">Reset it here</a>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="register" role="tabpanel">
                    <form>
                        <input type="text" class="form-control" placeholder="Full name">
                        <input type="email" class="form-control" placeholder="Email address">
                        <input type="password" class="form-control" placeholder="Password">
                        <input type="password" class="form-control" placeholder="Confirm password">
                        <button type="submit" class="btn btn-primary">Create Account</button>
                        <div class="form-text">
                            By registering, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
