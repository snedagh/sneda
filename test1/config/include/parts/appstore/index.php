<div class="w-100 h-100 bg-bone container-fluid overflow-hidden">
    <div class="row h-100 overflow-hidden">
        <!-- NAV -->
        <div class="col-sm-3 h-100 overflow-hidden p-2 bg-light">
            <div class="h-15 p-1 shadow-lg">
                <strong>Seacrh for an app</strong>
                <!-- SEARCH APP -->
                <input type="search" placeholder="Search" id="searchForApp" class="form-control">
            </div>

            <!-- SEARCH RESULT -->
            <div id="app_res" class="w-100 p-2 pt-5 h-85 overflow-auto">



            </div>
        </div>

        <!-- APPS -->
        <div class="col-sm-9 p-5 h-100 overflow-auto">
            <?php if($view == 'all'): ?>

                <div class="row">

                    <?php while ($app = $apps_stmt->fetch(PDO::FETCH_ASSOC)):
                        $uni = $app['uni'];
                        ?>
                        <div class="col-sm-3 p-2">
                            <div onclick="set_session('view=single,uni=<?php echo $uni ?>','<?php echo session_id() ?>')" class="card pointer btn-light">
                                <img src="assets/icons/apps_icon/def.jpg" class="img-fluid">
                                <div class="card-body p-2">
                                    <h5 class="text_eclips"><?php echo $app['name'] ?></h5>
                                    <p class="text_eclips text-muted m-0"><?php echo $app['details'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>

            <?php endif; ?>

            <?php if($view == 'single'): ?>

                <?php if($app_exist == 'no'): ?>

                    can't load app, contact sysadmin <?php print_r($_SESSION) ?>

                <?php endif;  ?>


                <?php if($app_exist == 'yes'): ?>
                    <!-- TOP -->
                    <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">
                        <!-- IMAGE -->
                        <div class="w-50 d-flex flex-wrap align-content-center justify-content-center">
                            <div class="w-40 text-center card">
                                <img src="assets/icons/apps_icon/def.jpg" class="img-fluid">
                                <div class="card-body p-2">
                                    <h5 class="text_eclips"><?php echo $ap['name'] ?></h5>
                                    <small>V0.01</small>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="w-50 d-flex flex-wrap align-content-center justify-content-center">
                            <div class="w-50 d-flex flex-wrap align-content-center justify-content-between text-center">
                                <?php
                                    $installed_app = rowsOf("$my_apps","`app` = '$uni'",$pdo);
                                    if($installed_app == 1)
                                    {
                                        echo '<button onclick="uninstall_app(\'are you sure you want to uninstall app?\',\''.$uni.'\')" class="btn w-40 btn-sm btn-warning">Uninstall</button>';
                                    } elseif ($installed_app == 0)
                                    {
                                        echo '<button onclick="install_app(\'are you sure you want to intall app?\',\''.$uni.'\')" class="btn w-40 btn-sm btn-success">Install</button>';
                                    }
                                ?>

                            </div>
                        </div>

                    </div>

                    <hr>
                    <div class="bg-light p-2">
                        <strong>Details</strong>
                        <p>
                            <?php echo $ap['details'] ?>
                        </p>
                        <hr>
                        <strong>Screenshots</strong>
                        <div class="row no-gutters">
                            <div class="col-sm-4 p-2">
                                <img src="assets/icons/apps_icon/def.jpg" class="img-fluid img-thumbnail">
                            </div>
                            <div class="col-sm-4 p-2">
                                <img src="assets/icons/apps_icon/def.jpg" class="img-fluid img-thumbnail">
                            </div>
                            <div class="col-sm-4 p-2">
                                <img src="assets/icons/apps_icon/def.jpg" class="img-fluid img-thumbnail">
                            </div>
                        </div>

                    </div>
                <?php endif; ?>
                    <div class="w-100 text-center m-2">
                        <button onclick="set_session('view=all','<?php echo session_id() ?>')" class="btn btn-info">APPS STORE</button>
                    </div>
            <?php endif; ?>

        </div>

    </div>
</div>