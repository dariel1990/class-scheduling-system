@extends('layouts.app')
@prepend('page-css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet"
        type="text/css" />
@endprepend
@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-lg-12">
                <div class="pull-left">
                    <h2>Faculty Management</h2>
                </div>
                <div class="pull-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Faculty Management</li>
                        <li class="breadcrumb-item active">Faculty Lists</li>
                    </ol>
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
                    <div class="pull-left">
                        {{-- @can('academic-create') --}}
                            <a class="btn btn-sm btn-success" href="{{ route('faculty.create') }}"> Add New Record</a>
                        {{-- @endcan --}}
                    </div>
                    <div class="pull-right">

                    </div>
                </div>
                <div class="ribbon ribbon-clip ribbon-primary">
                    <span class="font-weight-bolder">Faculty Lists</span>
                </div>
                <div class="card-body p-2">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <span><i class="fa fa-check"></i></span>{{ $message }}
                            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-dashed" id="usersTable">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="text-start text-truncate">Faculty Fullname</th>
                                        <th class="text-start text-truncate">Contact No</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $faculty)
                                        <tr>
                                            <td class="text-start text-truncate">{{ $faculty->fullname }}</td>
                                            <td class="text-start text-truncate">{{ $faculty->contact_no }}</td>
                                            <td class="text-center text-truncate">
                                                <a class="btn btn-xs btn-primary p-2"
                                                    href="{{ route('faculty.edit', $faculty->id) }}">
                                                    Edit</a>
                                                <a href="#" class="btn btn-xs btn-danger p-2 delete-record" data-key="{{ $faculty->id }}">
                                                    Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('page-scripts')
<script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
<script>
    var table = $("table").dataTable({
        ordering: false,
    });
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
                    url: `/faculty/${id}`,
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
