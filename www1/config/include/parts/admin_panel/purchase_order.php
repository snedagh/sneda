<div class="container p-2 h-100">
    <?php if($view === 'view'): ?>
        <!-- VIEW -->
        <header>
            <div class="w-100 h-100 d-flex flex-wrap">
                <button onclick="set_session('view=new')" class="btn ico-nm btn-sm btn-light mr-2">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="btn ico-nm btn-sm btn-info mr-2">
                    <i class="fas fa-edit"></i>
                </button>
                <button id="delete_po" class="btn ico-nm btn-sm btn-warning mr-2">
                    <i class="fas fa-trash"></i>
                </button>
                <button onclick="po_nav('prev')" id="prev" value="" class="btn ico-nm btn-sm btn-secondary mr-2">
                    <i class="fas fa-less-than"></i>
                </button>
                <button id="next" onclick="po_nav('next')" value=""  class="btn ico-nm btn-sm btn-secondary mr-2">
                    <i class="fas fa-greater-than"></i>
                </button>
            </div>
        </header>

        <article class="overflow-hidden">
            <!-- HEADER -->
            <div class="w-100 overflow-hidden h-40">
                <input type="hidden" id="po_id">
                <div class="w-100 d-flex flex-wrap justify-content-between">

                    <div class="input-group cust_shadow bg-info mb-3 w-45 p-2 rounded">
                        <label class="w-50 mb-1 pr-1">
                            Entry Number
                            <input disabled autocomplete="off" required name="entry_no" id="entry_no" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pl-1">
                            Owner
                            <input disabled type="tel" autocomplete="off" required name="owner" id="owner" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pr-1">
                            Supplier
                            <input disabled autocomplete="off" required name="supplier" id="supplier" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pl-1">
                            Contact
                            <input disabled type="tel" autocomplete="off" required name="supplier_contact" id="supplier_contact" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pr-1">
                            Location
                            <input type="text" disabled name="loc" id="loc" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pl-1">
                            Remarks
                            <input disabled autocomplete="off" name="remarks" id="remarks" required class="form-control form-control-sm mt-2">
                        </label>
                    </div>

                    <div class="input-group mb-3 cust_shadow bg-light w-45 p-2 rounded">
                        <label class="w-50 mb-1 pr-1">
                            Entry Date
                            <input disabled autocomplete="off" type="text" required name="ent_date" id="ent_date" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pl-1">
                            Status
                            <input disabled type="tel" autocomplete="off" required name="status" id="status" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pr-1">
                            Gross
                            <input disabled autocomplete="off" type="number" value="0.00" required name="gross" id="gross" class="form-control form-control-sm mt-2">
                        </label>
                        <label class="w-50 mb-1 pl-1">
                            Disc
                            <input disabled type="text" autocomplete="off" value="0.00" required name="discount" id="discount" class="form-control form-control-sm mt-2">

                        </label>
                        <label class="w-100 mb-2">
                            Net
                            <input disabled autocomplete="off" type="number" value="0.00" name="net" id="net" required class="form-control form-control-sm mt-2">
                        </label>
                    </div>

                </div>
            </div>

            <!-- BODY -->
            <div class="w-100 h-60 cust_shadow p-2 bg-light rounded overflow-auto">

                <table class="table table-stripped table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Line</th>
                            <th>Description</th>
                            <th>Unit Cost</th>
                            <th>Quantity</th>
                            <th>Total Cost</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody id="po_list">
                    <tr id="" class="text-muted">
                        <td>1</td>
                        <td>Description</td>
                        <td>Unit Cost</td>
                        <td>Quantity</td>
                        <td>Total Cost</td>
                        <td class="text_sm">Date Added</td>
                        <td class="text_sm">Added By</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </article>

        <script>
            viewPo('<?php echo $entry_no ?>')
        </script>

    <?php endif; ?>

    <?php if($view === 'new'): ?>
        <header>
            <div class="w-100 h-100 d-flex flex-wrap">
                <button id="save_new_po" data-toggle="tooltip" title="Save Document" class="btn ico-nm btn-sm btn-success mr-2">
                    <i class="fas fa-save"></i>
                </button>
                <button onclick="set_session('view=view','<?php echo session_id() ?>')" data-toggle="tooltip" title="Cancel" class="btn ico-nm btn-sm btn-warning mr-2">
                    <i class="fas fa-cancel"></i>
                </button>
            </div>
        </header>

        <article>
            <form id="po_form" class="h-100" method="post" action="/config/proc/form_process.php">

                <input type="hidden" name="token" value="<?php echo session_id() ?>">
                <input type="hidden" name="function" value="new_po">
                

                <div class="w-100 overflow-hidden h-40">
                    <div class="w-100 d-flex flex-wrap justify-content-between">

                        <div class="input-group cust_shadow bg-info mb-3 w-45 p-2 rounded">
                            <label class="w-50 mb-1 pr-1">
                                Entry Number
                                <input disabled autocomplete="off" value="AUTOGEN" id="entry_no" class="form-control form-control-sm mt-2">
                            </label>
                            <label class="w-50 mb-1 pl-1">
                                Owner
                                <input disabled type="text" autocomplete="off" required value="<?php echo $my_username ?>" class="form-control form-control-sm mt-2">
                            </label>
                            <label class="w-50 mb-1 pr-1">
                                Supplier
                                <input autocomplete="off" required name="supplier" id="supplier" class="form-control form-control-sm mt-2">
                            </label>
                            <label class="w-50 mb-1 pl-1">
                                Contact
                                <input type="tel" autocomplete="off" required name="supplier_contact" id="supplier_contact" class="form-control form-control-sm mt-2">
                            </label>
                            <label class="w-50 mb-1 pr-1">
                                Location
                                <select name="loc" id="loc" class="form-control form-control-sm mt-2">
                                    <?php
                                    $loc_master = $pdo->query("SELECT * FROM loc_master");
                                    while($loc = $loc_master->fetch(PDO::FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $loc['loc_id'] ?>"><?php echo $loc['descr'] ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                            <label class="w-50 mb-1 pl-1">
                                Remarks
                                <input autocomplete="off" name="remarks" id="remarks" required class="form-control form-control-sm mt-2">
                            </label>
                        </div>

                        <div class="input-group mb-3 cust_shadow bg-light w-45 p-2 rounded">
                            <label class="w-100 mb-1 pr-1">
                                Entry Date
                                <input type="datetime-local" autocomplete="off" required name="entry_date" id="entry_date" class="form-control form-control-sm mt-2">
                            </label>
                            <label class="w-50 mb-1 pr-1">
                                Gross
                                <input readonly autocomplete="off" type="number" value="0.00" required name="gross" id="gross" class="form-control form-control-sm mt-2">
                            </label>
                            <label class="w-50 mb-1 pl-1">
                                Disc
                                <input type="number" autocomplete="off" value="0.00" required name="discount" id="discount" class="form-control form-control-sm mt-2">

                            </label>
                            <label class="w-100 mb-2">
                                Net
                                <input readonly autocomplete="off" type="number" value="0.00" name="net" id="net" required class="form-control form-control-sm mt-2">
                            </label>
                        </div>

                    </div>
                </div>

                <div id="po_card" class="mb-3 card h-60 cust_shadow rounded">
                    <div class="card-header overflow-hidden">
                        <div class="w-100 h-100 d-flex flex-wrap justify-content-between">
                            <p class="m-0 card-title">Items</p>
                            <button onclick="append_table('po_line')" type="button" class="btn btn-sm"><i class="fa fa-add"></i></button>
                        </div>
                    </div>
                    <div class="card-body h-100 overflow-auto">
                        <table class="table table-sm table-striped table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th>SN</th>
                                <th>Description</th>
                                <th>Unit Cost</th>
                                <th>Quantity</th>
                                <th>Total Cost</th>
                                <th>FUNC</th>
                            </tr>
                            </thead>
                            <tbody id="po_list">

                            </tbody>
                        </table>
                    </div>
                </div>

            </form>
        </article>
    <?php endif; ?>
</div>