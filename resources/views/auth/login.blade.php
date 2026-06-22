<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> AU & Brothers | Login </title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            background: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=80');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            position: absolute;
            z-index: 1;
        }
        .wrapper {
            position: relative;
            z-index: 2;
            max-width: 430px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .wrapper .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #22c55e;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .wrapper h2 {
            position: relative;
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .wrapper form .input-box {
            height: 52px;
            margin: 18px 0;
        }

        form .input-box input {
            height: 100%;
            width: 100%;
            outline: none;
            padding: 0 15px;
            font-size: 16px;
            font-weight: 400;
            color: #333;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-color: #22c55e;
        }

        .input-box.button input {
            color: #fff;
            letter-spacing: 1px;
            border: none;
            background: #22c55e;
            cursor: pointer;
            font-weight: 600;
            font-size: 18px;
            border-radius: 6px;
        }

        .input-box.button input:hover {
            background: #16a34a;
        }

        .alert {
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .forgot-pass {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-pass a {
            color: #22c55e;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-pass a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="logo-text">AU & Brothers</div>
        <h2>Account Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="input-box">
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus autocomplete="username" />
            </div>
            <div class="input-box">
                <input id="password" type="password" name="password" placeholder="Password" required autocomplete="current-password" />
            </div>
            <div class="forgot-pass">
                <a href="#">Forgot Password?</a>
            </div>

            <div class="input-box button">
                <input type="Submit" value="Login">
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
</body>

</html>