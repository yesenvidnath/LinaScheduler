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
                <form id="loginForm">
                    <div class="alert d-none" id="loginAlert" role="alert"></div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="email" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Log In</button>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="tab-pane fade" id="register" role="tabpanel">
                <form id="registerForm">
                    <div class="alert d-none" id="registerAlert" role="alert"></div>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" class="form-control" name="First_Name" placeholder="First name" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="Last_Name" placeholder="Last name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" autocomplete="email" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" class="form-control" name="UD_ID" placeholder="Designation ID" required>
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control" name="Honorifics_ID" placeholder="Honorific ID" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="User_Discrption" placeholder="Description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <select class="form-control" name="Status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                            <option value="1*">Suspended</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password" required>
                    </div>
                    <div class="mb-4">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('custom-js')
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
