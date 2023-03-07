
 <div class="modal fade" id="modalProfileHotspot" tabindex="-1" aria-labelledby="modalProfileHotspotLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProfileHotspotLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="formUserProfile">
                <div class="modal-body">
                 <div class="row">
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="name" class="form-label">Profile Name</label>
                             <input type="text" name="name" id="name" class="form-control" placeholder="1000">
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="address_pool" class="form-select-label">Address Pool</label>
                             <select name="address_pool" id="address_pool" class="form-control">
                                 <option selected>none</option>
                                 @foreach($pools as $pool)
                                     <option value="{{$pool['name']}}">{{$pool['name']}}</option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                 </div>
                 <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shared_users" class="form-label">Shared Users</label>
                                <input type="number" value="1" name="shared_users" id="shared_users" class="form-control">
                            </div>
                        </div>
                     <div class="col-md-6">
                         <div class="form-group">
                             <label for="rate_limit" class="form-label">Rate Limit UP/DOWN</label>
                             <input type="text" name="rate_limit" id="rate_limit" class="form-control" placeholder="Example 2M/2M or 2M/2M 3M/3M 1536k/1536k 16/16 8 1M/1M">
                         </div>
                     </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expired_mode" class="form-label">Expired Mode</label>
                                <select type="text" name="expired_mode" id="expired_mode" class="form-control">
                                    <option selected disabled>Choose Expired Type</option>
                                    <option value="0">None</option>
                                    <option value="rem">Remove</option>
                                    <option value="ntf">Notice</option>
                                    <option value="remc">Remove &amp; Record</option>
                                    <option value="ntfc">Notice &amp; Record</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="validity" class="form-label d-none" id="validity-label">Validity</label>
                                <input type="hidden" class="form-control" id="graceperiod" name="graceperiod" value="5m">
                                <input type="text" name="validity" id="validity" class="form-control d-none" placeholder="Example 1h 1d 1w">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Rp. 2000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" name="selling_price" id="selling_price" class="form-control" placeholder="Rp. 2500">
                            </div>
                        </div>
                    </div>
                 <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lock_user" class="form-label">Lock Mac</label>
                                <select type="text" name="lock_user" id="lock_user" class="form-control">
                                    <option value="disable">Disable</option>
                                    <option value="enable">Enable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent" class="form-label">Parent Queue</label>
                                <select type="parent" name="parent" id="parent" class="form-control">
                                    <option value="none">none</option>
                                    @foreach($simplequeue as $sq)
                                        <option value="{{$sq['name']}}">{{$sq['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary simpan-profile">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
 @pushonce('custom_scripts')
     <script>
         // Ambil element select box expired_mode dan input validity
         const selectExpiredMode = document.getElementById('expired_mode');
         const inputValidity = document.getElementById('validity');
         const labelValidity = document.querySelector('label[for="validity"]');

         // Tambahkan event listener pada select box
         selectExpiredMode.addEventListener('change', function() {
             // Jika opsi Remove atau Record dipilih, tampilkan input validity dan label validity
             if (this.value === 'remove' || this.value === 'remc') {
                 inputValidity.classList.remove('d-none');
                 labelValidity.classList.remove('d-none');
             }
             // Jika opsi lain yang dipilih, sembunyikan input validity dan label validity
             else {
                 inputValidity.classList.add('d-none');
                 labelValidity.classList.add('d-none');
             }
         });
     </script>
  @endpushonce
