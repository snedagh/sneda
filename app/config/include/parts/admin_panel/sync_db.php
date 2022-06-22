<div class="h-100 overflow-hidden bg-sync">
    <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
        <div  class="w-25 m-2 h-25">
            <input name="db" value="loyalty" type="hidden">
            <input name="token" value="<?php echo session_id() ?>" type="hidden">
            <?php $this_data = "db_sync&db=loyalty&token=".session_id(); ?>
            <button onclick="loyalty_sync('<?php echo $this_data ?>')" class="btn m-1 w-100 h-100 btn-info">
                <div class="w-100 text-lg-center h-100 d-flex flex-wrap align-content-center justify-content-center">
                    <p class="m-0 text_eclips">New Customers</p>
                    <span class="badge badge-light badge-pill"><?php echo rowsOf('customers',"`synced`= 0",$pdo) ?></span>
                </div>
            </button>

            <button data-toggle="modal" data-target="#rest_del" class="btn m-1 w-100 h-100 btn-info">
                <div class="w-100 text-lg-center h-100 d-flex flex-wrap align-content-center justify-content-center">
                    REST DEL
                </div>
            </button>
            <div class="modal" id="rest_del">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="height: 50vh">
                        <div class="modal-header">
                            <input id="rest_search" type="text" class="form-control" placeholder="Item Name">
                        </div>
                        <div class="modal-body overflow-hidden">
                            <div class="w-100 h-100 overflow-auto" id="rest_res">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>