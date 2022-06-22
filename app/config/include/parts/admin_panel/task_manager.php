
<div class="bg-task h-100">
    <header class="p-2 d-flex flex-wrap align-content-center justify-content-between">
        <div class="h-90 d-flex flex-wrap align-content-center">
            <strong>Task Manager</strong>
        </div>
        <div style="overflow: hidden !important" class="h-90 d-flex overflow-hidden flex-wrap align-content-center justify-content-end">
            <button data-toggle="tooltip" title="New Task" class="btn btn-sm ico-lg p-1 bg-platinum rounded-circle">
                <img data-toggle="modal" data-target="#newTask" src="assets/icons/admin_panel/new.png" class="img-fluid" alt="N">
            </button>
            <?php if(getSession('view') == 'single'): ?>
                <button onclick="set_session('view=all','<?php echo session_id() ?>')" class="btn btn-sm p-1 btn-primary rounded-circle">
                    View All
                </button>
            <?php endif; ?>

            <div class="modal fade" id="newTask">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <strong class="modal-title">New Task</strong>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body p-2">

                            <form method="post" style="display: <?php if(getSession('new_stage') == 1){echo 'none'; } ?>" action="config/proc/form_process.php" class="w-100 new1" id="regular_form">

                                <input type="hidden" value="new_task" name="function">
                                <!--OWNER TITLE-->
                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">
                                    <!--OWNER-->
                                    <div class="form-group w-45">
                                        <label>Owner</label>
                                        <input required type="text" name="owner" class="form-control form-control-sm cust_shadow_innser">
                                        <small class="text-info">Who reported the issue?</small>
                                    </div>

                                    <div class="form-group w-45">
                                        <label>Title</label>
                                        <input maxlength="15" required type="text" name="title" class="form-control form-control-sm cust_shadow_innser">
                                        <small class="text-info">Short description of issues</small>
                                    </div>

                                </div>

                                <!--RELATIVE AND DOMAIN-->
                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">

                                    <!--DOMAIN-->
                                    <div class="form-group w-45">
                                        <label>Domain</label>
                                        <select class="form-control form-control-sm cust_shadow_innser" name="domain">
                                            <option value="0">Select Domain</option>
                                            <optgroup label="Internal">
                                                <option value="SM">Sneda Motors</option>
                                                <option value="SSM">Sneda Smart Meters</option>
                                                <option value="SSC">Sneda Shopping Center</option>
                                                <option value="ST">Sneda Transformers</option>
                                            </optgroup>
                                            <optgroup label="External">
                                                <option value="COMSYS">COMSYS</option>
                                                <option value="MYCOM">MYCOM</option>
                                                <option value="RELISH">Relish</option>
                                            </optgroup>
                                        </select>
                                        <div id="WRONGD">
                                            <small class="text-info">Where is the issue affecting?</small>
                                        </div>

                                    </div>

                                    <!--RELATIVE-->
                                    <div class="form-group w-45">
                                        <label>Relative</label>
                                        <select class="form-control form-control-sm cust_shadow_innser" name="relative">
                                            <option value="0">Select Relative</option>
                                            <option value="Networking">Networking</option>
                                            <option value="Hardware">Hardware</option>
                                            <option value="Update">Update</option>
                                        </select>
                                        <div id="WRONGR">
                                            <small class="text-info">Which part of productivity is affected?</small>
                                        </div>

                                    </div>


                                </div>

                                <!--ASSIGN-->
                                <div class="w-100 d-flex flex-wrap align-content-center justify-content-between">

                                    <!--DOMAIN-->
                                    <div class="form-group w-45">
                                        <label>Asign To</label>
                                        <select class="form-control form-control-sm cust_shadow_innser" name="assigned_to">
                                            <?php
                                                $users = $pdo->query("SELECT * FROM `users`");
                                               while($user = $users->fetch(PDO::FETCH_ASSOC)):
                                                   $uid = $user['id'];
                                                    $uname = $user['first_name'] . ' ' . $user['last_name'];
                                            ?>
                                                   <option value="<?php echo $uid ?>"><?php echo $uname ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                        <div id="WRONGD">
                                            <small class="text-info">Who should be in charge</small>
                                        </div>

                                    </div>



                                </div>

                                <!--DETAILS-->
                                <div class="w-100 mb-2">
                                    <label>Details</label>
                                    <textarea name="details" required class="form-control cust_shadow_innser" rows="3"></textarea>
                                    <small class="text-info">Explain issue clearly</small>
                                </div>
                                <input type="hidden" name="token" value="<?php echo session_id() ?>">



                                <!--SUBMIT-->
                                <div class="form-group d-flex flex-wrap align-content-end justify-content-center w-100">
                                    <input type="submit" value="NEXT" class="btn w-100 h-100 btn-info">
                                </div>



                            </form>

                            <form enctype="multipart/form-data" method="post" style="display: <?php if(getSession('new_stage') == 0){echo 'none'; } ?>" action="config/proc/form_process.php" class="w-100 new2" id="formWithFiles">
                                <input type="hidden" name="token" value="<?php echo session_id() ?>">
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
        <?php if($view == 'all'): ?>
            <?php if($t_count < 1) :?>
                <!--NO TASK-->
                <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                    <p class="enc">No Task</p>
                </div>
            <?php endif; ?>
            <?php if($t_count > 0) :?>
                <div class="table-responsive container cust_shadow h-100 p-3 o-auto">

                    <table class="table table-hover table-striped">

                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Domain</th>
                            <th>Relative</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php while($task = $task_stmt->fetch(PDO::FETCH_ASSOC)):
                            $status = $task['status']; $t_id=$task['id'];
                            $owner = fetchFunc('users',"`id` = '$user_id'",$pdo)['username'];
                            ?>
                            <tr ondblclick="set_session('view=single,task=<?php echo $t_id ?>','<?php echo session_id() ?>')" class="pointer text_sm">
                                <td><?php echo $task['title'] ?></td>
                                <td><?php echo $task['domain'] ?></td>
                                <td><?php echo $task['relative'] ?></td>
                                <td>
                                    <?php if($status == 0) { ?>
                                        <button class="btn btn_x_sm badge-danger">PENDING</button>
                                    <?php } ?>
                                    <?php if($status == 1) { ?>
                                        <button class="btn btn_x_sm badge-info">DONE</button>
                                    <?php } ?>
                                </td>
                                <td><?php echo $task['date_added'] ?></td>

                            </tr>
                        <?php endwhile; ?>


                        </tbody>

                    </table>

                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($view == 'single'): ?>

            <div class="w-75 h-100 container">
                <div class="w-100 h-100 d-flex flex-wrap align-content-center" style="overflow: hidden !important;">
                    <!-- DETAILS-->
                    <div class="w-75 h-95 o-hide p-2 text-center">
                        <div class="w-100 h-100 d-flex card p-2 cust_shadow card">
                            <div class="card-header text-left">
                                <div class="w-100 h-100 d-flex flex-wrap">
                                    <div class="w-50 h-100 d-flex flex-wrap align-content-center pl-2">
                                        <strong class="card-title"><?php echo $task['title'] ?></strong>
                                    </div>
                                    <div class="w-50 h-100 d-flex flex-wrap align-content-center justify-content-end pr-2">
                                        <?php
                                        if($task['status'] == '0')
                                        {
                                            echo '<button id="task_completed" value="'.session_id().'" class="btn btn-sm btn-success ml-2">Completed</button>';
                                            echo '<button onclick="show_display('."'updateBox'".')" class="btn btn-sm btn-info ml-2">Update</button>';
                                        } else
                                        {
                                            echo "<button class='btn btn-danger' disabled>CLOSED</button>";
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-left overflow-auto p-2">

                                <div class="mb-2" id="accordion">

                                    <div class="card m-2">
                                        <div class="card-header">
                                            <a class="card-link" data-toggle="collapse" href="#collapseOne">
                                                Task
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                            <div class="card-body p-2">
                                                <?php echo $task['details'] ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php while ($track = $tracking->fetch(PDO::FETCH_ASSOC)): ?>

                                        <div class="card m-2">
                                            <div class="card-header bg-track-head">
                                                <a class="collapsed card-link text-light" data-toggle="collapse" href="#collapse<?php echo md5($track['title']) ?>">
                                                    <?php echo $track['title'] ?>
                                                </a>
                                            </div>
                                            <div id="collapse<?php echo md5($track['title']) ?>" class="collapse" data-parent="#accordion">
                                                <div class="card-body p-2">
                                                    <?php echo $track['details'] ?>

                                                    <hr>

                                                    <small class="text-muted">
                                                        <?php echo $track['date'] ?>
                                                    </small>

                                                </div>
                                            </div>
                                        </div>

                                    <?php endwhile; ?>

                                </div>
                                <hr>


                                <div style="display: none" id="updateBox" class="card cust_shadow m-2">
                                    <!-- UPDATE -->
                                    <form action="config/proc/ajax.php" class="cust_shadow p-2" id="regular_form" method="post">
                                        <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                        <input type="hidden" name="function" value="add_task_track">

                                        <input type="text" required name="title" autocomplete="off" class="form-control mb-2" placeholder="Title">

                                        <textarea placeholder="Details" required name="update_text" id="update_text" class="form-control" rows="3"></textarea>


                                        <div class="w-100 text-right mt-2">
                                            <button class="btn btn-success">SUBMIT</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--FILES-->
                    <div class="w-25 h-95 o-hide o-hide text-center">
                        <div class="w-100 h-100 d-flex cust_shadow flex-wrap justify-content-center text-center o-auto">
                            <div class="w-100 w-100 d-flex flex-wrap">
                                <?php
                                $m_arr = explode('/',$task['files']);
                                foreach ($m_arr as $key=>$file_name):
                                    if(strlen($file_name) > 1):
                                        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                                        ?>

                                        <div class="d-flex flex-wrap align-content-center h-fit pointer cust_shadow img-thumbnail justify-content-center m-2 border">
                                            <?php if(file_type($ext) == 'Pictures') {
                                                $imgxx = "assets/storage/admin_panel/issues/$file_name"; ?>
                                                <img data-toggle="modal" data-target="#imgPrev<?php echo str_replace('.','',$file_name) ?>" src="<?php echo srcData($imgxx) ?>" class="img-fluid">
                                                <div style="cursor: default !important;" id="imgPrev<?php echo str_replace('.','',$file_name) ?>" class="modal">
                                                    <div class="w-100 h-100 bg-trans-50 d-flex flex-wrap align-content-center justify-content-center">
                                                        <div class="w-75 h-75 o-hide">

                                                            <?php if(file_exists($imgxx)): ?>
                                                                <a onclick="location.href='config/proc/task.php?download&file=<?php echo $file_name ?>'" class="text-light pointer">Save File</a>
                                                            <?php endif; ?>
                                                            <a class="text-info ml-2 pointer" data-dismiss="modal">Close</a>
                                                            <?php if(file_exists($imgxx)): ?>
                                                                <div class="w-100">
                                                                    <img src="<?php echo srcData($imgxx) ?>" class="img-fluid">
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if(!file_exists($imgxx)): ?>
                                                                <p>FILE DOES NOT EXIST</p>
                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>

                                    <?php endif; endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div style="display: none" class="container-fluid p-0 h-100">
                    <div class="row no-gutters h-100">
                        <div class="col-sm-8 w-100">
                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                <div class="h-100 w-100 o-hide bg-light cust_shadow">
                                    <div class="h-75 w-100 d-flex flex-wrap align-content-center justify-content-center text-center o-auto">
                                        <p class="text_lg">
                                            <?php echo $task['details'] ?>
                                        </p>
                                    </div>
                                    <div class="d-flex flex-wrap o-hide align-content-center h-25">
                                        <?php
                                        $m_arr = explode('/',$task['files']);
                                        foreach ($m_arr as $key=>$file_name):
                                            if(strlen($file_name) > 1):
                                                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                                                ?>

                                                <div class="media_case d-flex flex-wrap align-content-center bg-cus-muted c_hover img-thumbnail justify-content-center m-2 border">
                                                    <?php if(file_type($ext) == 'Pictures') {
                                                        $imgxx = "assets/storage/admin_panel/issues/$file_name"; ?>
                                                        <img data-toggle="modal" data-target="#imgPrev<?php echo str_replace('.','',$file_name) ?>" src="<?php echo srcData($imgxx) ?>" class="img-fluid">
                                                        <div style="cursor: default !important;" id="imgPrev<?php echo str_replace('.','',$file_name) ?>" class="modal">
                                                            <div class="w-100 h-100 bg-trans-50 d-flex flex-wrap align-content-center justify-content-center">
                                                                <div class="w-75 h-75 o-hide">

                                                                    <?php if(file_exists($imgxx)): ?>
                                                                        <a onclick="location.href='config/proc/task.php?download&file=<?php echo $file_name ?>'" class="text-light pointer">Save File</a>
                                                                    <?php endif; ?>
                                                                    <a class="text-info ml-2 pointer" data-dismiss="modal">Close</a>
                                                                    <?php if(file_exists($imgxx)): ?>
                                                                        <img src="<?php echo srcData($imgxx) ?>" class="img-fluid">
                                                                    <?php endif; ?>
                                                                    <?php if(!file_exists($imgxx)): ?>
                                                                        <p>FILE DOES NOT EXIST</p>
                                                                    <?php endif; ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            <?php endif; endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </article>

</div>

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