@extends('main.app')

@section("title", "Login")

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="auth-container">
        <div class="logo">
            <h1>Lina</h1>
            <p>Class Schedule Manager</p>
        </div>

        <ul class="nav nav-tabs" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Register</button>
            </li>
        </ul>

        <div class="tab-content" id="authTabsContent">
            <!-- Login Form -->
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <form>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Log In</button>
                    <div class="text-center">
                        <a href="#" class="form-text text-decoration-none">Forgot password?</a>
                    </div>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="tab-pane fade" id="register" role="tabpanel">
                <form>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('custom-js')

@endsection
