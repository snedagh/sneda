<div style="height: 100%; overflow: hidden !important;" class="container-fluid p-0">
    <div class="row no-gutters h-100 overflow-hidden">
        <div class="col-sm-2 text-light h-100 overflow-hidden bg-dark">

            <header class="d-flex flex-wrap pl-2 align-content-center">
                <strong>Mycom Inventory</strong>
            </header>

            <article class="p-1">



                <!-- DASHBOARD -->
                <button onclick="set_session('tool=dashboard,view=all','<?php echo $token ?>')" class="btn <?php if($tool == 'dashboard'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                    <span class="fa fa-dashboard"></span> Dashboard
                </button>
                <!--DASHBOARD-->

                <!-- LOYALTY -->
                <button  data-toggle="collapse" data-target="#stock" class="btn <?php if($tool === 'loyalty'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                    <span class="fa fa-credit-card"></span> Loyalty
                </button>
                <div id="stock" class="collapse pl-4 mb-2">
                    <span onclick="set_session('tool=loyalty,view=customers,todo=view','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-users"></span> Customers</span>
                </div>

                <!-- LOYALTY -->


            </article>

        </div>

        <div class="col-sm-10 h-100">

            <?php if($tool === 'dashboard'): ?>

                <div class="w-100 container-fluid h-100 bg-light-cyan">
                    <div class="h-25 w-100 row d-flex flex-wrap justify-content-center align-content-center">
                        <p class="encx">¢ <?php echo number_format($toNet,2) ?></p>
                    </div>

                    <div class="h-75 d-flex flex-wrap align-content-center justify-content-between">
                        <!-- SPINTEX -->
                        <div class="w-45 alert alert-info">
                            <div class="card card-into w-100">
                                <div class="card-header">
                                    <span class="card-title">Spintex Sales</span>
                                </div>
                                <div class="card-body">
                                    <div class="w-100 h-100">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>Mach</th><th>Gross Sales</th><th>Deduct</th><td>Net Sales</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php while ($machine = $machines_sql->fetch(PDO::FETCH_ASSOC)):
                                                $mech_no = $machine['mech_no'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $machine['mech_no'] ?></td>
                                                    <td class="text-info">
                                                        <?php echo number_format(get_sales($spin_retail,$mech_no),2) ?>
                                                    </td>
                                                    <td class="text-danger">
                                                        <?php echo number_format(get_deduct($spin_retail,$mech_no),2) ?>
                                                    </td>
                                                    <td class="text-success">
                                                        <?php echo number_format(get_sales($spin_retail,$mech_no) + get_deduct($spin_retail,$mech_no),2)  ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>

                                                <tr>
                                                    <td class="font-weight-bolder">Total</td>
                                                    <td class="text-info font-weight-bolder"><?php echo number_format($spintex_sales,2) ?></td>
                                                    <td class="text-danger font-weight-bolder">-<?php echo number_format($spintex_disc,2) ?></td>
                                                    <td class="text-success font-weight-bolder"><?php echo number_format($spintex_sales-$spintex_disc,2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bolder">Total Tax</td>
                                                    <td colspan="3" class="text-danger font-weight-bolder">-<?php echo number_format($spintex_tax,2) ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-weight-bolder">Total Sales</td>
                                                    <td colspan="3" class="text-success font-weight-bolder"><?php echo number_format($sp_sales,2) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- NIA -->
                        <div class="w-45 alert alert-info">
                            <div class="card card-into w-100">
                                <div class="card-header">
                                    <span class="card-title">NIA Sales</span>
                                </div>
                                <div class="card-body">
                                    <div class="w-100 h-100">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>Mach</th><th>Gross Sales</th><th>Deduct</th><td>Net Sales</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php while ($niaMachine = $niaMachines->fetch(PDO::FETCH_ASSOC)):
                                                $mech_no = $niaMachine['mech_no'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $niaMachine['mech_no'] ?></td>
                                                    <td class="text-info">
                                                        <?php echo number_format(get_sales($niaDD,$mech_no),2) ?>
                                                    </td>
                                                    <td class="text-danger">
                                                        <?php echo number_format(get_deduct($niaDD,$mech_no),2) ?>
                                                    </td>
                                                    <td class="text-success">
                                                        <?php echo number_format(get_sales($niaDD,$mech_no) + get_deduct($niaDD,$mech_no),2)  ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>

                                            <tr>
                                                <td class="font-weight-bolder">Total</td>
                                                <td class="text-info font-weight-bolder"><?php echo number_format($niaGross,2) ?></td>
                                                <td class="text-danger font-weight-bolder">-<?php echo number_format($niaDisc,2) ?></td>
                                                <td class="text-success font-weight-bolder"><?php echo number_format($niaGross-$niaDisc,2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bolder">Total Tax</td>
                                                <td colspan="3" class="text-danger font-weight-bolder">-<?php echo number_format($niaTax,2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bolder">Total Sales</td>
                                                <td colspan="3" class="text-success font-weight-bolder"><?php echo number_format($niaNet,2) ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

            <?php endif; ?>

            <?php if($tool === 'loyalty'):?>
                <?php if($view == 'customers' && $customer_exist == 'yes'): ?>

                    <div class="container-fluid p-2 d-flex flex-wrap align-content-center h-100 overflow-hidden bg-light-cyan">
                        <div class="container h-fit card p-2 w-75">
                            <header style="height: 8vh !important;" class="p-2 d-flex flex-wrap justify-content-between align-content-center">
                                <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-2 bg-light cust_shadow">

                                    <?php if($do === 'new'): ?>

                                        <button type="button" data-toggle="tooltip" disabled title="Save" class="btn btn-outline-success mr-2 p-1 fa fa-save pointer"></button>
                                        <button type="button" data-toggle="tooltip" title="Delete" class="btn btn-outline-danger mr-2 p-1 fa fa-delete-left pointer"></button>

                                    <?php endif; ?>
                                    <?php if($do === 'view'): ?>
                                        <button type="button" data-toggle="tooltip" title="Edit" class="btn btn-outline-primary fa mr-2 p-1 fa-plus-circle pointer"></button>
                                        <button type="button" data-toggle="modal" data-target="#loyalty_search" title="Edit" class="btn btn-outline-info fa mr-2 p-1 fa-search pointer"></button>
                                        <button type="button" onclick="item_nav('loyalty_customer','prev')" data-toggle="tooltip" title="Previous" class="btn btn-outline-dark mr-2 p-1 fa fa-arrow-left pointer"></button>
                                        <button type="button" onclick="item_nav('loyalty_customer','next')" data-toggle="tooltip" title="Next" class="btn btn-outline-dark mr-2 p-1 fa fa-arrow-right pointer"></button>
                                    <?php endif; ?>
                                </div>

                            </header>
                            <hr>
                            <?php if($do === 'view'): ?>
                                <div class="modal fade" id="loyalty_search">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="card-header">
                                                <input type="text" placeholder="find query" class="form-control form-control-sm" onkeyup="search_item('loyalty_customers',this.value)">
                                            </div>
                                            <div class="modal-body">
                                                <div style="height: 50vh">
                                                    <header>

                                                    </header>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col-sm-6 d-flex flex-wrap align-content-between pr-1">
                                    <!-- FIRST NAME -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Firstname
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['first_name']; ?></div>
                                        </div>
                                    </div>

                                    <!-- LAST NAME -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Lastname
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['last_name']; ?></div>
                                        </div>
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Email
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['email']; ?></div>
                                        </div>
                                    </div>

                                    <!-- PHONE -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Phone
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['phone']; ?></div>
                                        </div>
                                    </div>

                                    <!-- BIRTHDAY -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Issued On
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['date_created']; ?></div>
                                        </div>
                                    </div>

                                    <!-- GENDER -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Gender
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['gender']; ?></div>
                                        </div>
                                    </div>

                                    <!-- CARD NUMBER -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Card
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="n_input form-control-sm"><?php echo $customer['card_no']; ?></div>
                                        </div>
                                    </div>


                                </div>

                                <div class="col-sm-6 h-100 overflow-hidden pl-1">
                                    <div style="height: 300px; overflow: hidden">
                                        <img src="assets/profile_pics/21232f297a57a5a743894a0e4a801fc3.jpeg" alt="" class="img-fluid">
                                    </div>
                                </div>

                            </div>
                            <?php endif; ?>
                            <?php if($do === 'edit'): ?>
                                <div class="row h-50">
                                <div class="col-sm-6 d-flex flex-wrap align-content-between pr-1">
                                    <!-- FIRST NAME -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Firstname
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="n_input w-100">
                                        </div>
                                    </div>

                                    <!-- LAST NAME -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Lastname
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="n_input w-100">
                                        </div>
                                    </div>

                                    <!-- EMAIL -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Email
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="n_input w-100">
                                        </div>
                                    </div>

                                    <!-- PHONE -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Phone
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="n_input w-100">
                                        </div>
                                    </div>

                                    <!-- BIRTHDAY -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Birthday
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="date" class="n_input w-100">
                                        </div>
                                    </div>

                                    <!-- GENDER -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Gender
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="n_input w-100">
                                        </div>
                                    </div>

                                    <!-- CARD NUMBER -->
                                    <div class="row w-100">
                                        <div class="col-sm-3">
                                            Card
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" class="n_input w-100">
                                        </div>
                                    </div>


                                </div>
                                
                                <div class="col-sm-6 h-100 overflow-hidden pl-1">
                                    <img src="assets/profile_pics/21232f297a57a5a743894a0e4a801fc3.jpeg" alt="" class="img-fluid">
                                </div>
                                
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>

            <?php endif; ?>

        </div>

    </div>
</div>