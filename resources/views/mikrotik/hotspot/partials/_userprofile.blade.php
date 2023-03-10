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
{{--                    <a class="dropdown-item text-success edit-layanan" href=""><i class="fas fa-external-link-alt"></i> Open </a>--}}
{{--                    <a type="button" class="dropdown-item text-warning edit-layanan" href="{{route('mikrotik.edit',$mikrotik)}}"><i class="far fa-edit"></i> Perbarui </a>--}}
{{--                    <a type="button" class="dropdown-item text-danger hapus-mikrotik" href="javascript:void(0)" data-id="{{$mikrotik->slug}}" ><i class="far fa-trash-alt"></i> Hapus</a>--}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow">
                    <div class="card-header text-primary">
                        List User Profile
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTableProfile" class="table table-borderless align-middle dt-responsive nowrap w-100">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Shared Users</th>
                                    <th>Rate Limit</th>
                                    <th>Expired Mode</th>
                                    <th>Validity</th>
                                    <th>Price</th>
                                    <th>Selling Price</th>
                                    <th>Lock User</th>
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
        @include('mikrotik.hotspot.partials._scriptuserprofile')
    @endpushonce
@endsection
