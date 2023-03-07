@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
         <h2 class="h3 mb-2 text-gray-800">Data Mikrotik</h2> <p class="mb-4"><a href="{{route('home')}}"> Dashboard</a>
            <i class="fas fa-fw fa-arrow-right" style="font-size:15px;" aria-hidden="true"></i> Data Mikrotik </p>
        <!-- DataTales Example -->
        <div class="d-flex justify-content-between my-2">
            <a class="btn btn-sm btn-primary" href="{{route('mikrotik.create')}}">add mikrotik</a>
            <a class="btn btn-sm btn-info" href="{{route('home')}}">kembali</a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Mikrotik Table</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless dt-responsive wrap w-100 customer_datatable dataTable no-footer dtr-inline collapsed" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Identity</th>
                            <th>Host</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Port</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @pushonce('custom_styles')
        <link rel="stylesheet" href="{{asset('libs/DataTables/datatables.css')}}">
        <link rel="stylesheet" href="{{asset('libs/notify/notify.min.css')}}">
        <link rel="stylesheet" href="{{asset('libs/sweetalert/sweetalert2.min.css')}}">
    @endpushonce
    @pushonce('custom_scripts')
        @include('mikrotik.partials._scripts')
        <script src="{{asset('libs/notify/notify.min.js')}}"></script>
        <script src="{{asset('libs/DataTables/datatables.js')}}"></script>
        <script src="{{asset('libs/sweetalert/sweetalert2.min.js')}}"></script>
    @endpushonce
@endsection
