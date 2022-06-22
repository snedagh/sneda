


    <div style="height: 100%; overflow: hidden !important;" class="container-fluid bg-ligh-danger p-0">
        <div class="row no-gutters h-100 overflow-hidden">

            <div class="col-sm-2 text-light h-100 overflow-hidden bg-dark">

                <header class="d-flex flex-wrap pl-2 align-content-center">
                    <strong>Admin Panel</strong>
                </header>

                <article class="p-1">



                    <!-- USER MANAGEMENT -->
                    <button onclick="set_session('tool=user_management,view=all','<?php echo $token ?>')" class="btn <?php if($tool === 'user_management'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                        <span class="fa fa-users"></span> User Management
                    </button>
                    <!--USER MANAGEMENT-->

                    <!-- APPS -->
                    <button onclick="set_session('tool=applications,view=all','<?php echo $token ?>')" class="btn <?php if($tool === 'applications'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                        <span class="fa fa-exchange"></span> Applications
                    </button>
                    <!-- APPS -->


                    <!--TASK MANAGER-->
                    <button onclick="set_session('tool=task_manager,view=all,new_stage=0','<?php echo $token ?>')" class="btn <?php if($tool === 'task_manager'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                        <span class="fa fa-tasks"></span> Task Manager
                    </button>
                    <!--TASK MANAGER-->

                    <!-- DB SYNC -->
                    <button onclick="set_session('tool=sync_db,view=all','<?php echo $token ?>')" class="btn <?php if($tool === 'sync_db'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                        <span class="fa fa-database"></span> Database Sync
                    </button>
                    <!-- DB SYNC -->

                    <!-- Stock -->
                    <button  data-toggle="collapse" data-target="#stock" class="btn <?php if($tool === 'stock'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left mb-2 w-100" data-target="#inventory_collapse">
                        <span class="fa fa-boxes-stacked"></span> Stock
                    </button>
                    <div id="stock" class="collapse pl-4 mb-2">
                        <span onclick="set_session('tool=stock,view=all','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-eye"></span> View Stock</span>
                    </div>

                    <!-- Stock -->

                    <!-- INVENTORY -->

                    <button data-toggle="collapse" class="btn <?php if($tool === 'inventory'){echo 'r_nav_active';} else {echo 'r_nav';} ?> text-light text-left w-100" data-target="#inventory_collapse">
                        <span class="fa fa-boxes-stacked"></span> Inventory
                    </button>

                    <div id="inventory_collapse" class="collapse pl-4">
                        <span onclick="set_session('tool=inventory,todo=new_asset,stage=asset_type,view=all','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-add"></span> Add Asset</span>
                        <hr class="m-1">
                        <span onclick="set_session('tool=inventory,todo=view_workstation,view=all','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-desktop"></span> Workstations</span>
                        <hr class="m-1">
                        <span onclick="set_session('tool=inventory,todo=attendance,view=all','<?php echo $token ?>')" class="r_child pointer"><span class="fa fa-desktop"></span> Attendance</span>
                    </div>



                    <!-- INVENTORY -->

                </article>

            </div>
            <div class="col-sm-10 h-100">
                <?php
                    if($tool == 'task_manager')
                    {
                        require_once 'config/include/parts/admin_panel/task_manager.php';
                    }
                    elseif  ($tool == 'user_management')
                    {
                        require_once 'config/include/parts/admin_panel/user_mgmt.php';
                    }
                    elseif ($tool == 'sync_db')
                    {
                        require_once 'config/include/parts/admin_panel/sync_db.php';
                    } elseif ($tool == 'stock')
                    {
                        require_once 'config/include/parts/admin_panel/stock.php';
                    } elseif ($tool == 'applications')
                    {
                        if(!isset($_SESSION['view']))
                        {
                            $_SESSION['view'] = 'all';
                        }
                        $view = $_SESSION['view'];
                        require_once 'config/include/parts/admin_panel/applications/index.php';
                    }
                    elseif ($tool == 'inventory')
                    {
                        $todo = getSession('todo');
                        $stage = getSession('stage');
//                        echo $todo;
//                        die();
                        require_once 'config/include/parts/admin_panel/inventory.php';
                    }
                ?>
            </div>

        </div>
    </div>