<!DOCTYPE html>
<html>
<head>
    <title>Login Toko Online</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .login-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        .btn-login { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-login:hover { background: #0056b3; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; text-align: center;}
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center;">Login</h2>
        
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="/auth/loginProcess" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
    </div>
</body>
</html>