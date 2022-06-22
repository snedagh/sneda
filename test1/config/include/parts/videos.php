<?php if ($app === 'all'): ?>
    <div class="container h-100">
        <div class="rows no-gutters h-100">
            <?php if($videos_count < 1) : ?>
                <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                    <div class="text-center">
                        <p class="enc">
                            NO ViDEO
                        </p>
                        <p class="text-muted">
                            You can upload a video for others to have a view of
                        </p>
                    </div>
                </div>
             <?php endif; ?>

            <?php if($videos_count > 0) : ?>
                <?php while($video = $videos->fetch(PDO::FETCH_ASSOC)):
                    $spec_id = $video['spec_id'];
                    ?>
                    <div class="col-sm-4 p-2">
                        <div onclick="set_session('app=single,video=<?php echo $spec_id ?>','<?php echo session_id() ?>')" class="card pointer vid_thumb shadow-lg rounded-0">
                            <img src="assets/videos/tn/test.jpg" alt="Thumb Nail" class="img-fluid card-img-top">
                            <div class="card-body p-2">
                                <h5 class="text_eclips"><?php echo $video['title'] ?></h5>
                                <p class="m-0 p-0 text-muted text_eclips">
                                    <?php echo $video['description'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>


        </div>
    </div>
<?php endif; ?>