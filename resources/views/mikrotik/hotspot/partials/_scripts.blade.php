<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function(){
        $('#dataTableHotspot').DataTable({
            processing:true,
            serverSide: true,
            ajax: {
                url: "{{route('hotspot.index',$mikrotik)}}"
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'server', name: 'server' },
                { data: 'user', name: 'user' },
                { data: 'address', name: 'address' },
                { data: 'mac-address', name: 'mac-address' },
                {
                    data: 'uptime',
                    name: 'uptime',
                    render: function(data) {
                        return formatUptime(data);
                    }
                },
                {
                    data: function (row) { return formatBytes(row['bytes-in']); },
                    name: 'bytes-in'
                },
                {
                    data: function (row) { return formatBytes(row['bytes-out']); },
                    name: 'bytes-out'
                } ,
                {
                    data: function (row) {
                        return formatUptime(row['session-time-left']) || 'N/A';
                    },
                    name:'session-time-left'
                },
                { data: 'login-by', name: 'login-by' },
                { data: function (row) { return row.comment ? row.comment : 'No Comment' }, name:'comment' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    $(document).ready(function() {
        var hotspotOnlineSpinner = $('#hotspot-online-spinner');
        var hotspotOnlineCount = $('#countHotspotOnline');
        var hotspotUserSpinner = $('#hotspot-user-spinner');
        var hotspotUserCount = $('#countUserHotspot');

        function showHotspotOnline() {
            var url = "{{ route('router.hotspot.active', $mikrotik) }}";
            var url1 = "{{ route('router.hotspot.user', $mikrotik) }}";

            hotspotOnlineSpinner.addClass('d-inline-block');
            hotspotOnlineCount.addClass('d-none');
            hotspotUserSpinner.addClass('d-inline-block');
            hotspotUserCount.addClass('d-none');

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
                }).always(function() {
                    hotspotUserSpinner.removeClass('d-inline-block');
                });
            }).always(function() {
                hotspotOnlineSpinner.removeClass('d-inline-block');
            });
        }

        showHotspotOnline();
    });

    function formatUptime(t) {
        if(t) {
            var e = t.search("w"),
                s = t.search("d"),
                n = t.search("h"),
                r = t.search("m"),
                i = t.search("s");
            e > 0
                ? ((weak = 7 * Number(t.split("w")[0])),
                    (t_day = t.substring(e + 1, t.legth)))
                : e < 0 && ((weak = ""), (t_day = t.substring(t.legth))),
                s > 0
                    ? (weak > 0
                        ? (day = Number(t_day.split("d")[0]))
                        : (day = t_day.split("d")[0]),
                        (t_hour = t.substring(s + 1, t.legth)))
                    : s < 0 && ((day = ""), (t_hour = t_day.substring(t.legth))),
                n > 0
                    ? ((hour = t_hour.split("h")[0]),
                        1 == hour.length ? (hour = "0" + hour + ":") : (hour += ":"),
                        (t_minute = t.substring(n + 1, t.legth)))
                    : n < 0 && ((hour = "00:"), (t_minute = t.substring(s + 1, t.legth))),
                r > 0
                    ? ((minute = t_minute.split("m")[0]),
                    1 == minute.length && (minute = "0" + minute),
                        (t_sec = t.substring(r + 1, t.legth)))
                    : r < 0 && n < 0
                        ? ((minute = "00"), (t_sec = t.substring(s + 1, t.legth)))
                        : r < 0 && ((minute = "00"), (t_sec = t.substring(n + 1, t.legth))),
                i > 0
                    ? ((sec = t_sec.split("s")[0]),
                        1 == sec.length ? (sec = ":0" + sec) : (sec = ":" + sec))
                    : i < 0 && (sec = ":00");
            var a = Number(weak) + Number(day);
            return a < 1 ? (a = "") : (a += "d "), a + hour + minute + sec;
        }
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
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
    $(document).on('click', '.simpan-profile', function(e){
        e.preventDefault();
        var form = $('#formUserProfile');
        var url = "{{route('hotspot.user.profile.add',$mikrotik)}}";
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response){
                // berikan respon sukses kepada pengguna
                notifikasi(response.status,response.message,response.title)
            },
            error: function(response){
                // berikan pesan kesalahan kepada pengguna
                notifikasi(response.status,response.message,response.title)
            }
        });
    });
</script>
