@extends('auth.layouts.app')
@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h3 class="text-primary">Class Scheduling System</h3>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="/assets/images/profile-img.png" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="{{ route('login') }}" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('/assets/images/logo.png') }}" alt=""
                                                class="rounded-circle" height="80">
                                        </span>
                                    </div>
                                </a>

                                <a href="{{ route('login') }}" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('/assets/images/logo.png') }}" alt="" height="50">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">
                                            <span class="lead">
                                                Username
                                            </span>
                                        </label>
                                        <input type="username" name="username"
                                            class="form-control form-control-lg rounded-0 {{ $errors->first() ? 'is-invalid' : '' }}"
                                            id="username" placeholder="Enter username">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            <span class="lead">
                                                Password
                                            </span>
                                        </label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" name="password"
                                                class="form-control form-control-lg  rounded-0 {{ $errors->first() ? 'is-invalid' : '' }}"
                                                placeholder="Enter password" aria-label="Password"
                                                aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light text-uppercase"
                                            type="submit">
                                            <span class="lead">
                                                Log In
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end account-pages -->
@endsection
