<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Offline | Solar Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #fbbf24;
            --primary-dark: #d97706;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .illustration {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
        }

        .sun {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.4);
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }

        .cloud {
            position: absolute;
            bottom: 10%;
            right: -10%;
            width: 80px;
            height: 40px;
            background: #475569;
            border-radius: 20px;
            opacity: 0.8;
            animation: float 4s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-15px); }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #fff, var(--text-muted));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #000;
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(251, 191, 36, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(251, 191, 36, 0.4);
        }

        .status-badge {
            margin-top: 3rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.2);
            color: #fb7185;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .dot {
            width: 8px;
            height: 8px;
            background-color: #fb7185;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration">
            <div class="sun"></div>
            <div class="cloud"></div>
        </div>
        <h1>Connection Interrupted</h1>
        <p>The solar dashboard is having trouble reaching the database. This usually happens when your internet connection is unstable or disconnected.</p>
        
        <a href="javascript:window.location.reload()" class="btn">Try Reconnecting</a>

        <div class="status-badge">
            <div class="dot"></div>
            System Offline
        </div>
    </div>
</body>
</html>
