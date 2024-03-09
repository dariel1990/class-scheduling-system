@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Faculty Management</h2>
                </div>
                <div class="pull-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('faculty') }}">Faculty Management</a></li>
                        <li class="breadcrumb-item active">Faculty Create</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 margin-tb mt-3">
                <div class="pull-left">
                </div>
                <div class="pull-right">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12 box-col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1">
                            <p class="f-w-600 f-20 header-text-primary">Add Faculty</p>
                            <div class="setting-list">
                                <a class="btn btn-xs btn-link text-danger" href="/faculty"><i data-feather="x-circle"></i> </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body row ">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        @if (count($errors) > 0)
                        <div class="alert alert-light text-danger">
                            <strong>Whoops!</strong>
                            Inputs marked with an asterisk (*) indicate mandatory fields and must be filled out.
                            @foreach ($errors->all() as $error)
                                @unless (str_contains($error, 'required')) {{-- Exclude "required" error --}}
                                    <li>{{ $error }}</li>
                                @endunless
                            @endforeach
                        </div>
                        @endif
                        <form action="{{ route('faculty.store') }}" method="post" enctype='multipart/form-data'>
                            @csrf
                            <div class="mb-2 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">First Name</span>
                                    <input class="form-control {{ ($errors->has('first_name') ? ' is-invalid' : '') }}" name="first_name" type="text" value="{{ old('first_name') }}"
                                    placeholder="Enter first Name">
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">Middle Initial</span>
                                    <input class="form-control {{ ($errors->has('middle_name') ? ' is-invalid' : '') }}" name="middle_name" type="text" value="{{ old('middle_name') }}"
                                    placeholder="Enter middle initial only">
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">Last Name</span>
                                    <input class="form-control {{ ($errors->has('last_name') ? ' is-invalid' : '') }}" name="last_name" type="text" value="{{ old('last_name') }}"
                                    placeholder="Enter last name">
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group"><span class="input-group-text" style="width:150px">Suffix</span>
                                    <input class="form-control {{ ($errors->has('suffix') ? ' is-invalid' : '') }}" name="suffix" type="text" value="{{ old('suffix') }}"
                                    placeholder="Enter suffix">
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group"><span class="input-group-text" style="width:150px">Contact No.</span>
                                    <input class="form-control {{ ($errors->has('contact_no') ? ' is-invalid' : '') }}" name="contact_no" type="text" value="{{ old('contact_no') }}"
                                    placeholder="Enter Contact No">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block w-100 mt-3 text-uppercase"
                                    type="submit">Save Record</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('page-scripts')
<script src="{{ asset('/assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endpush
@endsection
