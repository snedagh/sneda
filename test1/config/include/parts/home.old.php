<?php if ($app === 'all'): ?>
    <!--HOME-->
    <div class="container border p-2 d-flex flex-wrap align-content-start h-100">

        <?php if ($apps_count < 1): ?>

            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                <p onclick="location.href='config/proc/appnav.php?app=ae281c6806257c0ef2e30cae52c0c910&token=<?php echo $token ?>'" class="enc">Please install an app</p>
            </div>

        <?php endif; ?>

        <?php if ($apps_count > 0): ?>


            <?php while ($app_detail = $apps_stmt->fetch(PDO::FETCH_ASSOC)): ?>

                <?php
				
                $app_uni = $app_detail['app'];
                $app = fetchFunc('appstore',"`uni` = '$app_uni'",$pdo);
				
                $app_trigger = $app['app_trigger'];
                $uni = $app['uni'];
                ?>

                <!--APPLICATION-->
                <div class="w-20 app_card p-2">
                    <div onclick="location.href='config/proc/appnav.php?app=<?php echo $uni ?>&token=<?php echo $token ?>'" class="card w-100 h-100 hover-independence cust_shadow pointer">

                        <div class="w-100 h-100 d-flex flex-wrap">

                            <!--APP ICON-->
                            <div class="w-40 overflow-hidden d-flex flex-wrap align-content-center justify-content-center h-100">
                                <?php
                                $icon = $app['icon'];
                                $image = 'assets/icons/apps_icon/'.$icon;
                                if(file_exists($image))
                                {
                                    // Read image path, convert to base64 encoding
                                    $imageData = base64_encode(file_get_contents($image));

                                    // Format the image SRC:  data:{mime};base64,{data};
                                    $src = 'data: '.mime_content_type($image).';base64,'.$imageData;
                                }
                                else
                                {
                                    $src = '';
                                }

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
                </div>

            <?php endwhile; ?>

        <?php endif; ?>


        <div class="row border">
            <div class="col-sm-6 p-2">
                <video  height="240" controls>
                    <source src="assets/videos/test.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>



    </div>
<?php endif; ?>