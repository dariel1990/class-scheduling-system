@extends('layouts.app')
@section('content')
@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Role Management</h2>
                </div>
                <div class="pull-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}"><i data-feather="home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Role Management</li>
                        <li class="breadcrumb-item active">Role Lists</li>
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
            <div class="ribbon-wrapper card">
                <div class="col-lg-12 px-3 py-3">
                    @can('role-create')
                        <div class="pull-left">
                            <a class="btn btn-sm btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
                        </div>
                    @endcan
                    <div class="pull-right">
                    </div>
                </div>
                <div class="ribbon ribbon-clip ribbon-primary">
                    <span class="font-weight-bolder">Role Lists</span>
                </div>
                <div class="card-body p-2">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <span><i class="fa fa-check"></i></span>{{ $message }}
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="card-block row">
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-dashed" id="usersTable">
                                <thead>
                                    <tr class="table-primary text-uppercase">
                                        <th class="text-center">Role Name</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td class="text-center">{{ $role->name }}</td>
                                            <td class="text-center">
                                                {{-- <a class="btn btn-xs btn-info p-2" type="button"
                                                    href="{{ route('superadmin.roles.show', $role->id) }}">View</a> --}}
                                                <a class="btn btn-xs btn-primary p-2"
                                                    href="{{ route('roles.edit', $role->id) }}"> Edit</a>
                                                <a href="#" class="btn btn-xs btn-danger p-2 delete-record" data-key="{{ $role->id }}"
                                                    > Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $roles->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
@push('page-scripts')
    <script>
        $('.delete-record').click(function (e) {

            let id = $(this).attr('data-key');
            swal({
                text: "Are you sure you want to delete this?",
                icon: "warning",
                buttons: [
                    'No',
                    'Yes!'
                ],
                dangerMode: true,
                closeOnClickOutside: false,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `/roles/${id}`,
                        method : 'DELETE',
                        success : function (response) {
                            if(response.success == true){
                                swal({
                                    text : 'Successfully deleted!',
                                    icon : 'success',
                                    timer : 2500,
                                    buttons : false,
                                });
                                location.reload();
                            }else{
                                swal({
                                    title : 'Warning!',
                                    text : `You're not allowed to do this!`,
                                    icon : 'warning',
                                    timer : 2500,
                                    buttons : false,
                                });
                            }
                        },
                    });
                }
            });
        });
    </script>
@endpush
@endsection
