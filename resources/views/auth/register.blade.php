<!DOCTYPE html>
<html lang="en">
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark-mode');
    }
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pax Republica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
    <div class="container mt-3 text-end">
        <button class="btn btn-outline-dark theme-toggle-button" id="theme-toggle-button">
            <i class="bi bi-moon-stars-fill"></i>
            <i class="bi bi-sun-fill"></i>
        </button>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header text-white text-center py-3" style="background: linear-gradient(135deg, #9d4edd, #ff8c42);">
                        <h4 class="mb-0"><i class="bi bi-stars me-2"></i>Pax Republica</h4>
                    </div>
                    <div class="card-body bg-white p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                                </div>

                                <div class="col-md-6">
                                    <label for="account_name" class="form-label">Account Name (8 chars max)</label>
                                    <input id="account_name" type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ old('account_name') }}" required maxlength="8">
                                    <small class="text-muted">Alphanumeric characters only</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select id="timezone" class="form-select @error('timezone') is-invalid @enderror" name="timezone" required>
                                        <option value="" disabled selected>Select your timezone</option>
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}" {{ old('timezone') == $timezone ? 'selected' : '' }}>{{ $timezone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                </div>

                                <div class="col-md-6">
                                    <label for="password-confirm" class="form-label">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Optional Profile Information</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="real_name" class="form-label">Real Name</label>
                                    <input id="real_name" type="text" class="form-control @error('real_name') is-invalid @enderror" name="real_name" value="{{ old('real_name') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="sex" class="form-label">Sex</label>
                                    <select id="sex" class="form-select @error('sex') is-invalid @enderror" name="sex">
                                        <option value="" selected>Prefer not to say</option>
                                        <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input id="age" type="number" class="form-control @error('age') is-invalid @enderror" name="age" value="{{ old('age') }}" min="13">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="profile" class="form-label">Profile</label>
                                <textarea id="profile" class="form-control @error('profile') is-invalid @enderror" name="profile" rows="4">{{ old('profile') }}</textarea>
                                <small class="text-muted">Tell us a bit about yourself</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn text-white fw-bold w-100" style="background: linear-gradient(135deg, #9d4edd, #ff8c42)">Register</button>
                            </div>

                            <div class="mt-3 text-center">
                                <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const themeToggleButton = document.getElementById('theme-toggle-button');
        const htmlElement = document.documentElement;

        // Function to apply the theme
        const applyTheme = (theme) => {
            if (theme === 'dark') {
                htmlElement.classList.add('dark-mode');
            } else {
                htmlElement.classList.remove('dark-mode');
            }
        };

        // Toggle theme on button click
        themeToggleButton.addEventListener('click', () => {
            const currentTheme = htmlElement.classList.contains('dark-mode') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme); // Save preference
        });
    </script>
</body>
</html>
