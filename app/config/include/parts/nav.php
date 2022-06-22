<?php
//get apps
$apps_t = 'user_'.$_SESSION['user_id'].'_apps';
$apps_sql = "SELECT * FROM `$apps_t`";
$apps_stmt = $pdo->prepare($apps_sql);
$apps_stmt->execute();
$apps_count = $apps_stmt->rowCount();
?>
<button onclick="show_display('apps_box');" id="show-apps" type="button" class="h_btn_30px p-0 m-2 start_menu btn btn-outline-light cust_shadow rounded">
    <img src="assets/icons/apps_icon/start_menu.png" class="img-fluid" alt="">
</button>

<div style="display: none" id="apps_box" class="apps_box ml-2">
    <div class="card p-2 bg-dark w-100 h-100 text-dark cust_shadow">
        <div class="card-header">
            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-between">

                <?php
                if($my['dp'] == 'default')
                {
                    $dp = 'assets/profile_pics/default.png';
                }
                else
                {
                    $dp = 'assets/profile_pics/'.md5($my_username).'.'.$my['dp'];
                }

                ?>

                <img
                        onclick="location.href='config/proc/appnav.php?app=53888e0c5bdacc1a3a32db8db615asdas&token=<?php echo session_id() ?>'"
                        class="img-fluid h_btn_30px pointer rounded-circle mr-2"
                        data-toggle="tooltip"
                        title="Profile"
                        src="<?php echo srcData($dp) ?>"
                >

                <button onclick="hide_display('apps_box')" id="close_menu" class="h_btn_30px p-0 btn btn-outline-danger">&times;</button>

            </div>

        </div>

        <div class="card-body p-1">
            <div class="w-100 h-100 d-flex flex-wrap">
                <!-- LOGOUT -->
                <div data-toggle="tooltip" title="Logout" onclick="location.href='config/proc/logout.php?token=<?php echo session_id() ?>'" class="app_icon_sm m-1 p-1 h-fit">
                    <div class="p-2 border cust_shadow">
                        <img src="assets/icons/apps_icon/power_off.png" class="img-fluid" alt="">
                    </div>
                </div>

                <!-- HOME -->
                <div data-toggle="tooltip" title="Home" onclick="open_app('53888e0c5bdacc1a3a32db8db61ahgsd','<?php echo session_id() ?>')" class="app_icon_sm m-1 p-1 h-fit">
                    <div class="p-2 border cust_shadow">
                        <img src="assets/icons/apps_icon/home.png" class="img-fluid" alt="">
                    </div>
                </div>

                <!-- APP STORE -->
                <div onclick="open_app('a3575d8cd3bfe16de98d7ac9d825d9d9','<?php echo session_id() ?>')" class="app_icon_sm m-1 p-1 h-fit">
                    <div class="p-2 border cust_shadow">
                        <img
                                onclick="location.href='config/proc/appnav.php?app=a3575d8cd3bfe16de98d7ac9d825d9d9&token=<?php echo session_id() ?>'"
                                class="img-fluid"
                                data-toggle="tooltip"
                                title="App Store"
                                src="<?php echo srcData('assets/icons/home/appstore.png') ?>">
                    </div>
                </div>

                <!-- SETTINGS -->
                <div  data-toggle="tooltip" title="Settings" onclick="open_app('53888e0c5bdacc1a3a32db8db615asdas','<?php echo session_id() ?>')"  class="app_icon_sm m-1 p-1 h-fit">
                    <div class="p-2 border cust_shadow">
                        <img src="assets/icons/apps_icon/settings.png" class="img-fluid" alt="">
                    </div>
                </div>

                <!-- UPLOAD VIDE -->
                <div  data-toggle="tooltip" title="Upload Video" class="app_icon_sm m-1 p-1 h-fit">
                    <div data-toggle="modal" data-target="#uploadVideo" class="p-2 border cust_shadow">
                        <img src="assets/icons/apps_icon/upload_video.png" class="img-fluid" alt="">
                    </div>



                </div>

                <!-- INSTALLED APPS -->
                <?php while ($app_detail = $apps_stmt->fetch(PDO::FETCH_ASSOC)): ?>

                    <?php

                    $app_uni = $app_detail['app'];
                    $app = fetchFunc('appstore',"`uni` = '$app_uni'",$pdo);

                    $app_trigger = $app['app_trigger'];
                    $uni = $app['uni'];

                    $icon = $app['icon'];
                    $image = 'assets/icons/apps_icon/'.$icon;
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



                    <!--APPLICATION-->
                    <div data-toggle="tooltip" title="<?php echo $app['name'] ?>" onclick="open_app('<?php echo $uni ?>','<?php echo session_id() ?>')"  class="app_icon_sm m-1 p-1 h-fit">
                        <div class="p-2 border cust_shadow">
                            <img src="<?php echo $src ?>" class="img-fluid" alt="">
                        </div>
                    </div>


                <?php endwhile; ?>

            </div>



        </div>

    </div>
</div>

