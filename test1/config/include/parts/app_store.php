<div class="container-fluid h-100 overflow-hidden bg-light-cyan">
    <div class="container h-100">
        <header class="bg-dark text-light p-2 d-flex flex-wrap align-content-center justify-content-between">
            <div class="h-100 w-25 d-flex flex-wrap align-content-center">
                <p class="m-0 font-weight-bolder">AppStore</p>
            </div>

            <div id="info" class="h-100 w-45 text-center text-info font-italic d-flex flex-wrap justify-content-center align-content-center">

            </div>

            <div class="h-100 d-flex flex-wrap align-content-center w-30 justify-content-end">
                <input class="form-control form-control-sm w-50 rounded-0" autocomplete="off">
                <button class="btn btn-sm btn-info rounded-0">FIND</button>
            </div>
        </header>

        <article class="p-2 d-flex flex-wrap overflow-auto align-content-start">
            <?php while ($app = $apps_stmt->fetch(PDO::FETCH_ASSOC)): ?>

                <?php
                $app_trigger = $app['app_trigger'];
                $uni = $app['uni'];
                ?>

                <!--APPLICATION-->
                <div class="w-20 dropdown dropright app_card p-2">

                    <div class="card w-100 h-100 hover-independence cust_shadow pointer">

                        <div class="w-100 h-100 d-flex flex-wrap">

                            <!--APP ICON-->
                            <div class="w-40 overflow-hidden d-flex flex-wrap align-content-center justify-content-center h-100">
                                <?php
                                $icon = $app['icon'];

                                if(is_file("assets/icons/apps_icon/$icon"))
                                {
                                    $image = 'assets/icons/apps_icon/'.$icon;

                                }
                                else
                                {
                                    $image = 'assets/icons/apps_icon/application.png';
                                }
                                // Read image path, convert to base64 encoding
                                $imageData = base64_encode(file_get_contents($image));

                                // Format the image SRC:  data:{mime};base64,{data};
                                $src = 'data: '.mime_content_type($image).';base64,'.$imageData;

                                ?>
                                <div class="app_icon overflow-hidden">
                                    <img src="<?php echo $src ?>" class="img-fluid">
                                </div>
                            </div>

                            <!--APP DETAILS-->
                            <div class="w-60 overflow-hidden h-100 p-2">
                                <div class="w-100 app_detail d-flex flex-wrap align-content-center">
                                    <p class="text_eclips p-0 m-0"><strong><?php echo $app['name'] ?></strong></p>
                                    <p class="line-clamp-4 text-muted m-0 line-h text_sm p-0">
                                        <?php echo substr($app['details'], 0 , 30)."...." ?>
                                    </p>
                                </div>
                            </div>


                        </div>

                    </div>
                    <span data-toggle="dropdown" class="block2 pointer">
                                            <img src="assets/icons/home/install_application.png" class="img-fluid">
                                    </span>
                    <div class="dropdown-menu">
                        <a class="dropdown-item text-info pointer" data-toggle="modal" data-target="#info<?php echo $uni ?>">Details</a>
                        <?php
                        $my_apps = 'user_'.$_SESSION['user_id'].'_apps';
                        if (rowsOf($my_apps,"`app` = '$uni'", $pdo) < 1)
                        { ?>
                            <a onclick="installApplication('i','<?php echo $uni ?>','<?php echo session_id() ?>')"  class="dropdown-item text-success pointer">Install</a>
                        <?php } else { ?>
                            <a onclick="installApplication('u','<?php echo $uni ?>','<?php echo session_id() ?>')" class="dropdown-item text-warning pointer">Uninstall</a>
                        <?php } ?>
                    </div>

                    <!--DETAILS MODAL-->
                    <div class="modal fade" id="info<?php echo $uni ?>">
                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">
                                    <strong><?php echo $app['name'] ?> Details</strong>
                                    <button class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="card-body p-2">
                                    <?php echo $app['details'] ?>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            <?php endwhile; ?>
        </article>

    </div>
</div>