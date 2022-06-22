
    <div class="w-100 h-100 d-flex flex-wrap overflow-auto align-content-center justify-content-center bg-light">
        <?php if($todo === 'new_asset'): ?>

            <?php if($stage === 'asset_type'): ?>

                <div class="card shadow w-25 p-2">
                <div class="card-header">
                    <strong class="card-title">Asset Type</strong>
                </div>
                
                <div class="card-body p-2">
                    <select name="" id="asset_type" class="form-control rounded-0">
                        <option value="xxx">Select Asset Type</option>
                        <option value="work_station">Workstation</option>
                        <option value="stock">Stock</option>
                    </select>
                </div>
                
            </div>

            <?php endif; ?>
            <?php if($stage === 'work_station'): //todo submit new workstation form ?>

            <form id="regular_form" method="post" action="config/proc/form_process.php" class="card h-100 shadow w-50">
                <input type="hidden" name="function" value="new_workstation">
                <input type="hidden" name="token" value="<?php echo session_id() ?>">
                <div class="card-header bg-track-head">
                    <strong class="card-title">Workstation Details</strong>
                    <div class="float-right d-flex flex-wrap justify-content-end align-content-center h-100">
                        <button onclick="set_session('tool=inventory,todo=view_workstation,view=all','<?php echo $token ?>')" type="button" class="btn btn-sm btn-danger mx-1">Cancel</button>
                        <button type="reset" class="btn btn-sm btn-warning mx-1">Reset</button>
                        <button type="submit" class="btn btn-sm btn-light mx-1">Save</button>
                    </div>
                </div>

                <div class="card-body bg-modal py-2">
                    <div class="w-100 p-0 containe h-100 overflow-auto">
                        <div class="row no-gutters">
                            <div class="col-sm-12 px-2">
                                <p class="font-weight-bolder m-0"> Owner</p>
                            </div>
                            <!-- Owner -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Owner" name="owner" id="owner" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Location -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <select required name="department" id="department" class="form-control rounded-0">
                                    <option disabled selected >Department</option>
                                    <?php
                                    $cps = $pdo->query('select * from departments');
                                    while ($cpu = $cps->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $cp_id = $cpu['id'];
                                        $cp_desc = $cpu['desc'];
                                        echo "<option value='$cp_id'>$cp_desc</option>" ;
                                    }

                                    ?>
                                </select>
                            </div>



                            <div class="col-sm-12 px-2">
                                <p class="font-weight-bolder m-0"> CPU Details</p>
                            </div>

                            <!-- OS -->
                            <div class="col-sm-12 p-2 input-group-sm">
                                <select required name="os" id="os" class="form-control rounded-0">
                                    <option disabled selected >Operating System</option>
                                    <optgroup label="Windows Servers">
                                        <option>Windows Server 2012</option>
                                        <option>Windows Server 2016</option>
                                    </optgroup>
                                    <optgroup label="Windows Clients">
                                        <option>Windows 7</option>
                                        <option>Windows 8</option>
                                        <option>Windows 10</option>
                                        <option>Windows 11</option>
                                    </optgroup>
                                    <optgroup label="Linux">
                                        <option>Kali</option>
                                        <option>Ubuntu</option>
                                    </optgroup>
                                </select>
                            </div>
                            <!-- Manufacturer -->
                            <div class="col-sm-6 p-2 input-group-sm">

                                <label for="cpu">Manufacturer</label><select required name="cpu" id="cpu" class="form-control rounded-0">
                                    <option disabled selected >CPU Brand</option>
                                    <?php
                                        $cps = $pdo->query('select * from brand_cpu');
                                        while ($cpu = $cps->fetch(PDO::FETCH_ASSOC))
                                        {
                                            $cp_id = $cpu['id'];
                                            $cp_desc = $cpu['desc'];
                                            echo "<option value='$cp_id'>$cp_desc</option>" ;
                                        }

                                    ?>
                                </select>

                            </div>

                            <!-- TYPYE -->
                            <div class="col-sm-6 p-2 input-group-sm">

                                <label for="cpu_type">Type</label><select required name="cpu_type" id="cpu_type" class="form-control rounded-0">
                                    <option disabled selected >CPU Type</option>
                                    <option value="Tower">Tower</option>
                                    <option value="Desktop">Desktop</option>
                                </select>

                            </div>

                            <!-- Model -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Model" name="cpu_model" id="model" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Serial -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Serial" name="serial" id="serial" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- HDD SIZE -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="HDD Size" name="hdd_size" id="hdd_size" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- RAM SIZE -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="RAM Size" name="ram_size" id="ram_size" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Ram Type -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Ram Type" name="ram_type" id="ram_type" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Processor -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Processor" name="processor" id="processor" class="form-control rounded-0" required autocomplete="off">
                            </div>


                            <div class="col-sm-12 px-2">
                                <p class="font-weight-bolder m-0"> Monitor Details</p>
                            </div>

                            <!-- Monitor Brand -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <label for="mon_man">Manufacturer</label><select name="mon_man" id="mon_man" class="form-control rounded-0">
                                    <option disabled selected >Monitor Brand</option>
                                    <?php
                                    $cps = $pdo->query('select * from brand_mon');
                                    while ($cpu = $cps->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $cp_id = $cpu['id'];
                                        $cp_desc = $cpu['desc'];
                                        echo "<option value='$cp_id'>$cp_desc</option>" ;
                                    }

                                    ?>
                                </select>
                            </div>

                            <!-- Model -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <label for="mon_model">Model</label>
                                <input type="text" placeholder="Model" name="mon_model" id="mon_model" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Serial -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Serial" name="mon_serial" id="mon_serial" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Dimension -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Dimension" name="mon_dimension" id="mon_dimension" class="form-control rounded-0" required autocomplete="off">
                            </div>


                            <div class="col-sm-12 px-2">
                                <p class="font-weight-bolder m-0"> Peripherals</p>
                            </div>

                            <!-- UPS -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="UPS" name="ups" id="ups" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- PRINTER -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Printer" name="printer" id="printer" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- MOUSE -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Mouse" name="mouse" id="mouse" class="form-control rounded-0" required autocomplete="off">
                            </div>

                            <!-- Keyboard -->
                            <div class="col-sm-6 p-2 input-group-sm">
                                <input type="text" placeholder="Keyboard" name="keyboard" id="keyboard" class="form-control rounded-0" required autocomplete="off">
                            </div>








                        </div>
                    </div>
                </div>

            </form>

        <?php endif; ?>

            <script>
                console.log('hello')
                $('#asset_type').on('change', function() {
                   if(this.value === 'xxx')
                   {
                       $('#asset_type').addClass('border-danger')
                   }
                   else
                   {
                       $('#asset_type').removeClass('border-danger')

                       set_session("stage="+this.value,"<?php echo $token ?>")

                       // prepare forms

                   }
                });
            </script>


        <?php endif; ?>

        <?php if($todo === 'view_workstation'):
            $view = $_SESSION['view'];
            if($view === 'all'){
            ?>

                <form method="post" action="config/proc/form_process.php" class="modal fade in" id="regular_form">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <strong class="modal-title" id="edit_pc_item_title"></strong>
                                <div class="float-right d-flex flex-wrap justify-content-end">
                                    <button data-dismiss="modal" class="btn btn-sm btn-warning rounded-0 mr-1" type="button">CANCEL</button>
                                    <button class="btn btn-sm btn-success rounded-0 ml-2" type="submit">SAVE</button>
                                </div>
                            </div>
                            <div class="modal-body p-2">
                                <div id="edit_pc_item_form" class="w-75 mx-auto"></div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="w-100 h-100 overflow-hidden p-1 flex-wrap">

                <header>
                    <div class="w-100 h-100 d-flex justify-content-end align-content-center">
                        <div class="h-100 w-20 d-flex flex-wrap align-content-center justify-content-end">
                            <span onclick="set_session('todo=new_asset,stage=work_station','<?php echo $token ?>')" title="New Workstation" class="pointer fa fa-desktop text-info"></span>
                        </div>
                    </div>
                </header>

                <article class="overflow-auto">
                    <div class="w-100 table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-dark">
                            <tr>
                                <th>AV</th>
                                <th>User</th>
                                <th>System Unit</th>
                                <th>RAM</th>
                                <th>Storage</th>
                                <th>Others</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $devices = $pdo->query("SELECT * FROM `devices`");

                            while($device = $devices->fetch(PDO::FETCH_ASSOC)):
                                $dev_uni = $device['uni'];
                                ?>

                                <tr class="pointer">
                                    <td onclick="set_session('view=single,device=<?php echo $dev_uni ?>','<?php echo $token ?>')">
                                        <kbd class="badge badge-success fa fa-toggle-on"></kbd>
                                    </td>
                                    <td><?php echo $device['user'] ?></td>
                                    <td><?php $m_brand = $device['cpu'];
                                        echo fetchFunc('brand_cpu',"`id` = '$m_brand'",$pdo)['desc']; ?> <kbd><?php echo  $device['cpu_model'] ?></kbd></td>
                                    <td><?php
                                        $m_brand = $device['monitor_brand'];
                                        echo $device['ram_size'];
                                        ?></td>
                                    <td><?php echo $device['hdd_size'] ?></td>
                                    <td>
                                        <span onclick="edit_pc_item('mouse','<?php echo $dev_uni ?>')" class="badge badge-info w-20"><span class="fa fa-computer-mouse"></span> <?php echo $device['mouse'] ?></span>
                                        <span onclick="edit_pc_item('keyboard','<?php echo $dev_uni ?>')" class="badge badge-dark w-20"><span class="fa fa-keyboard"></span> <?php echo $device['keyboard'] ?></span>
                                        <span onclick="edit_pc_item('phe_printer','<?php echo $dev_uni ?>')" class="badge badge-primary w-20"><span class="fa fa-print"></span> <?php echo substr($device['phe_printer'],0,5) ?></span>
                                        <span onclick="edit_pc_item('phe_ups','<?php echo $dev_uni ?>')" class="badge badge-danger w-20"><span class="fa fa-power-off"></span> <?php echo substr($device['phe_ups'],0,5) ?></span>
                                    </td>
                                </tr>

                            <?php endwhile; ?>
                            </tbody>

                        </table>
                    </div>
                </article>

            </div>
            <?php } elseif ($view === 'single') {
            $dev = $_SESSION['device'];
            $dev_info = fetchFunc('devices',"`uni` = '$dev'",$pdo);
            ?>
            <div class="w-100 h-100 p-1">
                <header>
                    <div class="w-100 h-100 d-flex justify-content-between align-content-center">

                        <div class="h-100 w-50 d-flex flex-wrap align-content-center">
                            <strong>System Information</strong>
                        </div>


                        <div class="h-100 w-20 d-flex flex-wrap align-content-center justify-content-end">
                            <span onclick="set_session('tool=inventory,todo=view_workstation,view=all','<?php echo $token ?>')" title="All Workstations" class="pointer fa fa-boxes-stacked m-1 text-primary"></span>

                            <span data-toggle="modal" data-target="#makeReort" title="Report" class="pointer fa fa-warning m-1 text-danger"></span>
                            <div class="modal fade" id="makeReort">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <form method="post" action="config/proc/form_process.php" id="regular_form" class="modal-content">

                                        <div class="modal-header">
                                            <strong class="modal-title">New Task</strong>
                                            <div class="float-right d-flex">
                                                <button class="btn btn-success btn-sm mx-2" type="submit">SAVE</button>
                                                <button type="button" data-dismiss="modal" class="btn btn-warning btn-sm mx-2">CLOSE</button>
                                            </div>
                                        </div>

                                        <div class="modal-body p-2">

                                            <div>

                                                <input type="hidden" value="new_task" name="function">
                                                <!--OWNER TITLE-->
                                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">
                                                    <!--OWNER-->
                                                    <div class="form-group w-45">
                                                        <label>Owner</label>
                                                        <input required type="text" name="owner" value="<?php echo $dev_info['user'] ?>" class="form-control form-control-sm cust_shadow_innser">
                                                        <small class="text-info">Who reported the issue?</small>
                                                    </div>

                                                    <div class="form-group w-45">
                                                        <label>Title</label>
                                                        <input maxlength="15" required type="text" name="title" class="form-control form-control-sm cust_shadow_innser">
                                                        <small class="text-info">Short description of issues</small>
                                                    </div>

                                                </div>

                                                <!--ASSIGN-->
                                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">

                                                    <!--DEVICE -->
                                                    <div class="form-group w-45">
                                                        <label>Target Devices</label>
                                                        <select class="form-control form-control-sm cust_shadow_innser" name="device">
                                                            <?php
                                                                $dev_query = $pdo->query("SELECT * FROM `devices`");
                                                                while($dev_x = $dev_query->fetch(PDO::FETCH_ASSOC)):
                                                                    $uni = $dev_x['uni'];
                                                                    $dev_name = $dev_x['user'];
                                                            ?>

                                                                <?php if($dev == $uni): ?>
                                                                    <option value="<?php echo $uni ?>"><?php echo $dev_name ?></option>
                                                                <?php endif; ?>


                                                                <?php endwhile;
                                                            ?>
                                                        </select>
                                                        <div id="WRONGD">

                                                        </div>

                                                    </div>

                                                    <!--ASSIGN TO -->
                                                    <div class="form-group w-45">
                                                        <label>Asign To</label>
                                                        <select class="form-control form-control-sm cust_shadow_innser" name="assigned_to">
                                                            <?php
                                                            $users = $pdo->query("SELECT * FROM `users`");
                                                            while($user = $users->fetch(PDO::FETCH_ASSOC)):
                                                                $uid = $user['id'];
                                                                $uname = $user['first_name'] . ' ' . $user['last_name'];
                                                                ?>
                                                                <option value="<?php echo $uid ?>"><?php echo $uname ?></option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                        <div id="WRONGD">

                                                        </div>

                                                    </div>



                                                </div>

                                                <!--RELATIVE AND DOMAIN-->
                                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">

                                                    <!--DOMAIN-->
                                                    <div class="form-group w-45">
                                                        <label>Domain</label>
                                                        <select class="form-control form-control-sm cust_shadow_innser" name="domain">
                                                            <option value="0">Select Domain</option>
                                                            <optgroup label="Internal">
                                                                <option value="SM">Sneda Motors</option>
                                                                <option value="SSM">Sneda Smart Meters</option>
                                                                <option value="SSC">Sneda Shopping Center</option>
                                                                <option value="ST">Sneda Transformers</option>
                                                            </optgroup>
                                                            <optgroup label="External">
                                                                <option value="COMSYS">COMSYS</option>
                                                                <option value="MYCOM">MYCOM</option>
                                                                <option value="RELISH">Relish</option>
                                                            </optgroup>
                                                        </select>
                                                        <div id="WRONGD">
                                                            <small class="text-info">Where is the issue affecting?</small>
                                                        </div>

                                                    </div>

                                                    <!--RELATIVE-->
                                                    <div class="form-group w-45">
                                                        <label>Relative</label>
                                                        <select class="form-control form-control-sm cust_shadow_innser" name="relative">
                                                            <option value="0">Select Relative</option>
                                                            <option value="Networking">Networking</option>
                                                            <option value="Hardware">Hardware</option>
                                                            <option value="Update">Update</option>
                                                        </select>
                                                        <div id="WRONGR">
                                                            <small class="text-info">Which part of productivity is affected?</small>
                                                        </div>

                                                    </div>


                                                </div>



                                                <!--DETAILS-->
                                                <div class="w-100 mb-2">
                                                    <label>Details</label>
                                                    <textarea name="details" required class="form-control cust_shadow_innser" rows="2"></textarea>
                                                    <small class="text-info">Explain issue clearly</small>
                                                </div>
                                                <input type="hidden" name="token" value="<?php echo session_id() ?>">




                                            </div>


                                        </div>

                                    </form>
                                </div>
                            </div>

                            <span onclick="msinfo32('<?php echo $dev ?>')" title="Print" class="pointer fa fa-print m-1 text-info"></span>
                            <div class="modal fade in" id="msinfo32" data-keyboard="false" data-backdrop="static">
                                <div id="modal_change" class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div id="m_body" class="modal-body">
                                            <div id="pdf">
                                                <div class="text-center"">
                                                    <div class="spinner-grow text-muted"></div>
                                                    <div class="spinner-grow text-primary"></div>
                                                    <div class="spinner-grow text-success"></div>
                                                    <div class="spinner-grow text-info"></div>
                                                    <div class="spinner-grow text-warning"></div>
                                                    <div class="spinner-grow text-danger"></div>
                                                    <div class="spinner-grow text-secondary"></div>
                                                    <div class="spinner-grow text-dark"></div>
                                                    <div class="spinner-grow text-light"></div>
                                                </div>

                                                <div class="text-center">
                                                    <kbd>Processing Document....</kbd><br>
                                                    <button data-dismiss="modal" class="btn mt-5 btn-info rounded-0 btn-sm">CANCEL</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <span onclick="set_session('view=edit,device=<?php echo $dev ?>','<?php echo $token ?>')" title="Edit" class="pointer fa fa-edit m-1 text-info"></span>
                        </div>
                    </div>
                </header>
                <article class="overflow-auto">

                    <div class="w-75 h-100 mx-auto p-2">

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>User</td><td><?php echo $dev_info['user'] ?></td>
                                <tr>
                                <tr>
                                    <td>Operating System</td><td><?php echo $dev_info['os'] ?></td>
                                <tr>
                                    <td>Lask Know IP</td><td><?php echo $dev_info['ip_addr'] ?></td>
                                </tr>
                                </tr>

                                <tr>
                                    <td>Department</td>
                                    <td>
                                        <?php
                                        $department = $dev_info['department'];
                                        echo fetchFunc('departments',"`id` = '$department'",$pdo)['desc'];
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>System Manufacturer</td>
                                    <td>
                                        <?php
                                        $department = $dev_info['cpu'];
                                        echo fetchFunc('brand_cpu',"`id` = '$department'",$pdo)['desc'];
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bolder">System Type</td><td><?php echo $dev_info['cpu_type'] ?></td>
                                </tr>
                                <tr>
                                    <td>System Model</td><td><?php echo $dev_info['cpu_model'] ?></td>
                                </tr>
                                <tr>
                                    <td>System SKU</td><td><?php echo $dev_info['cpu_serial'] ?></td>
                                </tr>
                                <tr>
                                    <td>HDD SIZE</td><td><?php echo $dev_info['hdd_size'] ?></td>
                                </tr>
                                <tr>
                                    <td>RAM Size</td><td><?php echo $dev_info['ram_size'] ?></td>
                                </tr>
                                <tr>
                                    <td>RAM Type</td><td><?php echo $dev_info['ram_type'] ?></td>
                                </tr>
                                <tr>
                                    <td>Processor</td><td><?php echo $dev_info['processor'] ?></td>
                                </tr>

                                <tr>
                                    <td>Monitor</td>
                                    <td>
                                        <?php
                                        $department = $dev_info['monitor_brand'];
                                        echo fetchFunc('brand_mon',"`id` = '$department'",$pdo)['desc'];
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Monitor Model</td><td><?php echo $dev_info['monitor_model'] ?></td>
                                </tr>

                                <tr>
                                    <td>Monitor Serial</td><td><?php echo $dev_info['monitor_serial'] ?></td>
                                </tr>

                                <tr>
                                    <td>Monitor Dimension</td>
                                    <td><?php echo $dev_info['monitor_dimension'] ?></td>

                                </tr>

                                <tr>
                                    <td>Keyboard</td>
                                    <td><?php echo $dev_info['keyboard'] ?></td>
                                </tr>

                                <tr>
                                    <td>Mouse</td>
                                    <td><?php echo $dev_info['mouse'] ?></td>
                                </tr>

                                <tr>
                                    <td>UPS</td>
                                    <td><?php echo $dev_info['phe_ups'] ?></td>
                                </tr>

                                <tr>
                                    <td>Printer</td>
                                    <td><?php echo $dev_info['phe_printer'] ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </article>
            </div>
            <?php } elseif ($view === 'edit') { $dev = $_SESSION['device']; ?>


            <?php
                $dev_info = fetchFunc('devices',"`uni` = '$dev'",$pdo);
            ?>
            <div class="w-100 h-100 p-1">
                <header>
                    <div class="w-100 h-100 d-flex justify-content-between align-content-center">

                        <div class="h-100 w-50 d-flex flex-wrap align-content-center">
                            <strong>System Information</strong>
                        </div>


                        <div class="h-100 w-20 d-flex flex-wrap align-content-center justify-content-end">
                            <button type="button" onclick="set_session('tool=inventory,todo=view_workstation,view=all','<?php echo $token ?>')" title="Cancel" class="pointer m-1 btn btn-warning btn-sm">CANCEL</button>
                            <button type="submit" form="regular_form" title="Cancel" class="pointer m-1 btn btn-success btn-sm">UPDATE</button>
                        </div>
                    </div>
                </header>
                <article class="overflow-auto">

                    <div class="w-75 h-100 mx-auto p-2">

                        <form method="post" action="config/proc/form_process.php" id="regular_form" class="table-responsive">
                            <input type="hidden" name="token" value="<?php echo $token ?>">
                            <input type="hidden" name="function" value="update_workstation">
                            <table class="table table-sm table-bordered table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Value</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>User</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo $dev_info['user'] ?>" name="user"></td>
                                <tr>
                                <tr>
                                    <td>Operating System</td>
                                    <td>
                                        <?php
                                            $os = $dev_info['os'];
                                        ?>
                                        <select name="os" id="" class="form-control form-control-sm">
                                            <?php echo "<option value='$os' selected>$os</option>"; ?>
                                            <optgroup label="Windows Servers">
                                                <option>Windows Server 2012</option>
                                                <option>Windows Server 2016</option>
                                            </optgroup>
                                            <optgroup label="Windows Clients">
                                                <option>Windows 7</option>
                                                <option>Windows 8</option>
                                                <option>Windows 10</option>
                                                <option>Windows 11</option>
                                            </optgroup>
                                            <optgroup label="Linux">
                                                <option>Kali</option>
                                                <option>Ubuntu</option>
                                            </optgroup>

                                        </select>
                                    </td>
                                <tr>
                                    <td>Lask Know IP</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo $dev_info['ip_addr'] ?>" name="ip_addr"></td>
                                </tr>
                                </tr>

                                <tr>
                                    <td>Department</td>
                                    <td>
                                        <?php
                                            $sel_dep = $dev_info['department'];
                                            $all_departments = $pdo->query("SELECT * FROM `departments`");
                                        ?>
                                        <select name="department" id="" class="form-control form-control-sm">
                                            <?php while($m = $all_departments->fetch(PDO::FETCH_ASSOC)){
                                                $m_id = $m['id']; $m_desc = $m['desc'];
                                                if($m_id === $sel_dep)
                                                {
                                                    echo "<option value='$m_id' selected>$m_desc</option>";
                                                }
                                                else
                                                {
                                                    echo "<option value='$m_id'>$m_desc</option>";
                                                }
                                            }?>

                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>System Manufacturer</td>
                                    <td>
                                        <?php
                                            $sel_cpu = $dev_info['cpu_type'];
                                            $all_cpu = $pdo->query("SELECT * FROM `brand_cpu`");
                                        ?>
                                        <select name="cpu_type" id="" class="form-control form-control-sm">
                                            <?php while($m = $all_cpu->fetch(PDO::FETCH_ASSOC)){
                                                $m_id = $m['id']; $m_desc = $m['desc'];
                                                if($m_id === $sel_cpu)
                                                {
                                                    echo "<option value='$m_id' selected>$m_desc</option>";
                                                }
                                                else
                                                {
                                                    echo "<option value='$m_id'>$m_desc</option>";
                                                }
                                            }?>

                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bolder">System Type</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['cpu_type'] ?>" name="system_type"></td>
                                </tr>

                                <tr>
                                    <td>System Module</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['cpu_model'] ?>" autocomplete="off" name="system_module"></td>
                                </tr>
                                <tr>
                                    <td>System SKU</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['cpu_serial'] ?>" autocomplete="off" name="system_serial"></td>
                                </tr>
                                <tr>
                                    <td>HDD SIZE</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['hdd_size'] ?>" autocomplete="off" name="hdd_size"></td>
                                </tr>
                                <tr>
                                    <td>RAM Size</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['ram_size'] ?>" autocomplete="off" name="ram_size"></td>
                                </tr>
                                <tr>
                                    <td>RAM Type</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['ram_type'] ?>" autocomplete="off" name="ram_type"></td>
                                </tr>
                                <tr>
                                    <td>Processor</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['processor'] ?>" autocomplete="off" name="processor"></td>
                                </tr>

                                <tr>
                                    <td>Monitor</td>
                                    <td>
                                        <?php
                                            $sel_mon = $dev_info['monitor_brand'];
                                            $all_monitors = $pdo->query("SELECT * FROM `brand_mon`");
                                        ?>
                                        <select name="monitor_brand" id="" class="form-control form-control-sm">
                                            <?php while($m = $all_monitors->fetch(PDO::FETCH_ASSOC)){
                                                $m_id = $m['id']; $m_desc = $m['desc'];
                                                if($m_id === $sel_mon)
                                                {
                                                    echo "<option value='$m_id' selected>$m_desc</option>";
                                                }
                                                else
                                                {
                                                    echo "<option value='$m_id'>$m_desc</option>";
                                                }
                                            }?>

                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Monitor Model</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['monitor_model'] ?>" autocomplete="off" name="monitor_model"></td>
                                </tr>

                                <tr>
                                    <td>Monitor Serial</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['monitor_serial'] ?>" autocomplete="off" name="monitor_serial"></td>
                                </tr>

                                <tr>
                                    <td>Monitor Dimension</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['monitor_dimension'] ?>" autocomplete="off" name="monitor_dimension"></td>

                                </tr>

                                <tr>
                                    <td>Keyboard</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['keyboard'] ?>" autocomplete="off" name="keyboard"></td>
                                </tr>

                                <tr>
                                    <td>Mouse</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['mouse'] ?>" autocomplete="off" name="mouse"></td>
                                </tr>

                                <tr>
                                    <td>UPS</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['phe_ups'] ?>" autocomplete="off" name="phe_ups"></td>
                                </tr>

                                <tr>
                                    <td>Printer</td>
                                    <td><input class="form-control form-control-sm" type="text" required value="<?php echo  $dev_info['phe_printer'] ?>" autocomplete="off" name="phe_printer"></td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                </article>
            </div>
        <?php } ?>






        <?php endif; ?>

        <?php if($todo === 'attendance'):



        ?>
            <div class="w-100 h-100">




            </div>
        <?php  endif; ?>

    </div>

