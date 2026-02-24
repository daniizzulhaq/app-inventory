<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Berkah Sedati</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0206f0 0%, #202de9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 420px;
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #1e77ec, #248cec);
            padding: 32px 32px 24px;
            text-align: center;
        }

        .login-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,.15);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .login-body { padding: 32px; background: #fff; }

        .form-control:focus { border-color: #1d20d6; box-shadow: 0 0 0 .2rem rgba(26,107,58,.25); }

        .btn-login {
            background: linear-gradient(135deg, #1d20d6, #1d20d6);
            border: none;
            color: #fff;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
        }

        .btn-login:hover { background: linear-gradient(135deg, #2d7cf1, #1856ff); color: #fff; }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-box-seam text-white fs-2"></i>
            </div>
            <h4 class="text-white fw-bold mb-1">Berkah Sedati</h4>
            <p class="text-white-50 mb-0 small">Sistem Inventory Gudang Sembako</p>
        </div>
        <div class="login-body">
            <h5 class="fw-semibold mb-1">Selamat Datang!</h5>
            <p class="text-muted small mb-4">Silakan masuk untuk melanjutkan</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="admin@berkahsedati.com" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>
            <p class="text-center text-muted small mt-4 mb-0">
                &copy; {{ date('Y') }} Gudang Sembako Berkah Sedati
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
