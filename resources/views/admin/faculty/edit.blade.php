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
                        <li class="breadcrumb-item"><a href="{{ route('academic.year') }}">Faculty Management</a></li>
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
                            <p class="f-w-600 f-20 header-text-primary">Create Faculty</p>
                            <div class="setting-list">
                                <a class="btn btn-xs btn-link text-danger" href="/academic/year"><i data-feather="x-circle"></i> </a>
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
                        {!! Form::model($faculty, ['method' => 'PUT','route' => ['faculty.update', $faculty->id]]) !!}
                            @csrf
                            <div class="mb-2 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">First Name</span>
                                    {!! Form::text('first_name', $faculty->first_name, array('placeholder' => 'first_name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">Middle Initial</span>
                                    {!! Form::text('middle_name', $faculty->middle_name, array('placeholder' => 'middle_name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">Last Name</span>
                                    {!! Form::text('last_name', $faculty->last_name, array('placeholder' => 'last_name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="mb-3 m-form__group">
                                <div class="input-group">
                                    <span class="input-group-text" style="width:150px">Suffix</span>
                                    {!! Form::text('suffix', $faculty->suffix, array('placeholder' => 'suffix','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="mb-2 m-form__group">
                                <div class="input-group"><span class="input-group-text" style="width:150px">Contact No.</span>
                                    {!! Form::text('contact_no', $faculty->contact_no, array('placeholder' => 'Contact No','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block w-100 mt-3 text-uppercase"
                                    type="submit">Save Record</button>
                            </div>
                        {!! Form::close() !!}
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
