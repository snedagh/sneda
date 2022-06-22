<!-- UPLOAD VIDEO MODAL -->
<div id="uploadVideo" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-danger">
            <div class="modal-header">
                <strong class="modal-title">Upload a Video File</strong>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body p-2">
                <form action="config/proc/form_process.php" method="POST" enctype="multipart/form-data" class="w-100" id="formWithFiles">
                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                    <input type="hidden" name="function" value="upload_video">
                    <div class="form-group mb-2">
                        <label class="w-100">Video Title
                            <input type="text" class="form-control" name="title" autocomplete="off" required>
                        </label>
                    </div>
                    <div class="form-group mb-2">
                        <label class="w-100">Video Title
                            <textarea rows="3" type="text" class="form-control" name="description" autocomplete="off" required></textarea>
                        </label>
                    </div>

                    <!-- VIDEO FILE -->
                    <div class="custom-file mb-2">
                        <input required type="file" name="video_file" class="custom-file-input" accept="video/mp4" id="video_file">
                        <label class="custom-file-label" for="video_file">Choose Video File</label>
                    </div>

                    <div class="w-100 mb-2">
                        <progress style="display: none" class="progress w-100" value="0" max="100"></progress>
                    </div>
                    <div class="w-100">
                        <input class="btn btn-success w-100" type="submit" name="upload_video">
                    </div>

                </form>
                <script>

                </script>
            </div>

        </div>
    </div>
</div>

<?php //if ($app === 'all'): ?>
<!--    <div class="container h-100">-->
<!--        <div class="row no-gutters d-flex flex-wrap align-content-start h-100">-->
<!--            --><?php //if($videos_count < 1) : ?>
<!--                <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">-->
<!--                    <div class="text-center">-->
<!--                        <p class="enc">-->
<!--                            NO ViDEO-->
<!--                        </p>-->
<!--                        <p class="text-muted">-->
<!--                            You can upload a video for others to have a view of-->
<!--                        </p>-->
<!--                    </div>-->
<!--                </div>-->
<!--             --><?php //endif; ?>
<!---->
<!--            --><?php //if($videos_count > 0) : ?>
<!--                --><?php //while($video = $videos->fetch(PDO::FETCH_ASSOC)):
//                    $id = $video['spec_id'];
//                    ?>
<!--                    <div class="col-sm-4 mb-0 p-2">-->
<!--                        <div onclick="set_session('app=single,video=--><?php //echo $id ?>//','<?php //echo session_id() ?>//')" class="card pointer vid_thumb shadow-lg rounded-0">
//                            <img src="assets/videos/tn/test.jpg" alt="Thumb Nail" class="img-fluid card-img-top">
//                            <div class="card-body p-2">
//                                <h5 class="text_eclips"><?php //echo $video['title'] ?><!--</h5>-->
<!--                                <p class="m-0 p-0 text-muted text_eclips">-->
<!--                                    --><?php //echo $video['description'] ?>
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                --><?php //endwhile; ?>
<!--            --><?php //endif; ?>
<!---->
<!---->
<!--        </div>-->
<!--    </div>-->
<?php //endif; ?>

<?php if($app == 'single')
    :?>
    <div class="w-100 h-100 overflow-auto">
        <div class="container">

            <?php if ($fp == 'no' ):?>
                <script>
                    set_session('app=all','<?php echo session_id() ?>')
                </script>
            <?php endif; ?>

            <?php if ($fp == 'yes' ): ?>
                <div class="rows no-gutters d-flex flex-wrap justify-content-center">
                    <div class="col-sm-8 p-2">



                        <div class="card rounded-0 shadow-lg">

                            <video id="player" controlsList="nodownload" playsinline controls data-poster="assets/videos/tn/test.jpg">
                                <source src="<?php echo $file_path ?>" type="video/mp4" />
                                <source src="/path/to/video.webm" type="video/webm" />

                                <!-- Captions are optional -->
                                <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default />
                            </video>


                            <div class="card-body p-2">
                                <span onclick="set_session('app=all','<?php echo session_id() ?>')" class="pointer badge badge-info">All Videos</span>
                                <h3><?php echo $video['title'] ?></h3>
                                <p><?php echo $video['description'] ?></p>
                                <hr class="bg-independence">
                                <p>
                                    There will be comment sections soon where</p>
                            </div>


                        </div>
                    </div>
                </div>
            <?php endif; ?>



        </div>
    </div>
<?php endif; ?>