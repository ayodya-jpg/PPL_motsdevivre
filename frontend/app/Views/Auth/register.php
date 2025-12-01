<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .login-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        .btn-register { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-register:hover { background: #218838; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; }
        .link-login { text-align: center; margin-top: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center;">Register</h2>
        
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
            <?php if(session()->getFlashdata('errors_list')): ?>
                <ul style="color: red; font-size: 12px; padding-left: 15px;">
                    <?php foreach(session()->getFlashdata('errors_list') as $err): ?>
                        <li><?= is_array($err) ? implode(', ', $err) : $err ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>

        <form action="/auth/registerProcess" method="post">
            <div class="form-group">
                <label>Fullname</label>
                <input type="text" name="name" required value="<?= old('name') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?= old('email') ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-register">Register Now</button>
        </form>

        <div class="link-login">
            Have An Account? <a href="/auth">Login Here</a>
        </div>
    </div>
</body>
</html>