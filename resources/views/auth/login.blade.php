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

    <div class="login-left">

        <div class="logo-wrapper">

            <img src="{{ asset('images/logo.png') }}" class="mini-logo">

            <span class="logo-text">RakhaSport</span>

        </div>


        @if($errors->any())
            <div style="color:red; margin-bottom:10px;">
                {{ $errors->first() }}
            </div>
        @endif


        <form method="POST" action="{{ route('login') }}" class="login-form">

            @csrf

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn-submit">
                Login
            </button>

        </form>

    </div>


    <div class="login-right">

        <div class="white-curve"></div>

        <img src="{{ asset('images/Footbal.png') }}" class="player-img">

    </div>

</div>

</body>
</html>