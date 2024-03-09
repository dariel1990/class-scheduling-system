@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Edit Profile</h2>
                    </div>
                    <div class="pull-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}"><i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Edit Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-12 col-lg-12 card">
                    {!! Form::model($userProfile, ['method' => 'PUT','route' => ['update.profile', $userProfile->id]]) !!}
                        <div class="card-body">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <span><i class="fa fa-check"></i></span>{{ $message }}
                                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="row">
                                <h5 class="text-primary mt-3">User Credentials</h5>
                                <hr class="mt-0 mb-2">
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Username</span>
                                        {!! Form::text('username', null, array('placeholder' => 'username','class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Email</span>
                                        {!! Form::text('email', null, array('placeholder' => 'email','class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="mb-2 m-form__group">
                                    <div class="input-group"><span class="input-group-text" style="width:140px">Password</span>
                                        <input class="form-control" name="password" type="password"
                                        placeholder="Enter Password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-3">
                            <div class="pull-left">
                                <div class="alert alert-info alert-sm p-2">
                                    <p><span><i class="fa fa-info"></i>: </span>Input password if you wish to modify your password.</p>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-primary" type="submit">Update Profile </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @push('page-scripts')
        <script src="{{ asset('/assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            var baseUrl = `{{ url('/') }}`;

            $('#municipality').change(function(e) {
                let munId = parseInt(e.target.value);
                var selectElement = document.getElementById('barangay');
                $.ajax({
                    url: baseUrl + `/api/${munId}/barangays`,
                    method: "GET",
                    success: function(response) {
                        selectElement.innerHTML = '';
                        response.forEach(function(item) {
                            var option = document.createElement('option');
                            option.value = item.id;
                            option.text = item.barangay;
                            selectElement.appendChild(option);
                        });

                        selectElement.removeAttribute('disabled');
                    }
                });
            });

            $('#barangay').change(function(e) {
                let barId = parseInt(e.target.value);
                var selectElement = document.getElementById('roles');
                $.ajax({
                    url: baseUrl + `/api/${barId}/role`,
                    method: "GET",
                    success: function(response) {
                        selectElement.innerHTML = '';
                        response.forEach(function(item) {
                            var option = document.createElement('option');
                            option.value = item.name;
                            option.text = item.name;
                            selectElement.appendChild(option);
                        });

                        selectElement.removeAttribute('disabled');
                    }
                });
            });
        </script>
    @endpush
@endsection
