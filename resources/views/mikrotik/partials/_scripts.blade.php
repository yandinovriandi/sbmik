<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function(){
        $('#dataTable').DataTable({
            processing:true,
            serverSide:true,
            ajax:"{{route('mikrotik.index')}}",
            columns:[
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                { data:'name', name:'name'},
                { data:'host', name:'host' },
                { data:'username', name:'username' },
                { data:'password', name:'password' },
                {data:'port', name:'port'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });

    // sudah di fix hapus
    $('body').on('click', '.hapus-mikrotik', async function () {
        var id = $(this).data('id');
        var url = '/mikrotik/router/' + id+'/delete';
        const result = await Swal.fire({
            title: "Apa kamu yakin?",
            text: "Data akan di hapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#485ec4',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya hapus"
        });
        if (result.isConfirmed) {
            const success = await hapusLayanan(id, url);
            if (success) {
                notifikasi('success', 'Berhasil', 'Data layanan berhasil di hapus');
                $('#dataTable').DataTable().ajax.reload();
            } else {
                notifikasi('error', 'Error', 'Data layan gagal di hapus');
            }
        }
    });
    async function hapusLayanan(id, url) {
        try {
            await $.ajax({
                type: "DELETE",
                url: url
            });
            return true;
        } catch (error) {
            return false;
        }
    }
    function notifikasi(status, title, text) {
        new Notify({
            status: status,
            title: title,
            text: text,
            effect: 'slide',
            speed: 500,
            showCloseButton: true,
            autotimeout: 5000,
            autoclose: true,
        });
    }
</script>
