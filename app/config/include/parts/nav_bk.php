<!--HEADER-->
<header>
    <nav class="navbar navbar-expand-sm navbar-dark fixed-bottom">
        <!-- Brand/logo -->
        <a class="navbar-brand" href="#">SMDESK V0.2</a>

        <!-- Links -->
        <div class="w-25 h-100 ml-auto d-flex flex-wrap align-content-center justify-content-end">

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

            <img onclick="location.href='config/proc/appnav.php?app=53888e0c5bdacc1a3a32db8db61ahgsd&token=<?php echo session_id() ?>'" class="img-fluid h_btn_30px mr-2 pointer" data-toggle="tooltip" title="Home" src="<?php echo srcData('assets/icons/home/home.png') ?>">

            <?php if ($location !== 'app_store'): ?>
                <img onclick="location.href='config/proc/appnav.php?app=a3575d8cd3bfe16de98d7ac9d825d9d9&token=<?php echo session_id() ?>'" class="img-fluid h_btn_30px mr-2 pointer" data-toggle="tooltip" title="App Store" src="<?php echo srcData('assets/icons/home/appstore.png') ?>">
            <?php endif; ?>

            <img onclick="location.href='config/proc/logout.php?token=<?php echo session_id() ?>'" class="img-fluid h_btn_30px pointer" data-toggle="tooltip" title="Logout" src="<?php echo srcData('assets/icons/home/logout.png') ?>">

            <button id="show-apps" type="button" class="btn h_btn_30px rounded-circle btn-primary">
            </button>

            <div style="display: " id="apps_box" class="apps_box mr-2">
                <div class="card w-100 h-100 text-dark cust_shadow">
                    <div class="card-header">
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
                    </div>

                    <div class="card-body p-1">
                        <div class="w-100 h-100 d-flex flex-wrap">
                            <!-- LOGOUT -->
                            <div onclick="location.href='config/proc/logout.php?token=<?php echo session_id() ?>'" class="w-25 app_ico p-1 h-fit">
                                <div class="p-2 border cust_shadow">
                                    <img src="assets/icons/apps_icon/power_off.png" class="img-fluid" alt="">
                                </div>
                            </div>

                            <!-- HOME -->
                            <div onclick="location.href='config/proc/appnav.php?app=53888e0c5bdacc1a3a32db8db61ahgsd&token=<?php echo session_id() ?>'" class="w-25 app_ico p-1 h-fit">
                                <div class="p-2 border cust_shadow">
                                    <img src="assets/icons/apps_icon/home.png" class="img-fluid" alt="">
                                </div>
                            </div>

                            <!-- APP STORE -->
                            <div onclick="location.href='config/proc/appnav.php?app=a3575d8cd3bfe16de98d7ac9d825d9d9&token=<?php echo session_id() ?>'" class="w-25 app_ico p-1 h-fit">
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
                            <div onclick="location.href='config/proc/appnav.php?app=53888e0c5bdacc1a3a32db8db615asdas&token=<?php echo session_id() ?>'"  class="w-25 app_ico p-1 h-fit">
                                <div class="p-2 border cust_shadow">
                                    <img src="assets/icons/apps_icon/settings.png" class="img-fluid" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </nav>
</header>