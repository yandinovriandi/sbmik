
 <div class="modal fade" id="modalProfileHotspot" tabindex="-1" aria-labelledby="modalProfileHotspotLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProfileHotspotLabel">Add User Profile</h5>
            </div>
            <form id="formUserProfile">
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="pname" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pname" name="pname" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ppool" class="col-sm-2 col-form-label">Address Pool</label>
                        <div class="col-sm-10">
                            <select class="form-control " id="ppool" name="ppool">
                                <option value="none" selected>none</option>
{{--                               @foreach($pools as $pool)--}}
{{--                                    <option value="{{$pool['name']}}">{{$pool['name']}}</option>--}}
{{--                               @endforeach--}}
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pshared" class="col-sm-2 col-form-label">Shared User</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pshared" name="pshared">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="plimit" class="col-sm-2 col-form-label">Rate Limit</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="plimit" name="plimit">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pexpmode" class="col-sm-2 col-form-label">Expired Mode</label>
                        <div class="col-sm-10">
                            <select class="form-control " id="pexpmode" name="pexpmode">
                                <option value="0" selected>none</option>
                                <option value="rem">Remove</option>
                                <option value="remc">Remove & Record</option>
                                <option value="ntf">Notice</option>
                                <option value="ntfc">Notice & Record</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row" id="dvalidity" style="display: none;">
                        <label for="pvalidity" class="col-sm-2 col-form-label">Masa Aktif</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pvalidity" name="pvalidity">
                            <input type="hidden" class="form-control" id="graceperiod" name="graceperiod" value="5m">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pprice" class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="pprice" name="pprice">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="psellingprice" class="col-sm-2 col-form-label">Selling Price</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="psellingprice" name="psellingprice">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="plock" class="col-sm-2 col-form-label">Lock User</label>
                        <div class="col-sm-10">
                            <select class="form-control " id="plock" name="plock">
                                <option value="Disable">Disable</option>
                                <option value="Enable">Enable</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="pqueue" class="col-sm-2 col-form-label">Parent Queue</label>
                        <div class="col-sm-10">
                            <select class="form-control " id="pqueue" name="pqueue">
                                <option selected>none</option>
{{--                               @foreach($simplequeue as $sq)--}}
{{--                                    <option value="{{$sq['name']}}">{{$sq['name']}}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary simpan-profile">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
 @pushonce('custom_scripts')
     <script>
         // $("form[name='formadd']").submit(function(){
         //     $("#buttonadd").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Process...');
         //     $("#buttonadd").prop("disabled", true);
         //     $("form[name='formadd']")[0].submit();
         // });
         $("#pexpmode").change(function() {
             if ($('#pexpmode').val() == '0') {
                 $('#dvalidity').hide();
             } else {
                 $('#dvalidity').show();
             }
         });
     </script>
  @endpushonce
