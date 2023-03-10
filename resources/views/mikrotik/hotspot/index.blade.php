@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Hai, Terimakasih Telah Menggunakan App Ini</h1>
            <div class="dropdown text-center">
                <a class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sliders-h fa-fw" style="font-size:13px" aria-hidden="true"></i>
                    More Action
                </a>
                <div class="dropdown-menu">
                    <a type="button" class="dropdown-item text-success create-profile-hotspot" data-toggle="modal" data-target="#modalProfileHotspot">
                        <i class="fas fa-user-plus"></i> user profile
                    </a>
                    <a class="dropdown-item text-success edit-layanan" href="{{route('hotspot.profile',$mikrotik)}}"><i class="fas fa-external-link-alt"></i> List Profile </a>
                    <a type="button" class="dropdown-item text-warning edit-layanan" href="{{route('mikrotik.edit',$mikrotik)}}"><i class="far fa-edit"></i> Perbarui </a>
                    <a type="button" class="dropdown-item text-danger hapus-mikrotik" href="javascript:void(0)" data-id="{{$mikrotik->slug}}" ><i class="far fa-trash-alt"></i> Hapus</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4  shadow" style="min-height:6cm;">
                    <div class="card-body">
                        <div class="media-body">
                            <div class="text-xs font-weight-bold  text-uppercase mb-1"><i class="fas fa-bullhorn fa-fw " aria-hidden="true"></i> Pengumuman</div>
                            <hr class="text-white">
                            <p class="text-gray">
                                <br>

                                Halo {{auth()->user()->name}},<br><br>

                                Saat ini aplikasi sedang dalam pengembangan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-uppercase mb-1">Hotspot Online</div>
                                        <div class="h5 mb-0 font-weight-bold">
                                            <span id="countHotspotOnline"></span>
                                            <div id="hotspot-online-spinner" class="spinner-border text-gray-600 d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto" data-toggle="tooltip" data-original-title="Hotspot Online">
                                        <i class="fas fa-wifi fa-2x" style="color:#2caf09" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card  shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold   text-uppercase mb-1">Total User Hotspot</div>
                                        <div class="h5 mb-0 font-weight-bold">
                                            <span id="countUserHotspot"></span>
                                            <div id="hotspot-user-spinner" class="spinner-border text-gray-600 d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto" data-toggle="tooltip" data-original-title="User Hotspot">
                                        <i class="fas fa-id-card fa-2x " style="color:#db27f7" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header text-primary">
                        Users Hotspot Online
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableHotspot" class="table table-borderless align-middle dt-responsive nowrap w-100 package_datatable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Server</th>
                                    <th>Username</th>
                                    <th>Address</th>
                                    <th>Mac Address</th>
                                    <th>Uptime</th>
                                    <th>Bytes In</th>
                                    <th>Bytes Out</th>
                                    <th>Time Left</th>
                                    <th>Login By</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('mikrotik.hotspot.partials._modal_profile')
    @pushonce('custom_styles')
        <link rel="stylesheet" href="{{asset('libs/DataTables/datatables.css')}}">
        <link rel="stylesheet" href="{{asset('libs/notify/notify.min.css')}}">
        <link rel="stylesheet" href="{{asset('libs/sweetalert/sweetalert2.min.css')}}">
    @endpushonce
    @pushonce('custom_scripts')
        <script src="{{asset('libs/notify/notify.min.js')}}"></script>
        <script src="{{asset('libs/DataTables/datatables.js')}}"></script>
        <script src="{{asset('libs/sweetalert/sweetalert2.min.js')}}"></script>
        @include('mikrotik.hotspot.partials._scripts')
    @endpushonce
@endsection
