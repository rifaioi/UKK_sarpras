<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-main: #0b0718;
            --card-bg: rgba(20, 10, 40, 0.75);
            --border-soft: rgba(180, 140, 255, 0.18);
            --accent: #9b7cff;
            --accent-hover: #7f5bff;
            --text-soft: rgba(255,255,255,0.65);
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 20% 25%, rgba(155,124,255,.15), transparent 45%),
                radial-gradient(circle at 80% 75%, rgba(120,80,255,.12), transparent 45%),
                var(--bg-main);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #fff;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.6rem 2.3rem;
            border-radius: 22px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-soft);
            box-shadow: 0 30px 60px rgba(0,0,0,.55);
        }

        .brand {
            font-weight: 700;
            font-size: 1.7rem;
            letter-spacing: .4px;
        }

        .subtitle {
            color: var(--text-soft);
            font-size: .95rem;
        }

        .form-label {
            font-size: .85rem;
            color: var(--text-soft);
            margin-bottom: .35rem;
        }

        .form-control {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(180,140,255,.2);
            border-radius: 12px;
            color: #fff !important;
            padding: .9rem .95rem;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,.35);
        }

        .form-control:focus {
            background: rgba(255,255,255,.07);
            border-color: var(--accent);
            box-shadow: 0 0 0 .25rem rgba(155,124,255,.18);
            color: #fff;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #6f4cff);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: .7rem 1rem;
            transition: .25s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(155,124,255,.45);
        }

        /* ===== PASSWORD ICON CENTER FIX ===== */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            height: 100%;
            display: flex;
            align-items: center;
            background: none;
            border: none;
            color: rgba(255,255,255,.6);
            cursor: pointer;
        }

        .password-toggle:hover {
            color: #fff;
        }

        .alert-danger {
            background: rgba(220,53,69,.12);
            border: 1px solid rgba(220,53,69,.25);
            color: #ff8b8b;
            border-radius: 12px;
            font-size: .9rem;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .footer-text {
            margin-top: 1.8rem;
            font-size: .8rem;
            color: rgba(255,255,255,.45);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand mb-1">Sarpras App</div>
        <div class="subtitle">Sistem Informasi Sarana & Prasarana</div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert" style="background: rgba(40,167,69,.12); border: 1px solid rgba(40,167,69,.25); color: #72f58e;">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/login') ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
        </div>

        <div class="mb-4 password-wrapper">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control pe-5" id="passwordInput" placeholder="Masukkan password" required>
            <button type="button" class="password-toggle" id="togglePassword">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
        </div>

        <button class="btn btn-primary w-100 py-2 fs-6" type="submit">Masuk</button>
    </form>

    <div class="text-center footer-text">
        &copy; <?= date('Y') ?> UKK Sarpras
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>
