<div class="w-100 h-100 bg-light">
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

    <div class="container h-100">
        <div class="row no-gutters d-flex flex-wrap align-content-start h-100">

            <div class="col-sm-12 mb-2">
                <div class="card w-100">
                    <a href="#"></a>
                </div>
            </div>

        </div>
    </div>
</div>