@extends('layouts.winbox')
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
        <div class="row">
            <div class="col-12 text-end">
                <a href="/evaluation/print/comments/{{$evaluationId}}" class="btn btn-square"><i class="fas fa-print"></i> Preview this Report</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2 text-end">
                                <img src="{{ asset('assets/images/NEMSU.png') }}" width="60%">
                            </div>
                            <div class="col-8 text-center">
                                <h5 class="m-0">Republic of the Philippines</h5>
                                <h4 class="m-0 text-primary">NORTH EASTERN MINDANAO STATE UNIVERSITY</h4>
                                <h5 class="m-0">Lianga Campus</h5>
                                <h5 class="m-0 fw-light">Lianga, Surigao del Sur, 8307</h5>
                                <h5 class="m-0 fw-light">Website: <code>www.nemsu.edu.ph</code></h5>
                            </div>
                            <div class="col-2"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <h4 class="m-0">{{ $evaluation->academicYear->semester == '1' ? '1st' : '2nd' }} sem of AY {{ $evaluation->academicYear->academic_year}}</h4>
                                <h4 class="m-0 text-uppercase">{{ $evaluationType }} EVALUATION</h4><br>
                                <h4 class="fw-bold">{{ $evaluation->faculty->fullname }}.</h4>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-2"></div>
                            <div class="col-8">
                                <h5>Comments/Remarks from Students:</h5>
                                <table class="table table-condensed">
                                    <tbody>
                                        @foreach ($comments as $comment)
                                            <tr>
                                                <td class="fw-normal"> - {{ $comment->comment }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-2"></div>
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
    <script src="{{ asset('/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
@endpush
@endsection
