<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Logging out...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
        }
        .loading {
            text-align: center;
        }
        .spinner {
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid #fbbf24;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        // ✅ CLEAR sessionStorage
        sessionStorage.clear();
        
        // ✅ REDIRECT KE LOGIN SETELAH 500ms
        setTimeout(function() {
            window.location.href = '/auth';
        }, 500);
    </script>
</head>
<body>
    <div class="loading">
        <div class="spinner"></div>
        <p>Logging out...</p>
    </div>
</body>
</html>
