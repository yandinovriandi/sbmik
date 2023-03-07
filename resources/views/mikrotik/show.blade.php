@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Hai, Terimakasih Telah Menggunakan App Ini</h1>
{{--            <a href="/user/topup" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow"> --}}
{{--                <i class="fas fa-coins" data-toggle="tooltip" data-original-title="Aksi cepat untuk melakukan pembelian tunnel coin" aria-hidden="true"></i>--}}
{{--                <i class="fas fa-plus" style="font-size:7px;" aria-hidden="true"></i> Buy Tunnel Coin --}}
{{--            </a>--}}
            <div class="dropdown text-center">
                <a class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-sliders-h fa-fw" style="font-size:13px" aria-hidden="true"></i>
                    More Action
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item text-success edit-layanan" href="{{route('hotspot.index',$mikrotik)}}"><i class="fas fa-external-link-alt"></i> Hotspot </a>
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
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card   shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold   text-uppercase mb-1">PPP Active</div>
                                        <div class="h5 mb-0 font-weight-bold">
                                            <span id="countPppActive"></span>
                                            <div id="ppp-active-spinner" class="spinner-border text-gray-600 d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto" data-toggle="tooltip" data-original-title="PPPoE Active">
                                        <i class="fas fa-network-wired fa-2x " style="color:#13d5dc" aria-hidden="true"></i>
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
                                        <div class="text-xs font-weight-bold   text-uppercase mb-1">Total PPP User</div>
                                        <div class="h5 mb-0 font-weight-bold">
                                            <span id="countPppSecrets"></span>
                                            <div id="ppp-secret-spinner" class="spinner-border text-gray-600 d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto" data-toggle="tooltip" data-original-title="PPP User">
                                        <i class="fas fa-users fa-2x " style="color:#ef9218" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card  shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold  text-uppercase mb-1">Ip binding Active</div>
                                <div class="h5 mb-0 font-weight-bold " id="countIpStatic">0</div>
                            </div>
                            <div class="col-auto" data-toggle="tooltip" data-original-title="Ip Static Binding">
                                <i class="far fa-check-circle fa-2x text-success" aria-hidden="true"></i>
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
                                <div class="text-xs font-weight-bold  text-uppercase mb-1">Total Static Binding</div>
                                <div class="h5 mb-0 font-weight-bold " id="countIpStatic">0</div>
                            </div>
                            <div class="col-auto" data-toggle="tooltip" data-original-title="Ip Static Binding">
                                <i class="fas fa-check-double fa-2x text-gray-500" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xl-12">
               <div class="row">
                   <div class="col-lg-5">
                       <div class="card shadow">
                           <div class="card-body">
                            <div class="row">
                                    <div class="ml-3 d-flex align-items-center">
                                        <div class="border border-danger rounded-circle">
                                            <img width="50px" class="img-profile rounded-circle" src="https://wifinetbill.com/images/wifinetbill/mikrotik.png" alt="">
                                        </div>
                                        <p class="ml-2 mt-4 mr-4 text-gray-900 text font-weight-bold">{{$mikrotik->name}}</p>
                                    </div>
                                 </div>
                           </div>
                       </div>
                   </div>
                   <div class="col-lg-7">
                       <div class="card shadow">
                           <div class="card-body"></div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
    @pushonce('custom_scripts')
        <script>
            $(document).ready(function() {
                var hotspotOnlineSpinner = $('#hotspot-online-spinner');
                var hotspotOnlineCount = $('#countHotspotOnline');
                var hotspotUserSpinner = $('#hotspot-user-spinner');
                var hotspotUserCount = $('#countUserHotspot');
                var pppSecretSpinner = $('#ppp-secret-spinner');
                var pppSecretCount = $('#countPppSecrets');
                var pppActiveSpinner = $('#ppp-active-spinner');
                var pppActiveCount = $('#countPppActive');

                function showHotspotOnline() {
                    var url = "{{ route('router.hotspot.active', $mikrotik) }}";
                    var url1 = "{{ route('router.hotspot.user', $mikrotik) }}";
                    var urlp = "{{ route('router.ppp.secret', $mikrotik) }}";
                    var urlpa = "{{ route('router.ppp.active', $mikrotik) }}";

                    hotspotOnlineSpinner.addClass('d-inline-block');
                    hotspotOnlineCount.addClass('d-none');
                    hotspotUserSpinner.addClass('d-inline-block');
                    hotspotUserCount.addClass('d-none');
                    pppSecretSpinner.addClass('d-inline-block');
                    pppSecretCount.addClass('d-none');
                    pppActiveSpinner.addClass('d-inline-block'); // new spinner element
                    pppActiveCount.addClass('d-none'); // new count element

                    $.get(url, function(data) {
                        var hotspotActive = data;
                        if (hotspotActive !== 0) {
                            hotspotOnlineCount.text(hotspotActive);
                            hotspotOnlineCount.removeClass('d-none');
                        }
                        $.get(url1, function(data) {
                            var hotspotUsers = data;
                            if (hotspotUsers !== 0) {
                                hotspotUserCount.text(hotspotUsers);
                                hotspotUserCount.removeClass('d-none');
                            }
                            $.get(urlp, function(data) {
                                var pppSecrets = data;
                                if (pppSecrets !== 0) {
                                    pppSecretCount.text(pppSecrets);
                                    pppSecretCount.removeClass('d-none');
                                }
                                $.get(urlpa, function(data) { // new AJAX call for PPP active
                                    var pppActive = data;
                                    if (pppActive !== 0) {
                                        pppActiveCount.text(pppActive);
                                        pppActiveCount.removeClass('d-none');
                                    }
                                }).always(function() {
                                    pppActiveSpinner.removeClass('d-inline-block');
                                });
                            }).always(function() {
                                pppSecretSpinner.removeClass('d-inline-block');
                            });
                        }).always(function() {
                            hotspotUserSpinner.removeClass('d-inline-block');
                        });
                    }).always(function() {
                        hotspotOnlineSpinner.removeClass('d-inline-block');
                    });
                }

                showHotspotOnline();
            });
        </script>
    @endpushonce
@endsection
