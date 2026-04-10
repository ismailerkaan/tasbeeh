<!DOCTYPE html>
<html class="loading" lang="tr" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Tasbeeh App Admin Giriş">
    <meta name="keywords" content="tasbeeh, admin, login">
    <meta name="author" content="Tasbeeh App">
    <title>Admin Giriş</title>

    <link rel="apple-touch-icon" href="{{ asset('assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <style>
        .auth-page-bg {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f8ff 0%, #eef3ff 100%);
        }

        .auth-brand-logo {
            width: 88px;
            height: 88px;
            object-fit: contain;
        }
    </style>
</head>
<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <div class="app-content content auth-page-bg">
        <div class="content-wrapper">
            <div class="content-body">
                <div class="auth-wrapper auth-basic px-2">
                    <div class="auth-inner my-2">
                        <div class="card mb-0 shadow-sm border-0">
                            <div class="card-body">
                                <div class="text-center mb-2">
                                    <img src="{{ asset('assets/images/logos.png') }}" alt="Tasbeeh App Logo" class="auth-brand-logo mb-1">
                                    <h4 class="card-title mb-25">Admin Giriş</h4>
                                    <p class="card-text mb-0 text-muted">Tasbeeh App yönetim paneline erişim</p>
                                </div>

                                <form class="auth-login-form mt-2" action="{{ route('admin.login.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-1">
                                        <label class="form-label" for="email">E-posta</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-1">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label" for="password">Şifre</label>
                                        </div>
                                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                                            <label class="form-check-label" for="remember">Beni hatırla</label>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary w-100" type="submit">Giriş Yap</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('assets/js/core/app.js') }}"></script>
</body>
</html>
