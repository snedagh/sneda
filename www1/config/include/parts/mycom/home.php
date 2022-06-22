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

                <!-- POS -->
                <button  data-toggle="collapse" data-target="#pos" class="btn <?php if($tool === 'pos'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                    <span class="fa fa-money-bill"></span> POS
                </button>
                <div id="pos" class="collapse pl-4 mb-2">
                    <span onclick="set_session('tool=pos,module=users')" class="r_child pointer"><span class="fa fa-toolbox"></span> Master</span><br>
                </div>
                <!-- POS -->

                <!-- LOYALTY -->
                <button  onclick="set_session('tool=loyalty,view=home,todo=view','<?php echo $token ?>')" class="btn <?php if($tool === 'loyalty'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                    <span class="fa fa-credit-card"></span> Loyalty
                </button>
                <div id="loyalty" class="collapse pl-4 mb-2">
                    <span onclick="set_session('tool=loyalty,view=customers,todo=view','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-users"></span> Customers</span>
                </div>

                <!-- LOYALTY -->

                <!-- STOCK -->
                <!-- LOYALTY -->
                <button  data-toggle="collapse" data-target="#stock" class="btn <?php if($tool === 'stock'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                    <span class="fa fa-credit-card"></span> Stock
                </button>
                <div id="stock" class="collapse pl-4 mb-2">
                    <span onclick="sync_nia_stock()" class="r_child pointer"><span class="fa fa-users"></span> Sync Stock</span><br>
                    <span onclick="set_session('tool=spintex,view=home,todo=view','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-users"></span> Spin Stock</span><br>
                    <span onclick="set_session('tool=stock,view=count,todo=view','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-users"></span> Count</span><br>
                    <span onclick="set_session('tool=stock,view=stock,todo=view','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-users"></span> Stock</span>
                </div>
                <!-- STOCK -->


            </article>

        </div>

        <div class="col-sm-10 h-100 bg-bone">

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

            <?php if($tool === 'dashboardv2'): ?>
                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                <div class="w-100 container-fluid overflow-auto p-2 h-100 bg-light-cyan">
                    <div class="w-100">
                        <div id="chart" class="cust_shadow_inside" width="200" height="200"></div>
                    </div>
                    <div class="w-100 alert alert-info d-flex justify-content-between flex-wrap">
                        <?php if($group->rowCount()):
                            while ( $places = $group->fetch(PDO::FETCH_ASSOC)):
                                $place = $places['place'];
                                $location = $places['loc'];
                                $this_location = $pdo->query("SELECT * FROM sales WHERE `place` = '$place' and  `day` = '$today' group by `loc`");
                                $this_tax = $pdo->query("select sum(tax) as all_tax from sales where `place` = '$place' and  `day` = '$today'");
                                $this_tax_res = $this_tax->fetch(PDO::FETCH_ASSOC);
                                $tax_sum = $this_tax_res['all_tax'];

                                ## gross
                                $this_gross = $pdo->query("select sum(gross_sales) as gross from sales where `place` = '$place' and  `day` = '$today'");
                                $this_gross_res = $this_gross->fetch(PDO::FETCH_ASSOC);
                                $gross_sum = $this_gross_res['gross'];

                                ## deducts
                                $this_discount = $pdo->query("select sum(discount) as discount from sales where `place` = '$place' and  `day` = '$today'");
                                $this_discount_res = $this_discount->fetch(PDO::FETCH_ASSOC);
                                $discount_sum = $this_discount_res['discount'];

                                ## net
                                $this_net = $pdo->query("select sum(net_sales) as net from sales where `place` = '$place' and  `day` = '$today'");
                                $this_net_res = $this_net->fetch(PDO::FETCH_ASSOC);
                                $net_sum = $this_net_res['net'];

                                ?> 

                                    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                                        <!-- Brand/logo -->
                                        <a class="navbar-brand" href=""><?php echo strtoupper($place) ?></a>

                                        <!-- Links -->
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item">
                                                <span class="badge badge-info ml-1 mr-1" >Gross <?php echo number_format($gross_sum,2) ?></span>
                                            </li>
                                            <li class="nav-item">
                                                <span class="badge badge-danger ml-1 mr-1" >Tax <?php echo number_format($tax_sum,1) ?></span>
                                            </li>

                                            <li class="nav-item">
                                                <span class="badge badge-warning ml-1 mr-1" >Discount <?php echo number_format($discount_sum,2) ?></span>
                                            </li>
                                            <li class="nav-item">
                                                <span class=" badge badge-primary ml-1 mr-1" >Net <?php echo number_format($net_sum,2) ?></span>
                                            </li>
                                            <li class="nav-item">
                                                <span class=" badge badge-success ml-1 mr-1" >Total Sales <?php echo number_format($net_sum - $tax_sum,2) ?></span>
                                            </li>

                                        </ul>
                                    </nav>
                                    <div class="container-fluid">
                                        <div class="row no-gutters d-flex flex-wrap justify-content-between">
                                            <?php
                                            while ($location = $this_location->fetch(PDO::FETCH_ASSOC)):
                                                $loc = $location['loc'];
                                                $loc_desc = $location['loc_desc'];
                                                $pl = $location['place'];
                                                // get machines
                                                $machines = $pdo->query("SELECT * FROM `sales` WHERE `loc` = '$loc' AND `place` = '$pl' and  `day` = '$today' order by mech_no asc");
                                                ?>

                                                <div class="col-sm-6 p-2">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <span class="card-title"><?php echo $loc_desc  ?></span>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="w-100 h-100">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Mech</th><th>Gross Sales</th><th>Discount</th><th>Net Sales</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $gross_total = 0;
                                                                    $disc_total = 0;
                                                                    $net_total = 0;
                                                                    $tax = 0;
                                                                    while ($mach = $machines->fetch(PDO::FETCH_ASSOC)):
                                                                        $gross_total += $mach['gross_sales'];
                                                                        $disc_total += $mach['discount'] ;
                                                                        $net_total += $mach['net_sales'];
                                                                        $tax += $mach['tax'];
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $mach['mech_no'] ?></td>
                                                                            <td><?php echo number_format($mach['gross_sales'],2) ?></td>
                                                                            <td class="text-danger"><?php echo number_format($mach['discount'] ,2)?></td>
                                                                            <td><?php echo number_format($mach['net_sales'],2) ?></td>
                                                                        </tr>
                                                                    <?php endwhile; ?>
                                                                    <tr>
                                                                        <td class="font-weight-bold">Total</td>
                                                                        <td class="font-weight-bold"><?php echo number_format($gross_total,2) ?></td>
                                                                        <td class="font-weight-bold text-danger"><?php echo number_format($disc_total,2) ?></td>
                                                                        <td class="font-weight-bold"><?php echo number_format($net_total,2) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="font-weight-bold">Total Tax</td>
                                                                        <td colspan="3" class="text-danger"><?php echo number_format($tax,2) ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="font-weight-bold">Total Sales</td>
                                                                        <td colspan="3" class="text-success"><?php echo number_format($net_total - $tax,2) ?></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                <?php  endwhile; endif; ?>
                    </div>
                </div>

                <script >

                    var options = {
                        series: [{
                            name: 'SPINTEX RETAIL',
                            data: [<?php while($spi = $graph_sales->fetch(PDO::FETCH_ASSOC)){echo $spi['total_sales'] . ',';} ?>]
                        }, {
                            name: 'NIA RETAIL',
                            data: [<?php while($nia = $nia_sales->fetch(PDO::FETCH_ASSOC)){echo $nia['total_sales'] . ',';} ?>]
                        }],
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: [<?php while($days = $graph_days->fetch(PDO::FETCH_ASSOC)){echo "'".$days['day']."',";} ?>],
                        },
                        yaxis: {
                            title: {
                                text: '¢'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return "$ " + val + " thousands"
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();

                </script>

            <?php endif; ?>

            <?php if($tool === 'loyalty'):?>



                    <div class="w-100 h-100 bg-bone">
                        <header class="bg-light-cyan d-flex flex-wrap align-content-center p-1">
                            <div class="dropdown">
                                <button type="button" class="btn bg-cus-muted btn-sm btn-light rounded-0 dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-id-card"></i> Customers
                                </button>
                                <div class="dropdown-menu rounded-0 bg-cus-muted">
                                    <a class="dropdown-item pointer" onclick="set_session('view=customers','<?php echo session_id() ?>')" >Customers</a>
                                    <a class="dropdown-item pointer" onclick="set_session('view=new_customer','<?php echo session_id() ?>')" >New Customer</a>
                                    <a class="dropdown-item pointer" onclick="set_session('view=v2,stage=comf_num','<?php echo session_id() ?>')" >Re-register</a>
                                </div>
                            </div>
                        </header>
                        <article class="d-flex flex-wrap align-content-center justify-content-center">
                            <?php if($view === 'home'): ?>
                                <div class="w-75">
                                    <div class="card w-30">
                                        <div class="card-header">
                                            Customers
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($view === 'customers'):
                                unset($_SESSION['new_customer_stage']);
                                // check customers
                                if(rowsOf('customers',"none",$pdo) < 1)
                                {
                                    // change session
                                    setSession('view','new_customer');
                                    reload();
                                }

                                $q = $pdo->query("SELECT * FROM `customers` order by `id` desc limit 1");
                                $s = $q->fetch(PDO::FETCH_ASSOC);
                                $r = $s['id'];
                                setSession('customer',$r);
                                $customer_id = getSession('customer');
                                $customer_detail = $pdo->query("SELECT * FROM `customers` WHERE `id` = '$customer_id'");
                                $result = $customer_detail->fetch(PDO::FETCH_ASSOC);


                                $next = rowsOf('customers',"`id` > '$customer_id'", $pdo);
                                $previous = rowsOf('customers',"`id` < '$customer_id'", $pdo);

                                // get user image
                                $file_str = $result['card_no'];
                                $file = glob ("assets/customers/$file_str*");
                                if(count($file) > 0)
                                {
                                    // get first file
                                    $id_exist = 'yes';
                                    $id_file = $file[0];
                                    $file_ext = pathinfo($id_file,PATHINFO_EXTENSION);

                                }
                                else
                                {
                                    $id_exist = 'no';
                                }

                                // check if there is next

                                //print_r($customer_id);
                                ?>
                                <div class="w-50 h-75 card cust_shadow">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex flex-wrap">
                                            <div class="d-flex flex-wrap w-50">

                                            </div>
                                            <div class="d-flex w-50 flex-wrap justify-content-end">
                                                <button type="button" id="previCustomer" onclick="c_nav('previous')" data-toggle="tooltip" title="Previous" class="btn btn-sm btn-outline-info mr-1"><i class="fa fa-backward"></i></button>
                                                <button type="button" id="nextCustomer" onclick="c_nav('next')" data-toggle="tooltip" title="Next" class="btn btn-sm btn-outline-info mr-1"><i class="fa fa-forward"></i></button>
                                                <button data-toggle="modal" title="Search" data-target="#searchCustomer" class="btn btn-sm btn-dark mr-1"><i class="fa fa-search"></i></button>
                                                <div class="modal" id="searchCustomer">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <input type="text" class="form-control" id="customerSearchInput" placeholder="Query">
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="w-100 d-flex flex-wrap" style="height: 50vh !important; overflow: auto">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-striped table-hover">
                                                                            <thead class="thead-dark">
                                                                            <tr>
                                                                                <th>Card N<u>0</u></th>
                                                                                <th>Customer</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody id="cusRes"></tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="container">
                                            <!-- NAMES -->
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    Card Number : <br>
                                                    <div id="card_no" class="n_input"><?php echo $result['card_no'] ?></div>
                                                </div>
                                            </div>
                                            <!-- NAMES -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    First Name: <br>
                                                    <div id="firstName" class="n_input"><?php echo $result['first_name'] ?></div>
                                                </div>
                                                <div class="col-sm-6">
                                                    Last Name: <br>
                                                    <div id="lastName" class="n_input"><?php echo $result['last_name'] ?></div>
                                                </div>
                                            </div>

                                            <!-- CONTACT -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Email: <br>
                                                    <div id="emailAddress" class="n_input"><?php echo $result['email'] ?></div>
                                                </div>
                                                <div class="col-sm-6">
                                                    Phone: <br>
                                                    <div id="phoneNumber" class="n_input"><?php echo $result['phone'] ?></div>
                                                </div>
                                            </div>

                                            <!-- OWNER LOCATION -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Owner: <br>
                                                    <div id="ownerX" class="n_input"><?php echo $result['owner'] ?></div>
                                                </div>
                                                <div class="col-sm-6">
                                                    Location: <br>
                                                    <div id="location" class="n_input"><?php echo $result['location'] ?></div>
                                                </div>
                                            </div>

                                            <!-- DATES -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Date Created: hello worl
                                                </div>
                                                <div class="col-sm-6">
                                                    Date Expiry: hello
                                                </div>
                                            </div>

                                            <!-- IMAGE -->
                                            <div class="w-100 d-flex flex-wrap justify-content-center">
                                                <div style="width: 100px">
                                                    <?php if($id_exist === 'yes'): ?>
                                                        <button data-toggle="modal" data-target="#idImg" class="btn btn-info rounded-0">View ID</button>
                                                        <div id="idImg" class="modal">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <img src="<?php echo $id_file ?>" alt="" class="img-fluid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if($id_exist === 'no'): ?>
                                                        <button class="btn btn-warning rounded-0">No ID <?php print_r($file) ?></button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if($view === 'new_customer'):
                                    if(!isset($_SESSION['new_customer_stage']))
                                    {
                                        $_SESSION['new_customer_stage'] = 'details';
                                    }
                                    $new_customer_stage = $_SESSION['new_customer_stage'];
                                ?>
                                <?php if($new_customer_stage === 'details'): ?>
                                    <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                    <input type="hidden" name="function" value="loyalty">
                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex flex-wrap">
                                            <button data-toggle="tooltip" type="submit" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-save"></i></button>
                                            <button onclick="set_session('view','customers')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-cancel"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="container">
                                            <!-- NAMES -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    First Name: <br>
                                                    <input value="<?php echo getSession('first_name') ?>" type="text" required autocomplete="off" name="first_name" autofocus class="n_input w-100">
                                                </div>
                                                <div class="col-sm-6">
                                                    Last Name: <br>
                                                    <input value="<?php echo getSession('last_name') ?>" type="text" required autocomplete="off" name="last_name" class="n_input w-100">
                                                </div>
                                            </div>

                                            <!-- CONTACT -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Email: <br>
                                                    <input value="<?php echo getSession('email_address') ?>" type="email" autocomplete="off" name="email_address" class="n_input w-100">
                                                </div>
                                                <div class="col-sm-6">
                                                    Phone: <br>
                                                    <input value="<?php echo getSession('phone') ?>" type="tel" required autocomplete="off" name="phone" class="n_input w-100">
                                                </div>
                                            </div>

                                            <!-- OWNER LOCATION -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Date Of Birth: <br>
                                                    <input value="<?php echo getSession('date_of_birth') ?>" type="date" required autocomplete="off" name="date_of_birth" class="n_input w-100">
                                                </div>
                                                <div class="col-sm-6">
                                                    Gender: <br>
                                                    <select name="gender" class="n_input p-1 w-100" id="">
                                                        <option <?php if(getSession('gender') === 'M'){echo 'selected';} ?> value="M">Male</option>
                                                        <option <?php if(getSession('gender') === 'F'){echo 'selected';} ?> value="F">Female</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                                <?php endif; ?>

                                <?php if($new_customer_stage === 'verify_otp'): ?>
                                    <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                        <input type="hidden" name="function" value="loyalty">
                                        <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                        <div class="card-header">
                                            <div class="w-100 h-100 d-flex justify-content-between flex-wrap">
                                                <button onclick="set_session('new_customer_stage=details','<?php echo session_id() ?>')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-backward"></i></button>
                                                <button data-toggle="tooltip" type="submit" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-forward"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="w-100 d-flex flex-wrap align-content-center h-100">
<!--                                                valid otp-->
                                                    <div class="form-inline d-flex flex-wrap justify-content-center">
                                                        <div class="w-100 text-center">
                                                            Provide OTP sent to customer
                                                        </div>
                                                        <input maxlength="1" min="1" required name="otp1" onkeyup="otpMove(this.id)" id="otp_1" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                        <input maxlength="1" min="1" required name="otp2" onkeyup="otpMove(this.id)" id="otp_2" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                        <input maxlength="1" min="1" required name="otp3" onkeyup="otpMove(this.id)" id="otp_3" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                        <input maxlength="1" min="1" required name="otp4" onkeyup="otpMove(this.id)" id="otp_4" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                        <input maxlength="1" min="1" required name="otp5" onkeyup="otpMove(this.id)" id="otp_5" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                        <input maxlength="1" min="1" required name="otp6" onkeyup="otpMove(this.id)" id="otp_6" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                    </div>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                                <?php if($new_customer_stage === 'upload_id'): ?>
                                    <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                    <input type="hidden" name="function" value="loyalty">
                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex justify-content-between flex-wrap">
                                            <button onclick="set_session('new_customer_stage=details','<?php echo session_id() ?>')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-backward"></i></button>
                                            <button disabled onclick="set_session('new_customer_stage=final','<?php echo session_id() ?>')" id="next" data-toggle="tooltip" type="button" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-forward"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="w-100 d-flex flex-wrap align-content-center h-100">
                                            <!--                                                valid otp-->
                                            <div class="w-100 d-flex flex-wrap justify-content-center">


                                                <div class="img_id">
                                                    <img src="" class="img-fluid img-thumbnail" alt="" id="image_display">
                                                    <div id="img_ckeck" class="w-100 h-100 alert alert-info">
                                                        <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                            <div id class="w-100 text-center"><div class="spinner-border"></div></div>
                                                            <span>Upload Image using App</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    function find_customer_id_uploaded(token)
                                                    {

                                                        setInterval(function() {
                                                            $.ajax(
                                                                {
                                                                    url: "config/proc/form/loyalty.php",
                                                                    type: 'POST',
                                                                    data: {
                                                                        'token': token,
                                                                        'get_uploaded_id': '',
                                                                    },
                                                                    success: function (response)
                                                                    {

                                                                        console.log(response)

                                                                        var s = response.split('%%');
                                                                        var code = s[0];
                                                                        if(code == 'Y')
                                                                        {

                                                                            var image = s[1];
                                                                            console.log(+image.toString());
                                                                            document.getElementById('image_display').src='assets/customers/'+image.toString();

                                                                            document.getElementById('img_ckeck').style.display='none';
                                                                            document.getElementById('next').disabled = false;
                                                                        }
                                                                        else
                                                                        {
                                                                            // document.getElementById('img_ckeck').innerHTML = '<span class="spinner-border spinner-border-sm"></span>\n' +
                                                                            //     '                                            Upload id using LIP from phone';
                                                                            document.getElementById('next').disabled = true;
                                                                        }
                                                                    }
                                                                }
                                                            );
                                                        },  3000)
                                                    }
                                                    find_customer_id_uploaded('<?php echo session_id() ?>');
                                                </script>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php endif; ?>

                                <?php if($new_customer_stage === 'final'): ?>
                                    <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                        <input type="hidden" name="function" value="loyalty">
                                        <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                        <div class="card-header">
                                            <div class="w-100 h-100 d-flex flex-wrap">
                                                <button disabled data-toggle="tooltip" id="save_button" type="submit" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-save"></i></button>
                                                <button onclick="set_session('new_customer_stage','details')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-cancel"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="container">
                                                <!-- NAMES -->
                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        First Name: <br>
                                                        <input disabled value="<?php echo getSession('first_name') ?>" type="text" required autocomplete="off" name="first_name" autofocus class="n_input w-100">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        Last Name: <br>
                                                        <input disabled value="<?php echo getSession('last_name') ?>" type="text" required autocomplete="off" name="last_name" class="n_input w-100">
                                                    </div>
                                                </div>

                                                <!-- CONTACT -->
                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        Email: <br>
                                                        <input disabled value="<?php echo getSession('email_address') ?>" type="email" autocomplete="off" name="email_address" class="n_input w-100">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        Phone: <br>
                                                        <input disabled value="<?php echo getSession('phone') ?>" type="tel" required autocomplete="off" name="phone" class="n_input w-100">
                                                    </div>
                                                </div>

                                                <!-- OWNER LOCATION -->
                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        Date Of Birth: <br>
                                                        <input disabled value="<?php echo getSession('date_of_birth') ?>" type="date" required autocomplete="off" name="date_of_birth" class="n_input w-100">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        Gender: <br>
                                                        <select disabled name="gender" class="n_input p-1 w-100" id="">
                                                            <option <?php if(getSession('gender') === 'M'){echo 'selected';} ?> value="M">Male</option>
                                                            <option <?php if(getSession('gender') === 'F'){echo 'selected';} ?> value="F">Female</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- OWNER LOCATION -->
                                                <div class="row mb-3">
                                                    <div class="col-sm-6">
                                                        <div class="img_id_sm">
                                                            <img class="img-fluid img-thumbnail img_id" src="assets/customers/tmp_56.jpg" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        Card Number: <br>
                                                        <input maxlength="10"  autofocus onkeyup="validateCard(this.value)" type="text" required autocomplete="off" name="card_number" class="n_input w-100">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                            <?php endif; ?>

                            <?php if($view === 'v2'):
                                unsetSession(['new_customer_stage']);
                                //print_r($_SESSION['stage']);
                                $stage = getSession('stage');
                                ?>

                                <?php if($stage === 'comf_num'): ?>

                                    <div class="w-50 h-50 card">
                                        <div class="card-header">
                                            <div class="w-100 h-100 d-flex flex-wrap justify-content-between align-content-center">
                                                <div class="h-100 w-25">
                                                    <strong class="card-title">Confirm Card</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="card-body">
                                            <input type="hidden" name="function" value="confirm_card">
                                            <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                <input type="text" required placeholder="Card Number" autofocus autocomplete="off" name="card_number" class="form-control text-center w-75">
                                            </div>
                                            <button type="submit" style="display: none"></button>
                                        </form>
                                    </div>

                                <?php endif; ?>

                                <?php if($stage === 'user_details'):
                                    $card = getSession('card_number');
                                    $customer = fetchFunc('customers',"`card_no` = '$card'",$pdo);
                                ?>

                                    <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 card cust_shadow">
                                    <input type="hidden" name="function" value="confirm_details">
                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex flex-wrap">
                                            <button data-toggle="tooltip" type="submit" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-save"></i></button>
                                            <button onclick="set_session('view','customers')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-cancel"></i></button>

                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="container">
                                            <div class="w-100 my-2">
                                                <strong class="text-primary"><?php echo $card ?></strong>
                                            </div>
                                            <!-- NAMES -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    First Name: <br>
                                                    <input value="<?php echo $customer['first_name'] ?>" type="text" required autocomplete="off" name="first_name" class="n_input w-100">
                                                </div>
                                                <div class="col-sm-6">
                                                    Last Name: <br>
                                                    <input value="<?php echo $customer['last_name']?>" type="text" required autocomplete="off" name="last_name" class="n_input w-100">
                                                </div>
                                            </div>

                                            <!-- CONTACT -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Email: <br>
                                                    <input value="<?php echo $customer['email']  ?>" type="email" autocomplete="off" name="email_address" class="n_input w-100">
                                                </div>
                                                <div class="col-sm-6">
                                                    Phone: <br>
                                                    <input value="<?php echo $customer['phone'] ?>" type="tel" required autocomplete="off" name="phone" class="n_input w-100">
                                                </div>
                                            </div>

                                            <!-- OWNER LOCATION -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    Date Of Birth: <br>
                                                    <input value="<?php echo $customer['date_of_birth'] ?>" type="date" required autocomplete="off" name="date_of_birth" class="n_input w-100">
                                                </div>
                                                <div class="col-sm-6">
                                                    Gender: <br>
                                                    <select name="gender" class="n_input p-1 w-100" id="">
                                                        <option <?php if(getSession('gender') === 'M'){echo 'selected';} ?> value="M">Male</option>
                                                        <option <?php if(getSession('gender') === 'F'){echo 'selected';} ?> value="F">Female</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>

                                <?php endif; ?>

                                <?php if($stage === 'verify_otp'): ?>
                                <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                    <input type="hidden" name="function" value="update_otp">
                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex justify-content-between flex-wrap">
                                            <button onclick="set_session('stage=user_details','<?php echo session_id() ?>')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-backward"></i></button>
                                            <button data-toggle="tooltip" type="submit" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-forward"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="w-100 d-flex flex-wrap align-content-center h-100">
                                            <!--                                                valid otp-->
                                            <div class="form-inline d-flex flex-wrap justify-content-center">
                                                <div class="w-100 text-center">
                                                    Provide OTP sent to customer
                                                </div>
                                                <input autofocus maxlength="1" min="1" required name="otp1" onkeyup="otpMove(this.id)" id="otp_1" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                <input maxlength="1" min="1" required name="otp2" onkeyup="otpMove(this.id)" id="otp_2" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                <input maxlength="1" min="1" required name="otp3" onkeyup="otpMove(this.id)" id="otp_3" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                <input maxlength="1" min="1" required name="otp4" onkeyup="otpMove(this.id)" id="otp_4" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                <input maxlength="1" min="1" required name="otp5" onkeyup="otpMove(this.id)" id="otp_5" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                                <input maxlength="1" min="1" required name="otp6" onkeyup="otpMove(this.id)" id="otp_6" autocomplete="off" type="text" class="otp_input form-control text-center m-2 border-bottom rounded-0" placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>

                                <?php if($stage === 'verify_id'): ?>
                                <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                    <input type="hidden" name="function" value="loyalty">
                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex justify-content-between flex-wrap">
                                            <button onclick="set_session('new_customer_stage=details','<?php echo session_id() ?>')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-backward"></i></button>
                                            <button disabled onclick="set_session('stage=verify','<?php echo session_id() ?>')" id="next" data-toggle="tooltip" type="button" title="Save" class="btn btn-sm btn-info mr-1"><i class="fa fa-forward"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="w-100 d-flex flex-wrap align-content-center h-100">
                                            <!--                                                valid otp-->
                                            <div class="w-100 d-flex flex-wrap justify-content-center">


                                                <div class="img_id">
                                                    <img src="" class="img-fluid img-thumbnail" alt="" id="image_display">
                                                    <div id="img_ckeck" class="w-100 h-100 alert alert-info">
                                                        <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                            <div id class="w-100 text-center"><div class="spinner-border"></div></div>
                                                            <span>Upload Image using App</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    function find_customer_id_uploaded(token)
                                                    {

                                                        setInterval(function() {
                                                            $.ajax(
                                                                {
                                                                    url: "config/proc/form/loyalty.php",
                                                                    type: 'POST',
                                                                    data: {
                                                                        'token': token,
                                                                        'get_uploaded_id': '',
                                                                    },
                                                                    success: function (response)
                                                                    {

                                                                        console.log(response)

                                                                        var s = response.split('%%');
                                                                        var code = s[0];
                                                                        if(code == 'Y')
                                                                        {

                                                                            var image = s[1];
                                                                            console.log(+image.toString());
                                                                            document.getElementById('image_display').src='assets/customers/'+image.toString();

                                                                            document.getElementById('img_ckeck').style.display='none';
                                                                            document.getElementById('next').disabled = false;
                                                                        }
                                                                        else
                                                                        {
                                                                            // document.getElementById('img_ckeck').innerHTML = '<span class="spinner-border spinner-border-sm"></span>\n' +
                                                                            //     '                                            Upload id using LIP from phone';
                                                                            document.getElementById('next').disabled = true;
                                                                        }
                                                                    }
                                                                }
                                                            );
                                                        },  3000)
                                                    }
                                                    find_customer_id_uploaded('<?php echo session_id() ?>');
                                                </script>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>

                                <?php if($stage === 'verify'): ?>
                                <form method="post" id="regular_form" action="config/proc/form/loyalty.php" class="w-50 h-75 card cust_shadow">
                                    <input type="hidden" name="function" value="update_customer">
                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                    <div class="card-header">
                                        <div class="w-100 h-100 d-flex justify-content-between flex-wrap">
                                            <button onclick="set_session('stage=verify_id','<?php echo session_id() ?>')" data-toggle="tooltip" type="button" title="Delete" class="btn btn-sm btn-warning mr-1"><i class="fa fa-backward"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body overflow-auto p-2">
                                        <stront>Customer Details</stront>
                                        <p><small>Full Name : <span class="text-primary"><?php echo getSession('first_name') . " " . getSession('last_name') ?></span></small></p>
                                        <p><small>Email Address : <span class="text-primary"><?php echo getSession('email_address') ?></span></small></p>
                                        <p><small>Phone Number : <span class="text-primary"><?php echo getSession('phone') ?></span></small></p>
                                        <p><small>Date Of Birth : <span class="text-primary"><?php echo getSession('gender') ?></span></small></p>
                                        <p><small>Date Of Birth : <span class="text-primary"><?php echo getSession('date_of_birth') ?></span></small></p>
                                        <hr>
                                        <div class="text-center"><strong>ID Card</strong></div>
                                        <div class="w-50 mx-auto text-center">
                                            <img src="" class="img-fluid" alt="" id="image_display">

                                            <button class="btn btn-success m-2" id="next" onclick="updateCustomer('<?php echo session_id() ?>')" >FINISH</button>

                                            <script>
                                                function find_customer_id_uploaded(token)
                                                {

                                                    $.ajax(
                                                        {
                                                            url: "config/proc/form/loyalty.php",
                                                            type: 'POST',
                                                            data: {
                                                                'token': token,
                                                                'get_uploaded_id': '',
                                                            },
                                                            success: function (response)
                                                            {

                                                                console.log(response)

                                                                var s = response.split('%%');
                                                                var code = s[0];
                                                                if(code == 'Y')
                                                                {

                                                                    var image = s[1];
                                                                    console.log(+image.toString());
                                                                    document.getElementById('image_display').src='assets/customers/'+image.toString();

                                                                    document.getElementById('img_ckeck').style.display='none';
                                                                    document.getElementById('next').disabled = false;
                                                                }
                                                                else
                                                                {
                                                                    // document.getElementById('img_ckeck').innerHTML = '<span class="spinner-border spinner-border-sm"></span>\n' +
                                                                    //     '                                            Upload id using LIP from phone';
                                                                    document.getElementById('next').disabled = true;
                                                                }
                                                            }
                                                        }
                                                    );
                                                }
                                                find_customer_id_uploaded('<?php echo session_id() ?>');
                                            </script>
                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php endif; ?>

                        </article>
                    </div>



            <?php endif; ?>

            <?php if($tool === 'stock'): ?>
                <script src="js/stock.js"></script>
                <script>$('#addNew').modal('show')</script>
                <?php if($view === 'count'){ ?>
                    <div class="w-100 d-flex flex-wrap align-content-center justify-content-center h-100">
                        <div class="card w-50">
                            <div class="card-header">
                                <input type="text" class="form-control" autofocus placeholder="search" onkeyup="searchStock(this.value)">
                            </div>
                            <div class="card-body" style="height: 50vh">
                                <form id="regular_form" method="post" action="config/proc/form/stock.php" class="w-100"">
                                <input type="hidden" name="function" value="add_counted">
                                <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                <table class="table w-100 table-bordered table-sm table-responsive">
                                    <tbody id="sRes">

                                    </tbody>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                <div class="w-100 h-100 bg-light overflow-hidden">
                    <?php
                        // get counts of all
                        $all_count = rowsOf('nia_stock_filtered','none',$pdo);
                        $accurate = rowsOf('nia_stock_filtered',"`diff` = '0'",$pdo);
                        $more = rowsOf('nia_stock_filtered',"nia_stock_filtered.physical > nia_stock_filtered.db_stock",$pdo);
                        $less = rowsOf('nia_stock_filtered',"nia_stock_filtered.physical < nia_stock_filtered.db_stock",$pdo);
                        // get less abounts
                        $lessValue = 0;
                        $lessAmountSql = $pdo->query("SELECT * FROM `nia_stock_filtered` WHERE nia_stock_filtered.physical < nia_stock_filtered.db_stock");
                        while($lessAmount = $lessAmountSql->fetch(PDO::FETCH_ASSOC))
                        {
                            $barcode = $lessAmount['barcode'];
                            if(is_numeric($lessAmount['diff']))
                            {
                                $diff = abs($lessAmount['diff']);
                            }
                            else
                            {
                                $diff = 1;
                            }


                            $itemQuery = "SELECT * FROM prod_mast WHERE barcode = '$barcode'";
                            $itemQueryParams = array();
                            $itemQueryOptions =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $itemQueryResult = sqlsrv_query($mycom, $itemQuery,$itemQueryParams,$itemQueryOptions);
                            $itemRowcount = sqlsrv_num_rows($itemQueryResult);

                            if($itemRowcount > 0)
                            {
                                $prodDetail = sqlsrv_fetch_array($itemQueryResult,2);
                                $retail = $prodDetail['retail1'] * $diff;
                                $lessValue += $retail;
                            }
                        }

                        // get more value
                        $moreValue = 0;
                        $moreAmountSql = $pdo->query("SELECT * FROM `nia_stock_filtered` WHERE nia_stock_filtered.physical > nia_stock_filtered.db_stock");
                        while($moreAmount = $moreAmountSql->fetch(PDO::FETCH_ASSOC))
                        {
                            $barcode = $moreAmount['barcode'];
                            if(is_numeric($moreAmount['diff']))
                            {
                                $diff = abs($moreAmount['diff']);
                            }
                            else
                            {
                                $diff = 1;
                            }


                            $itemQuery = "SELECT * FROM prod_mast WHERE barcode = '$barcode'";
                            $itemQueryParams = array();
                            $itemQueryOptions =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                            $itemQueryResult = sqlsrv_query($mycom, $itemQuery,$itemQueryParams,$itemQueryOptions);
                            $itemRowcount = sqlsrv_num_rows($itemQueryResult);

                            if($itemRowcount > 0)
                            {
                                $prodDetail = sqlsrv_fetch_array($itemQueryResult,2);
                                $retail = $prodDetail['retail1'] * $diff;
                                $moreValue += $retail;
                            }
                        }

                    ?>
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Count', 'Percentage'],
                                ['Accurate',     <?php echo $accurate ?>],
                                ['Less',     <?php echo $less ?>],
                                ['More',     <?php echo $more ?>],

                            ]);

                            var options = {
                                title: 'Stock Matching'
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>

                    <div class="w-100 h-100 bg-cmain">
                        <div class="container h-25">
                            <div class="row no-gutters h-100">

                                <div class="col-sm-3 p-2 h-100">
                                    <div class="card text-light bg-info cust_shadow h-100">
                                        <div class="card-header">
                                            <strong class="card-title">Total</strong>
                                            <kbd onclick="export_data('all')" class="float-right btn-dark pointer">PDF</kbd>
                                        </div>
                                        <div class="card-body">
                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                <strong><?php echo $all_count ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 p-2 h-100">
                                    <div class="card btn-info bg-success pointer cust_shadow h-100">
                                        <div class="card-header">
                                            <strong class="card-title">Accurate</strong>
                                            <kbd onclick="export_data('accurate')" class="float-right btn-dark pointer">PDF</kbd>
                                        </div>
                                        <div class="card-body">
                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                <strong><?php echo $accurate ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 p-2 h-100">
                                    <div class="card btn-info bg-warning pointer cust_shadow h-100">
                                        <div class="card-header">
                                            <strong class="card-title">More</strong>
                                            <kbd onclick="export_data('more_physical')" class="float-right btn-dark pointer">PDF</kbd>
                                        </div>
                                        <div class="card-body">
                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                <table class="table table-sm w-100">
                                                    <tr>
                                                        <td>Items</td>
                                                        <td><?php echo $more ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Value</td>
                                                        <td>GHS <?php echo number_format($moreValue,2) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3 p-2 h-100">
                                    <div class="card btn-info bg-danger pointer cust_shadow h-100">
                                        <div class="card-header">
                                            <strong class="card-title">Less</strong>
                                            <kbd onclick="export_data('less_physical')" class="float-right btn-dark pointer">PDF</kbd>
                                        </div>
                                        <div class="card-body">
                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">

                                                <table class="table table-sm w-100">
                                                    <tr>
                                                        <td>Items</td>
                                                        <td><?php echo $less ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Value</td>
                                                        <td>GHS <?php echo number_format($lessValue,2) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="container h-75 overflow-hidden p-2">
                            <div class="w-75 card mx-auto h-100 cust_shadow">
                                <div class="card-header">
                                    <strong class="card-title">Details </strong>
                                    <kbd onclick="take_plus()" class="float-right btn-info pointer mr-2">Take +</kbd>
                                    <kbd onclick="sub_total()" class="float-right btn-info pointer">Sub Total +</kbd>

                                </div>
                                <div class="card-body">
                                    <div class="w-100 h-100 overflow-auto">
                                        <div id="piechart" style="width: 100%; height: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php } ?>
            <?php endif; ?>

            <?php if($tool === 'pos'): ?>

                <div class="w-100 h-100">
                    <header class="bg-light-cyan d-flex flex-wrap align-content-center p-1">
                        <div class="dropdown">
                            <button type="button" class="btn bg-cus-muted btn-sm btn-light rounded-0 dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-id-card"></i> Users
                            </button>
                            <div class="dropdown-menu rounded-0 bg-cus-muted">
                                <a class="dropdown-item pointer" onclick="set_session('view=customers','<?php echo session_id() ?>')" >Customers</a>
                            </div>
                        </div>
                    </header>

                    <article>
                        <header></header>
                        <article>
                            <div class="w-100 d-flex flex-wrap align-content-center justify-content-center h-100">
                                <div class="w-50 h-50 card">
                                    <div class="card-header">
                                        <strong class="card-title">Users</strong>
                                        <div class="w-50 h-100 float-right d-flex flex-wrap justify-content-end align-content-center">
                                            <button class="btn btn-sm btn-outline-info mr-1"><i class="fa fa-sync"></i></button>
                                            <button data-toggle="modal" data-target="#newUser" class="btn btn-sm btn-outline-success mr-1"><i class="fa fa-add"></i></button>
                                            <div class="modal" id="newUser">
                                                <div class="modal-dialog">
                                                    <form method="post" action="config/proc/form/pos-processing.php" id="regular_form" class="modal-content">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="function" value="newClerk">
                                                            <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                                                <input type="tel" class="form-control rounded-0 border-info mb-2" name="phone_number" autocomplete="off" required autofocus placeholder="Phone Number">
                                                                <input type="text" class="form-control rounded-0 border-info mb-2" name="name" required autocomplete="off" placeholder="Full Name">

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-warning rounded-0" type="button" data-dismiss="modal">Cancel</button>
                                                            <button class="btn btn-success rounded-0" type="submit">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body overflow-hidden p-1">
                                        <div class="w-100 h-100 overflow-auto">
                                            <table class="table table-sm table-striped">
                                                <thead class="thead-dark">
                                                    <tr><th>Code</th><th>Name</th></tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $q = $spin_retail->query("select * from clerk_mast");
                                                    while($r = $q->fetch(PDO::FETCH_ASSOC)):
                                                ?>
                                                    <tr>
                                                        <td><?php echo $r['clrk_code'] ?></td>
                                                        <td><?php echo $r['clrk_name'] ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </article>

                </div>

            <?php endif; ?>

            <?php if($tool === 'spintex'): ?>

                <div class="w-100 container-fluid h-100 d-flex flex-wrap align-content-center justify-content-center">
                    <div class="container w-75 h-90 border">
                        <header>
                            <div onclick="triggerArrange()" class="w-100 py-1 h-100 d-flex flex-wrap">
                                <button class="btn btn-light rounded-0">Arrange</button>
                            </div>
                        </header>
                        <article id="arrandRest" class="bg-dark p-1 text-success overflow-auto">
                            >
                        </article>
                    </div>
                </div>

            <?php endif; ?>

        </div>

    </div>
</div>