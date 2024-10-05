<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .auth-container {
            margin-top: 50px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .divider-custom {
            margin: 20px 0;
        }
        .divider-custom-line {
            height: 2px;
            width: 100%;
            background-color: #ddd;
        }
        .divider-custom-icon {
            text-align: center;
            margin: -12px 0;
        }
        #flash-message {
            position: absolute;
            top: 70%; 
            left: 50%; 
            transform: translate(-50%, -50%);
            width: 80%; 
            max-width: 600px;
            padding: 15px;
            text-align: center;
            z-index: 9999;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }
        #flash-message.fade-out {
            opacity: 0;
        }
    </style>
</head>
<body>

<div class="container">
    @if(session('error'))
    <div class="alert alert-danger" id="flash-message">
        {{ session('error') }}
    </div>
    @endif
    @if(session('message'))
        <div class="alert alert-success" id="flash-message">
            {{ session('message') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-lg-6 auth-container">
            <h2 class="text-center text-secondary text-uppercase mb-4" id="authModalLabel">Login</h2>
            <div class="divider-custom">
                <div class="divider-custom-icon"><i class="fas fa-user"></i></div>
                <div class="divider-custom-line"></div>
            </div>

            <div id="loginForm">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="loginEmail" name="login_email" required>
                        <span id="loginEmailError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="login_password" value="@Password1" required>
                        <span id="loginPasswordError" class="text-danger"></span>
                    </div>
                    <button type="submit" id="loginSubmitBtn" class="btn btn-primary" disabled>Login</button>
                    <p class="mt-3">Belum punya akun? <a href="#" id="toggleToRegister">Daftar di sini</a></p>
                </form>
            </div>
            
            <!-- Register Form -->
            <div id="registerForm" style="display: none;">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-1">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <span id="nameError" class="text-danger"></span>
                    </div>
                    <div class="mb-1">
                        <label for="registerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="registerEmail" name="register_email" required>
                        <span id="registerEmailError" class="text-danger"></span>
                    </div>
                    <div class="mb-1">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="5" required style="resize: none;"></textarea>
                        <span id="alamatError" class="text-danger"></span>
                    </div>
                    <div class="mb-1">
                        <label for="nomorTelpon" class="form-label">Nomor Telpon</label>
                        <input type="number" class="form-control" id="nomorTelpon" name="phone_number" required>
                        <span id="nomorTelponError" class="text-danger"></span>
                    </div>
                    <div class="mb-1">
                        <label for="nomorSim" class="form-label">Nomor SIM</label>
                        <input type="text" class="form-control" id="nomorSim" name="sim_number" required>
                        <span id="nomorSimError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="registerPassword" name="register_password" required>
                        <span id="registerPasswordError" class="text-danger"></span>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="registerPasswordConfirmation" name="register_password_confirmation" required>
                        <span id="confirmPasswordError" class="text-danger"></span>
                    </div>
                    <button type="submit" id="registerSubmitBtn" class="btn btn-primary" disabled>Daftar</button>
                    <p class="mt-3">Sudah punya akun? <a href="#" id="toggleToLogin">Login di sini</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function () {

        $(document).ready(function() {
            const flashMessage = $('#flash-message');
            if (flashMessage.length) {
                setTimeout(() => {
                    flashMessage.addClass('fade-out');
                    setTimeout(() => flashMessage.remove(), 500);
                }, 5000);
            }
        });

        function validateEmail(email) {
            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return regex.test(email);
        }

        function validatePassword(password) {
            const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/; 
            return regex.test(password);
        }

        function validateConfirmPassword(password, confirmPassword) {
            return password === confirmPassword;
        }

        function validateName(name) {
            return name.length >= 3; 
        }

        function validateAddress(address) {
            return address.length >= 10; 
        }

        function validatePhoneNumber(phone) {
            const regex = /^62\d{6,}$/; 
            return regex.test(phone);
        }

        function validateSimNumber(sim) {
            const regex = /^\d{4}-\d{4}-\d{6}$/;
            return regex.test(sim);
        }

        function checkAllValidReg() {
            const isValid =
                validateName($('#name').val()) &&
                validateAddress($('#alamat').val()) &&
                validatePhoneNumber($('#nomorTelpon').val()) &&
                validateSimNumber($('#nomorSim').val()) &&
                validateEmail($('#registerEmail').val()) &&
                validatePassword($('#registerPassword').val()) &&
                validateConfirmPassword($('#registerPassword').val(), $('#registerPasswordConfirmation').val());

            $('#registerSubmitBtn').prop('disabled', !isValid);
        }

        function checkAllValidLog() {
            const isValid =
                validateEmail($('#loginEmail').val()) &&
                validatePassword($('#loginPassword').val());

            $('#loginSubmitBtn').prop('disabled', !isValid);
        }

        $('input, textarea').on('input', function () {
            const errorField = $(this).attr('id') + 'Error';
            $('#' + errorField).text('');
            $(this).css('border-color', '');
            checkAllValidReg();
            checkAllValidLog();
        });

        $('#registerForm').hide();
        $('#toggleToRegister').on('click', function (e) {
            e.preventDefault();
            $('#loginForm').hide();
            $('#registerForm').show();
            $('#authModalLabel').text('Register');
        });
        $('#toggleToLogin').on('click', function (e) {
            e.preventDefault();
            $('#registerForm').hide();
            $('#loginForm').show();
            $('#authModalLabel').text('Login');
        });


        $('#loginEmail').on('input', function () {
            const email = $(this).val();
            if (!validateEmail(email)) {
                $('#loginEmailError').text('Email tidak valid.');
                $(this).css('border-color', 'red');
            }
        });

        $('#loginPassword').on('input', function () {
            const password = $(this).val();
            if (!validatePassword(password)) {
                $('#loginPasswordError').text('Password harus minimal 6 karakter, mengandung 1 huruf besar, 1 angka, dan 1 simbol.');
                $(this).css('border-color', 'red');
            } else {
                $('#loginPasswordError').text('');
                $(this).css('border-color', ''); 
            }
        });

        $('#registerEmail').on('input', function () {
            const email = $(this).val();
            if (!validateEmail(email)) {
                $('#registerEmailError').text('Email tidak valid.');
                $(this).css('border-color', 'red');
            }
            checkAllValidReg();
            checkAllValidLog();
        });

        $('#registerPassword, #registerPasswordConfirmation').on('input', function () {
            const password = $('#registerPassword').val();
            const confirmPassword = $('#registerPasswordConfirmation').val();
            if (!validatePassword(password)) {
                $('#registerPasswordError').text('Password harus minimal 6 karakter, mengandung 1 huruf besar, 1 angka, dan 1 simbol.');
                $('#registerPassword').css('border-color', 'red');
            } else {
                $('#registerPasswordError').text('');
                $('#registerPassword').css('border-color', '');
            }

            if (!validateConfirmPassword(password, confirmPassword)) {
                $('#confirmPasswordError').text('Konfirmasi password tidak cocok.');
                $('#registerPasswordConfirmation').css('border-color', 'red');
            } else {
                $('#confirmPasswordError').text('');
                $('#registerPasswordConfirmation').css('border-color', '');
            }
            
            checkAllValidReg();
            checkAllValidLog();
        });

        $('#name').on('input', function () {
            const name = $(this).val();
            if (!validateName(name)) {
                $('#nameError').text('Nama harus minimal 3 karakter.');
                $(this).css('border-color', 'red');
            } else {
                $('#nameError').text('');
                $(this).css('border-color', ''); 
            }
            checkAllValidReg();
            checkAllValidLog();
        });

        $('#alamat').on('input', function () {
            const address = $(this).val();
            if (!validateAddress(address)) {
                $('#alamatError').text('Alamat harus minimal 10 karakter.');
                $(this).css('border-color', 'red');
            } else {
                $('#alamatError').text(''); 
                $(this).css('border-color', ''); 
            }
            checkAllValidReg();
            checkAllValidLog();
        });

        $('#nomorTelpon').on('input', function () {
            const phone = $(this).val();
            if (!validatePhoneNumber(phone)) {
                $('#nomorTelponError').text('Nomor Telepon harus diawali dengan 62 dan minimal 8 digit.');
                $(this).css('border-color', 'red');
            } else {
                $('#nomorTelponError').text(''); 
                $(this).css('border-color', '');
            }
            checkAllValidReg();
            checkAllValidLog();
        });

        $('#nomorSim').on('input', function () {
            const sim = $(this).val();
            if (!validateSimNumber(sim)) {
                $('#nomorSimError').text('Nomor SIM harus angka yang berformat 0000-0000-000000.');
                $(this).css('border-color', 'red');
            } else {
                $('#nomorSimError').text(''); 
                $(this).css('border-color', '');
            }
            checkAllValidReg();
            checkAllValidLog();
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
