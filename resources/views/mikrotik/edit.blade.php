@extends('layouts.app')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h2 class="h3 mb-2 text-gray-800">Update Data Mikrotik</h2> <p class="mb-4"><a href="{{route('mikrotik.index')}}"> Mikrotik</a>
            <i class="fas fa-fw fa-arrow-right" style="font-size:15px;" aria-hidden="true"></i> Edit Mikrotik: {{$mikrotik->name}} </p>
        <!-- DataTales Example -->
        <div class="d-flex justify-content-end my-2">
            <a class="btn btn-sm btn-info" href="{{route('mikrotik.index')}}">kembali</a>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Update Mikrotik Data</h6>
            </div>
            <div class="card-body">
                <form action="{{route('mikrotik.edit',$mikrotik)}}" method="post">
                    @method('PUT')
                    @csrf
                   <div class="row">
                       <div class="col-lg-6">
                           <div class="form-group mb-3">
                               <label for="name">Identitas Mikrotik</label>
                               <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Hotspot Net" value="{{old('name',$mikrotik->name)}}">
                               @error('name')
                               <span class="invalid-feedback">{{$message}}</span>
                               @enderror
                           </div>
                       </div>
                       <div class="col-lg-6">
                           <div class="form-group mb-3">
                               <label for="host">Host</label>
                               <input type="text" name="host" id="host" class="form-control @error('host') is-invalid @enderror" value="{{old('host',$mikrotik->host)}}" placeholder="10.10.10.2">
                               @error('host')
                               <span class="invalid-feedback">{{$message}}</span>
                               @enderror
                           </div>
                       </div>
                   </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="username" value="{{old('username',$mikrotik->username)}}">
                                @error('username')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="text" name="password" id="password" value="{{old('password',$mikrotik->password)}}" class="form-control @error('password') is-invalid @enderror" placeholder="*******">
                                @error('password')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                        <div class="form-group">
                                <label for="port" class="form-label">Port API</label>
                                <input type="text" name="port" id="port" value="{{old('port',$mikrotik->port)}}" class="form-control @error('port') is-invalid @enderror" placeholder="8728">
                                @error('port')
                                <span class="invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
