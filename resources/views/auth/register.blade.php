<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}">
</head>
<body>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <h1 class="auth-title">Sign Up</h1>
                    <p class="auth-subtitle mb-4">Daftar sebagai pasien baru.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-xl @error('name') is-invalid @enderror"
                                   placeholder="Nama Lengkap"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus
                                   autocomplete="name">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="email"
                                   name="email"
                                   class="form-control form-control-xl @error('email') is-invalid @enderror"
                                   placeholder="Email"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="username">
                            <div class="form-control-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text"
                                   name="phone"
                                   class="form-control form-control-xl @error('phone') is-invalid @enderror"
                                   placeholder="No. HP (08xxx)"
                                   value="{{ old('phone') }}"
                                   autocomplete="tel">
                            <div class="form-control-icon">
                                <i class="bi bi-phone"></i>
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password"
                                   name="password"
                                   class="form-control form-control-xl @error('password') is-invalid @enderror"
                                   placeholder="Password"
                                   required
                                   autocomplete="new-password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control form-control-xl"
                                   placeholder="Confirm Password"
                                   required
                                   autocomplete="new-password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-4">Sign Up</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="font-bold">Log in</a>.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
