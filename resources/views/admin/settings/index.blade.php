@extends('layouts.app')
@prepend('page-css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endprepend
@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>{{ $pageTitle }}</h2>
                    </div>
                    <div class="pull-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i data-feather="home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- end page title -->
        <div class='card' id="officeTableContainer">
            <div class="card-body">
                <div class="table-responsive">
                    <table class='table table-sm table-hover' id='settingsTable' width="100%">
                        <tbody>
                            @foreach ($settings as $setting)
                                <tr>
                                    <th class="text-truncate border border-right" width="10%">
                                        {{ str_replace('_', ' ', $setting->Keyname) }}
                                    </th>
                                    <th class="fw-bold editable px-3" width="85%">
                                        <span class='keyValue'
                                            id="{{ $setting->Keyname }}_value">{{ $setting->Keyvalue }}</span>
                                        <form id="{{ $setting->Keyname }}_form" class="d-none">
                                            <div class="row">
                                                <div class="col-11">
                                                    <input type='text' class="form-control form-control-sm"
                                                        name="keyValue" value="{{ $setting->Keyvalue }}"
                                                        data-key="{{ $setting->Keyname }}" />
                                                </div>
                                                <div class="col-1 text-end">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light waves-effect waves-light btnSave"
                                                        data-key="{{ $setting->Keyname }}">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </th>
                                    <th class="text-truncate" width="5%">
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-sm btn-link btnEdit"
                                                    data-key="{{ $setting->Keyname }}"
                                                    id="{{ $setting->Keyname }}_btnEdit">
                                                    Edit
                                                </button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm waves-effect waves-light d-none btnCancel mb-2"
                                                    data-key="{{ $setting->Keyname }}"
                                                    id="{{ $setting->Keyname }}_btnCancel">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('page-scripts')
        <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
        <script>
            $(function() {
                $(document).on('click', '.btnEdit', function(e) {
                    let key = $(this).attr('data-key');
                    $('.btnEdit').each(function(index, element) {
                        let data_key = $(element).attr('data-key');
                        console.log(data_key, key);
                        if (data_key == key) {
                            $(element).addClass('d-none');
                            $(`#${data_key}_btnCancel`).removeClass('d-none');
                            $(`#${data_key}_value`).addClass('d-none');
                            $(`#${data_key}_form`).removeClass('d-none');
                        }
                    });
                });

                $(document).on('click', '.btnCancel', function(e) {
                    let key = $(this).attr('data-key');
                    $('.btnCancel').each(function(index, element) {
                        let data_key = $(element).attr('data-key');
                        if (data_key == key) {
                            $(element).addClass('d-none');
                            $(`#${data_key}_btnEdit`).removeClass('d-none');
                            $(`#${data_key}_value`).removeClass('d-none');
                            $(`#${data_key}_form`).addClass('d-none');
                        }
                    });
                });

                $(document).on('click', '.btnSave', function(e) {
                    e.preventDefault();
                    let key = $(this).attr('data-key');

                    $('.btnSave').each(function(index, element) {
                        let data_key = $(element).attr('data-key');
                        if (data_key == key) {
                            let data = $(`#${data_key}_form`).serialize();

                            $.ajax({
                                url: `/settings/update/${data_key}`,
                                method: 'POST',
                                data: data,
                                success: function(response) {
                                    if (response.success) {
                                        $(`#${data_key}_value`).text(response.newValue);
                                        swal("", "Successfully updated!", "success", {
                                            closeOnClickOutside: false,
                                            button: false,
                                            timer: 1000
                                        })
                                        $(`#${data_key}_btnCancel`).trigger('click');
                                    }
                                },
                                error: function(response) {
                                    if (response.status === 422) {

                                    }
                                },
                            });
                        }
                    });

                });
            })
        </script>
    @endpush
@endsection
