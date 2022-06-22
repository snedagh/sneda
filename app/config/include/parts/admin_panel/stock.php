<div class="w-100 h-100 p-2 overflow-hidden">

    <div class="w-100 h-100 cust_shadow">
        <header class="bg-dutch-white">
            <div class="w-100 h-100 overflow-auto d-flex flex-wrap align-content-center pl-2">
                <button data-toggle="modal" data-target="#newBill" class="btn btn-sm ico-lg">
                    <img src="assets/icons/admin_panel/new.png" class="img-fluid">
                </button>
                <button onclick="set_session('view=all','<?php  echo session_id()?>')" class="btn btn-sm ico-lg">
                    <img src="assets/icons/admin_panel/new.png" class="img-fluid">
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