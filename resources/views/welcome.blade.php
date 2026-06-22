<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AU & Brothers | Abdul Shakoor Ahsanullah</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body, html {
            height: 100%;
            background-color: #f4f4f4;
        }

        .welcome-page {
            position: relative;
            background: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px; /* for mobile edges */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75); /* Darker overlay for better contrast */
            z-index: 1;
        }

        .content-box {
            position: relative;
            z-index: 2;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px 30px;
            border-radius: 15px;
            color: #fff;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .logo-text {
            font-size: clamp(16px, 2.5vw, 22px); /* smaller as requested */
            font-weight: 700;
            color: #4ade80; 
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .main-title {
            /* using clamp so it stays large on desktop but scales down on mobile without wrapping */
            font-size: clamp(24px, 5.5vw, 55px); 
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.2;
            white-space: nowrap; /* Ensures it stays on one line */
        }

        .sub-title {
            font-size: clamp(14px, 2.5vw, 20px);
            font-weight: 300;
            color: #ddd;
            margin-bottom: 30px;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap; /* allow wrapping on extremely small phones */
        }

        .btn {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            cursor: pointer;
            border: none;
            min-width: 150px;
        }

        .btn-primary {
            background-color: #22c55e;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #16a34a;
        }

        /* Mobile specific adjustments */
        @media (max-width: 480px) {
            .content-box {
                padding: 30px 15px;
            }
            .main-title {
                /* If screen is extremely small, allow normal wrapping to prevent overflow, but clamp keeps it on one line as much as possible */
                white-space: normal; 
                word-wrap: break-word;
            }
        }
    </style>
</head>
<body>

    <div class="welcome-page">
        <div class="overlay"></div>
        <div class="content-box">
            <div class="logo-text">AU & Brothers</div>
            <h1 class="main-title">Abdul Shakoor Ahsanullah</h1>
            <p class="sub-title">Fresh Vegetables Commission Agent</p>
            
            <div class="btn-container">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-primary">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>

</body>
</html>