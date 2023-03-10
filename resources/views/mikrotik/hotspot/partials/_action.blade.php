<div class="dropdown text-center">
    <a class="badge badge-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-sliders-h fa-fw" style="font-size:13px" aria-hidden="true"></i>
        Action
    </a>
    <div class="dropdown-menu">
        <a type="button" class="dropdown-item text-warning edit-profile" href="javascript:void(0)" data-id="{{$data['.id']}}" ><i class="far fa-edit"></i> Perbarui </a>
        <a type="button" class="dropdown-item text-danger hapus-profile" href="javascript:void(0)" data-id="{{$data['.id']}}" ><i class="far fa-trash-alt"></i> Hapus</a>
    </div>
</div>
