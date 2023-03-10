<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $('#dataTableProfile').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('hotspot.profile',$mikrotik) }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'shared-users', name: 'shared-users' },
                {
                    data: 'rate-limit',
                    name: 'rate-limit',
                    render: function(data, type, row) {
                        if (!data || data === '-') {
                            return '-';
                        }
                        return data;
                    }
                },
                { data: 'expired-mode', name: 'expired-mode' },
                { data: 'validity', name: 'validity' },
                { data: 'price', name: 'price' },
                { data: 'selling-price', name: 'selling-price' },
                { data: 'lock-user', name: 'lock-user' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        $('body').on('click', '.hapus-profile', async function () {
            var id = $(this).data('id');
            var url = "{{route('hotspot.profile.delete',$mikrotik)}}";
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
                const success = await hapusProfile(id, url);
                if (success) {
                    notifikasi('success', 'Berhasil', 'Data profile berhasil di hapus');
                    $('#dataTableProfile').DataTable().ajax.reload();
                } else {
                    notifikasi('error', 'Error', 'Data profile gagal di hapus');
                }
            }
        });

        async function hapusProfile(id, url) {
            try {
                await $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {id:id}
                });
                return true;
            } catch (error) {
                return false;
            }
        }

    });

    function notifikasi(status, title, message) {
        new Notify({
            status: status,
            title: title,
            text: message,
            effect: 'slide',
            speed: 500,
            showCloseButton: true,
            autotimeout: 5000,
            autoclose: true,
        });
    }
    $(document).on('click', '.simpan-profile', function(e) {
        e.preventDefault();
        var form = $('#formUserProfile');
        var url = "{{route('hotspot.user.profile.add',$mikrotik)}}";
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                // berikan respon sukses kepada pengguna
                notifikasi(response.status, response.message, response.title)
                $('#dataTableProfile').DataTable().ajax.reload();
            },
            error: function (response) {
                // berikan pesan kesalahan kepada pengguna
                notifikasi(response.status, response.message, response.title)
            }
        });
    });
    $('body').on('click', '.hapus-profile', async function () {
        var id = $(this).data('id');
        var url = "{{route('hotspot.profile.update',$mikrotik)}}";
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
            const success = await hapusProfile(id, url);
            if (success) {
                notifikasi('success', 'Berhasil', 'Data profile berhasil di hapus');
                $('#dataTableProfile').DataTable().ajax.reload();
            } else {
                notifikasi('error', 'Error', 'Data profile gagal di hapus');
            }
        }
    });

    async function hapusProfile(id, url) {
        try {
            await $.ajax({
                type: "DELETE",
                url: url,
                data: {id:id}
            });
            return true;
        } catch (error) {
            return false;
        }
    }
    // seleksi tombol "Perbarui" dan tambahkan event click
    $('body').on('click', '.edit-profile',function() {
        // tampilkan modal
        $('#modalProfileHotspot').modal('show');

        // set nilai dari form sesuai dengan data user yang ingin diupdate
        $('#pname').val('Nama User');
        $('#ppool').val('Address Pool');
        $('#pshared').val('Shared User');
        $('#plimit').val('Rate Limit');
        $('#pexpmode').val('Expired Mode');
        $('#pvalidity').val('Masa Aktif');
        $('#pprice').val('Price');
        $('#psellingprice').val('Selling Price');
        $('#plock').val('Lock User');
        $('#pqueue').val('Parent Queue');
    });

    $('body').on('click', '.update-profile', async function () {
        var id = $(this).data('id');
        var url = "{{route('hotspot.profile.update',$mikrotik)}}";
        const result = await Swal.fire({
            title: "Apakah kamu yakin ingin memperbarui profil pengguna ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#485ec4',
            cancelButtonColor: '#d33',
            confirmButtonText: "Ya, perbarui"
        });
        if (result.isConfirmed) {
            const data = $('#formUserProfile').serialize(); // ambil data dari form modal
            const success = await updateProfile(id, url, data); // kirim permintaan Ajax dengan data yang diambil dari form modal
            if (success) {
                notifikasi('success', 'Berhasil', 'Profil pengguna berhasil diperbarui');
                $('#dataTableProfile').DataTable().ajax.reload();
            } else {
                notifikasi('error', 'Error', 'Profil pengguna gagal diperbarui');
            }
        }
    });

    async function updateProfile(id, url, data) {
        try {
            await $.ajax({
                type: "PUT", // atau POST
                url: url + '/' + id,
                data: data
            });
            return true;
        } catch (error) {
            return false;
        }
    }

</script>
