<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from admin.pixelstrap.com/tivo/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 07 Feb 2023 03:15:42 GMT -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="tivo admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Tivo admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">

    <link rel="icon" href="{{ asset('/assets/images/favicon/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('/assets/images/favicon/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/color-1.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/responsive.css') }}">
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"> </div>
        <div class="dot"></div>
    </div>
    <!-- Loader ends-->
    <!-- login page start-->
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-xl-6"><img class="bg-img-cover bg-center"
                    src="{{ asset('/assets/images/register-bg.jpg') }}" alt="looginpage"></div>
            <div class="col-xl-6 p-0">
                <div class="login-card">
                    <div>
                        <div class="login-main">
                            <form class="theme-form">
                                <h4 class="text-center">Create your account</h4>
                                <p class="text-center">Enter your personal details to create account</p>
                                <hr class="mt-0 mb-2">
                                <div class="mb-4 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Register As</span>
                                        <select class="form-select" id="register_type" name="register_type">
                                            <option value="Student">Student</option>
                                            <option value="Peer">Peer</option>
                                            <option value="Supervisor">Supervisor</option>
                                        </select>
                                    </div>
                                </div>
                                <h5 class="text-primary">Personal Information</h5>
                                <hr class="mt-0 mb-2">
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">First Name</span>
                                        <input class="form-control" name="FirstName" type="text" required=""
                                        placeholder="Enter Firstname">
                                    </div>
                                </div>
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Middle Name</span>
                                        <input class="form-control" name="MiddleName" type="text" required=""
                                        placeholder="Enter MiddleName">
                                    </div>
                                </div>
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Last Name</span>
                                        <input class="form-control" name="LastName" type="text" required=""
                                        placeholder="Enter LastName">
                                    </div>
                                </div>
                                <div class="mb-4 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Contact No.</span>
                                        <input class="form-control" name="ContactNo" type="text" required=""
                                        placeholder="Enter Contact No.">
                                    </div>
                                </div>
                                <h5 class="text-primary">User Credentials</h5>
                                <hr class="mt-0 mb-2">
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Username</span>
                                        <input class="form-control" name="username" type="text" required=""
                                        placeholder="Enter Username">
                                    </div>
                                </div>
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Password</span>
                                        <input class="form-control" name="password" type="password" required=""
                                        placeholder="Enter Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block w-100 mt-3"
                                        type="submit">Register</button>
                                </div>
                                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2"
                                        href="/login">Sign in</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('/assets/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('/assets/js/icons/feather-icon/feather.min.js') }}"></script>
        <script src="{{ asset('/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
        <script src="{{ asset('/assets/js/config.js') }}"></script>
        <script src="{{ asset('/assets/js/script.js') }}"></script>
        <script>
            function showPassword() {
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        </script>
    </div>
</body>

</html>
