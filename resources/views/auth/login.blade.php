<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RakhaSport</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="login-card">
    <div class="login-image-section">
        <div class="logo-top-left">
            <img src="{{ asset('images/logo.png') }}" class="mini-logo">
            <span class="logo-text">RakhaSport</span>
        </div>
        
        <div class="image-container">
            <div class="image-circle">
                <img src="{{ asset('images/Footbal.png') }}" class="player-img">
            </div>
        </div>
        
        <p class="copyright">Copyright © 2026, RakhaSport. All rights reserved.</p>
    </div>

    <div class="login-form-section">
        <div class="white-curve"></div>
        
        <div class="form-wrapper">
            <div class="tabs">
                <span class="tab active">Log In</span>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="example@email.com" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="********" required>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
                
            </form>
        </div>
    </div>
</div>

</body>
</html>