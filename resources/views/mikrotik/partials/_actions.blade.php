<div class="dropdown text-center">
    <a class="badge badge-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-sliders-h fa-fw" style="font-size:13px" aria-hidden="true"></i>
        More Action
    </a>
 <div class="dropdown-menu">
     <a class="dropdown-item text-success edit-layanan" href="{{route('mikrotik.show',$data)}}"><i class="fas fa-external-link-alt"></i> Open </a>
     <a type="button" class="dropdown-item text-warning edit-layanan" href="{{route('mikrotik.edit',$data)}}"><i class="far fa-edit"></i> Perbarui </a>
     <a type="button" class="dropdown-item text-danger hapus-mikrotik" href="javascript:void(0)" data-id="{{$data->slug}}" ><i class="far fa-trash-alt"></i> Hapus</a>
    </div>
</div>
