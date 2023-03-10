<script>
    $(document).ready(function() {
        var table = $('#dataTableProfile').DataTable({
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




        // Fungsi delete user profile
        $('#dataTableProfile tbody').on('click', 'button.delete-btn', function () {
            var id = $(this).data('id');
            if (confirm("Anda yakin ingin menghapus user profile ini?")) {
                $.ajax({
                    type: "POST",
                    {{--url: "{{ route('hotspot.profile.delete', [$mikrotik, ':id']) }}".replace(':id', id),--}}
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    },
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });


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
