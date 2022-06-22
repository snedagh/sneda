
<article>
    <header class="p-2 d-flex flex-wrap align-content-center justify-content-between">
        <div class="h-90 d-flex flex-wrap align-content-center">
            <strong>User Management</strong>
        </div>
        <div style="overflow: hidden !important" class="h-90 d-flex overflow-hidden flex-wrap align-content-center justify-content-end">
            <button data-toggle="tooltip" title="New User" class="btn btn-sm ico-lg p-1 bg-platinum rounded-circle">
                <img data-toggle="modal" data-target="#newTask" src="assets/icons/admin_panel/new.png" class="img-fluid" alt="N">
            </button>

            <button data-toggle="tooltip" title="New UAL" class="btn btn-sm ico-lg p-1 bg-platinum rounded-circle">
                <img data-toggle="modal" data-target="#newUAL" src="assets/icons/admin_panel/new.png" class="img-fluid" alt="N">
            </button>

            <?php if(getSession('view') == 'single'): ?>
                <button onclick="xajax('config/proc/task.php?view=all','task','reload','none')" class="btn btn-sm p-1 btn-primary rounded-circle">
                    View All
                </button>
            <?php endif; ?>

            <div class="modal fade" id="newTask">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <strong class="modal-title">New User</strong>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body p-2">

                            <form method="post" style="display: <?php if(getSession('new_stage') == 1){echo 'none'; } ?>" action="config/proc/form_process.php" class="w-100 new1" id="regular_form">

                                <input type="hidden" name="token" value="<?php echo session_id() ?>" id="">

                                <!--OWNER TITLE-->
                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">
                                    <!--OWNER-->
                                    <div class="form-group w-45">
                                        <label>Username</label>
                                        <input autocomplete='off' required type="text" name="username" class="form-control cust_shadow_innser">
                                        <div id="UNERR">
                                            <small class="text-info"></small>
                                        </div>
                                    </div>

                                    <!--DOMAIN-->
                                    <div class="form-group w-45">
                                        <label>User Group</label>
                                        <select class="form-control cust_shadow_innser" name="ual">
                                            <?php
                                            $ual_stmt = $pdo->prepare("SELECT * FROM `users_ual` order by level desc");
                                            $ual_stmt->execute();

                                            while($ual = $ual_stmt->fetch(PDO::FETCH_ASSOC))
                                            {
                                                echo "<option value='".$ual['level']."'>".$ual['description']."</option>";
                                            }
                                            ?>
                                        </select>
                                        <div id="UAL">
                                            <small class="text-info"></small>
                                        </div>

                                    </div>

                                </div>



                                <!--SUBMIT-->
                                <div class="form-group d-flex flex-wrap align-content-end justify-content-center w-100">
                                    <input type="submit" value="NEXT" class="btn w-100 h-100 btn-info">
                                </div>



                            </form>

                            <form enctype="multipart/form-data" method="post" style="display: <?php if(getSession('new_stage') == 0){echo 'none'; } ?>" action="config/proc/form_process.php" class="w-100 new2" id="formWithFiles">
                                <input type="hidden" name="token" value="<?php echo session_id() ?>" id="">
                                <div class="w-100 p-2 cust_shadow">
                                    <strong>Attaching Files</strong>
                                    <p>
                                        If there are files available to support this. Attach them
                                    </p>

                                    <div class="w-100 mb-2 d-flex flex-wrap justify-content-between">
                                        <div class="ico-sm pointer">
                                            <img onclick="addAttachment()" data-toggle="tooltip" title="+1 More" src="assets/icons/admin_panel/attach.png" class="img-fluid" alt="AD">
                                        </div>

                                        <input type="submit" name="add_media" class="btn btn-sm btn-success" value="Process">

                                    </div>

                                    <div id="filesContainer">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input" id="file1" name="file[]">
                                            <label for="file1" class="custom-file-label">SELECT FILE</label>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="newUAL">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <strong class="modal-title">New newUAL</strong>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body p-2">

                            <form method="post" style="display: <?php if(getSession('new_stage') == 1){echo 'none'; } ?>" action="config/proc/form_process.php" class="w-100 new1" id="regular_form">

                                <input type="hidden" name="token" value="<?php echo session_id() ?>" id="">
                                <!--OWNER TITLE-->
                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">
                                    <!--OWNER-->
                                    <div class="form-group w-45">
                                        <label>Description</label>
                                        <input required type="text" autocomplete='off' name="ual" class="form-control cust_shadow_innser">
                                        <div id="UNERR">
                                            <small class="text-info"></small>
                                        </div>
                                    </div>

                                    <!--DOMAIN-->
                                    <div class="form-group w-45">
                                        <label>Level</label>
                                        <input required type="tel" name="level" autocomplete='off' class="form-control cust_shadow_innser">
                                        <div id="UAL">
                                            <small class="text-info"></small>
                                        </div>

                                    </div>

                                </div>



                                <!--SUBMIT-->
                                <div class="form-group d-flex flex-wrap align-content-end justify-content-center w-100">
                                    <input type="submit" value="NEXT" class="btn w-100 h-100 btn-info">
                                </div>



                            </form>

                            <form enctype="multipart/form-data" method="post" style="display: <?php if(getSession('new_stage') == 0){echo 'none'; } ?>" action="config/proc/form_process.php" class="w-100 new2" id="formWithFiles">

                                <div class="w-100 p-2 cust_shadow">
                                    <strong>Attaching Files</strong>
                                    <p>
                                        If there are files available to support this. Attach them
                                    </p>

                                    <div class="w-100 mb-2 d-flex flex-wrap justify-content-between">
                                        <div class="ico-sm pointer">
                                            <img onclick="addAttachment()" data-toggle="tooltip" title="+1 More" src="assets/icons/admin_panel/attach.png" class="img-fluid" alt="AD">
                                        </div>

                                        <input type="submit" name="add_media" class="btn btn-sm btn-success" value="Process">

                                    </div>

                                    <div id="filesContainer">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input" id="file1" name="file[]">
                                            <label for="file1" class="custom-file-label">SELECT FILE</label>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </header>

    <article class="p-2" style="overflow: hidden">
        <div class="card bg-ligh-danger h-100">
            <div class="card-header">
                <strong class="card-title">Users</strong>
            </div>

            <div class="card-body p-0 o-hide">
                <?php if($u_count < 1) :?>
                    <!--NO TASK-->
                    <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                        <p class="enc">No User</p>
                    </div>
                <?php endif; ?>
                <?php if($u_count > 0) :?>
                    <div class="table-responsive h-100 p-3 o-auto">

                        <table class="table table-hover table-striped">

                            <thead>
                            <tr>
                                <th><input type="checkbox"></th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Account Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>

                            <?php while($user = $user_stmt->fetch(PDO::FETCH_ASSOC)):
                                $status = $user['acct_disabled']; $u_id=$user['id']; ?>
                                <tr class="pointer text_sm">
                                    <td><input type="checkbox"></td>
                                    <td><?php echo $user['username'] ?></td>
                                    <td><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                                    <td><?php echo $user['phone'] ?></td>
                                    <td><?php echo $user['email'] ?></td>
                                    <td>
                                        <?php if($status == 0) { ?>
                                            <button class="btn btn_x_sm badge-danger">PENDING</button>
                                        <?php } ?>
                                        <?php if($status == 1) { ?>
                                            <button class="btn btn_x_sm badge-info">DONE</button>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="dropdown dropleft">
                                            <button type="button" class="btn btn-info btn_x_sm dropdown-toggle" data-toggle="dropdown">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-info">Reset Password</a>
                                                <a onclick="xajax('config/proc/task.php?del=<?php echo $u_id ?>&token=<?php echo session_id() ?>','delete_task','reload','none')" class="dropdown-item text-danger">Delete</a>
                                                <a class="dropdown-item text-success">Deactivate</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>


                            </tbody>

                        </table>

                    </div>
                <?php endif; ?>
            </div>

        </div>

    </article>

</article>

<script>
    function addAttachment()
    {
        var special = Math.floor(Math.random() * 999);
        var existing = document.getElementById('filesContainer').innerHTML;
        var topUp = '<div class="custom-file mb-2"> <input type="file" class="custom-file-input" id="file'+special.toString()+'" name="file[]"> <label for="file'+special.toString()+'" class="custom-file-label">SELECT FILE</label> </div>';
        var together = existing.toString() + '\n' + topUp.toString();

        document.getElementById('filesContainer') . innerHTML = together;


    }


</script>
