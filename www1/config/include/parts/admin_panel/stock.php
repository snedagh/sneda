<div class="w-100 h-100 p-2 overflow-hidden">

    <div class="w-100 h-100 cust_shadow">
        <header class="bg-dutch-white">
            <div class="w-100 h-100 overflow-auto d-flex flex-wrap align-content-center pl-2">

                <div class="w-50 h-100 d-flex flex-wrap align-content-center">
                    <button data-toggle="modal" data-target="#newBill" class="btn btn-sm ico-lg">
                        <i title="New Invoice" class="fa fa-add"></i>
                    </button>
                    <button onclick="set_session('view=all','<?php  echo session_id()?>')" class="btn btn-sm ico-lg">
                        <i class="fa fa-eye"></i>
                    </button>
                    <div class="modal fade" id="newBill">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-3">
                                    <form id="stockItem" method="post" action="/config/proc/form_process.php">

                                        <input type="hidden" name="token" value="<?php echo session_id() ?>">

                                        <div class="input-group mb-3 p-2 cust_shadow rounded">
                                            <label class="w-100">
                                                Supplier
                                                <input autocomplete="off" required name="supplier" class="form-control mt-2">
                                            </label>
                                        </div>

                                        <div class="input-group mb-3 p-2 cust_shadow rounded">
                                            <label class="w-100">
                                                Invoice Number
                                                <input autocomplete="off" name="invoice" required class="form-control mt-2">
                                            </label>
                                        </div>

                                        <div class="input-group mb-3 p-2 rounded cust_shadow">
                                            <label class="w-100">
                                                Each item on invoice should be on each line and formatted as (Description:Quantity:Price)
                                                <textarea name="items" required class="form-control mt-2" rows="5"></textarea>
                                            </label>
                                        </div>

                                        <div class="w-100 pl-3 pr-3 d-flex flex-wrap justify-content-between">
                                            <button type="submit" name="new_bill" class="btn btn-success rounded-0">Save Bill</button>
                                            <button data-dismiss="modal" type="button" name="new_bill" class="btn btn-warning rounded-0">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-50 h-100 d-flex flex-wrap align-content-center justify-content-end">
                    <button data-toggle="modal" data-target="#PO" class="btn btn-sm btn-info ico-lg mr-2">
                        <i class="">PO</i>
                    </button>
                    <div class="modal fade" id="PO">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body p-3">
                                    <form id="regular_form" method="post" action="/config/proc/form_process.php">

                                        <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                        <input type="hidden" name="function" value="new_po">

                                        <div class="w-100 d-flex flex-wrap justify-content-between">

                                            <div class="input-group mb-3 w-50 p-2 rounded">
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
                                                        <option value="001">Motors</option>
                                                    </select>
                                                </label>
                                                <label class="w-50 mb-1 pl-1">
                                                    Remarks
                                                    <input autocomplete="off" name="remarks" id="remarks" required class="form-control form-control-sm mt-2">
                                                </label>
                                            </div>

                                            <div class="input-group mb-3 w-40 p-2 rounded">
                                                <label class="w-50 mb-1 pr-1">
                                                    Gross
                                                    <input autocomplete="off" type="number" value="0.00" required name="gross" id="gross" class="form-control form-control-sm mt-2">
                                                </label>
                                                <label class="w-50 mb-1 pl-1">
                                                    Disc ( <i class="text-danger">without %</i> )
                                                    <input type="number" autocomplete="off" value="0.00" required name="discount" id="discount" class="form-control form-control-sm mt-2">

                                                </label>
                                                <label class="w-100 mb-2">
                                                    Net
                                                    <input autocomplete="off" type="number" value="0.00" name="net" id="net" required class="form-control form-control-sm mt-2">
                                                </label>
                                            </div>

                                        </div>

                                        <div style="height: 50vh" id="po_card" class="mb-3 card cust_shadow rounded">
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


                                        <div class="w-100 pl-3 pr-3 d-flex flex-wrap justify-content-between">
                                            <button type="button" id="save_new_po" name="new_bill" class="btn btn-success rounded-0">Save Bill</button>
                                            <button data-dismiss="modal" type="button" name="new_bill" class="btn btn-warning rounded-0">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <article>
            <?php if($view == 'all'): ?>
                <div class="w-100 h-100 overflow-auto">
                <table class="table table-info">
                    <thead class="thead-dark">
                    <tr>
                        <th>Supplier</th>
                        <th>Invoice</th>
                        <th>Count</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php while ($invoice = $stmt->fetch(PDO::FETCH_ASSOC)):
                            $inv = $invoice['inv_num'];
                            $id = $invoice['idinvoice']
                        ?>
                            <tr>
                            <td><?php echo $invoice['supp'] ?></td>
                                <td><?php echo $invoice['inv_num'] ?></td>
                            <td>
                                <?php
                                    echo rowsOf('stock_in',"`invoice` = '$inv'",$pdo);
                                ?>
                            </td>
                            <td><?php echo $invoice['date_stamp'] ?></td>
                            <td><kbd onclick="set_session('view=single,invoice=<?php echo $inv ?>','<?php echo session_id() ?>')" class="pointer">Preview</kbd></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php if($view == 'single'): ?>

                <div class="w-100 h-100 overflow-hidden bg-light d-flex flex-wrap align-content-center justify-content-center">
                    <div class="w-50 p-2 bg-dutch-white cust_shadow">
                        <div class="h-20">
                            <h1 class="text-center"><?php echo $invoice ?></h1>
                            <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">
                                <p><strong>From : </strong><?php echo $company ?></p>
                                <a href="/config/proc/pdf.php" target="_blank"><kbd onclick="" class="h-fit pointer">INVOICE</kbd></a>
                            </div>
                        </div>
                        <div class="h-75 overflow-auto">
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th>QTY</th><th>DESCRIPTION</th><th>UNIT PRICE</th><th>AMOUNT</th>
                                </tr>
                                <?php while ($inv = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    $amout_calc = $inv['quantity'] * $inv['price'];
                                    $total += $amout_calc;
                                    ?>
                                    <tr>
                                        <td><?php echo $inv['quantity'] ?></td>
                                        <td><?php echo $inv['item'] ?></td>
                                        <td>GHS <?php echo number_format($inv['price'],2) ?></td>
                                        <td>GHS <?php echo number_format($amout_calc,'2') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <td colspan="4" class="text-left">
                                        <p class="p-0 m-0"><strong>Sub Total : GHS <?php echo number_format($total,2) ?></strong></p>
                                        <p class="p-0 m-0"><strong>Tax (<?php echo "$tax_rate"; ?>) : GHS <?php echo $tax = cal_percentage($tax_rate,$total) ?></strong></p>
                                        <p class="p-0 m-0"><strong>Total : GHS <?php echo number_format($total + $tax,2) ?></strong></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </article>
    </div>

</div>