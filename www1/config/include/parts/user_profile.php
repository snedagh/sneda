<!--USER PROFILE-->
<div class="w-100 bg-dutch-white h-100">
    <div style="background: url('<?php echo srcData('assets/profile_pics/'.md5($my_username).'.'.$my_extension) ?>')" class="jumbotron m-0 p-0 img-bg border-0 d-flex flex-wrap align-content-center jumbotron h-50">

        <div class="d-flex flex-wrap align-content-center w-100 h-100 bg-trans-50">

            <div class="w-100 h-100 black_trans_50">

                <div class="container h-100 clearfix">

                    <!--DAP-->
                    <div class="w-50 float-left h-100 d-flex flex-column align-content-center justify-content-center">
                        <div class="w-100">
                            <div class="overflow-hidden p_dp mx-auto">
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
                                <img class="img-fluid rounded-circle" src="<?php echo srcData($dp) ?>">

                            </div>
                        </div>
                        <kbd class="text-light w-fit mx-auto m-2 pointer">@<?php echo $my['username']  ?></kbd>
                    </div>

                    <!--USERNAME-->
                    <div class="w-50 float-right h-100 d-flex flex-wrap align-content-center justify-content-center">
                        <div class="w-100"><strong class="display-4"><?php echo $my['first_name'] . ' ' . $my['last_name'] ?></strong></div>
                        <div class="d-flex flex-wrap justify-content-center">
                            <button class="btn btn-sm m-2 btn-info" data-toggle="modal" data-target="#editProfile">Edit Profile</button>
                            <!--EDIT MODAL-->
                            <div class="modal fade" id="editProfile">

                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-dutch-white">

                                        <div class="modal-header">
                                            <strong class="modal-title">Editing Profile</strong>
                                            <button class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body p-3">

                                            <form method="post" action="config/proc/usermgmt.php?token=<?php echo session_id() ?>" class="w-100" enctype="multipart/form-data">

                                                <!--PROFILE POC-->
                                                <div class="mb-2">
                                                    <div class="w-100 d-flex flex-wrap align-content-center">
                                                        <div class="w-10 d-flex flex-wrap align-content-center">
                                                            <div class="w-100 bg-info" style="height: 1px"></div>
                                                        </div>
                                                        <div class="pl-1 pr-1">Profile Picture</div>
                                                        <div class="w-30 d-flex flex-wrap align-content-center">
                                                            <div class="w-100 bg-info" style="height: 1px"></div>
                                                        </div>
                                                    </div>

                                                    <div class="w-100 d-flex flex-wrap justify-content-between">
                                                        <div class="w-40">
                                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                                <div class="custom-file">
                                                                    <input onchange="loadFile(event)" type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload">
                                                                    <label for="fileToUpload" class="custom-file-label">Choose Image</label>
                                                                </div>
                                                                <script>
                                                                    var loadFile = function(event) {
                                                                        var output = document.getElementById('output');
                                                                        output.src = URL.createObjectURL(event.target.files[0]);
                                                                        output.onload = function() {
                                                                            URL.revokeObjectURL(output.src) // free memory
                                                                        }
                                                                    };
                                                                </script>
                                                            </div>
                                                        </div>

                                                        <div class="w-40">
                                                            <div class="overflow-hidden card cust_shadow p_dp p-2 mx-auto">
                                                                <img id="output" class="img-fluid rounded-circle" src="<?php echo srcData('assets/profile_pics/'.md5($my_username).'.'.$my_extension) ?>">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--PERSONAL INFO-->
                                                <div class="mb-2">
                                                    <div class="w-100 d-flex flex-wrap align-content-center">
                                                        <div class="w-10 d-flex flex-wrap align-content-center">
                                                            <div class="w-100 bg-info" style="height: 1px"></div>
                                                        </div>
                                                        <div class="pl-1 pr-1">Personal Information</div>
                                                        <div class="w-30 d-flex flex-wrap align-content-center">
                                                            <div class="w-100 bg-info" style="height: 1px"></div>
                                                        </div>
                                                    </div>

                                                    <div class="w-100 container">
                                                        <div class="w-100  input-group input-group-sm row no-gutters">
                                                            <!--FIRST NAME-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-info">
                                                                    <label for="firstName" class="m-0 font-weight-bold text-light text_sm">First Name</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="text" name="firstName" id="firstName" autocomplete="off" value="<?php echo $my['first_name'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--LAST NAME-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-info">
                                                                    <label for="lastName" class="m-0 font-weight-bold text-light text_sm">Last Name</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="text" name="lastName" id="lastName" autocomplete="off" value="<?php echo $my['last_name'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--Phone-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card h-100 p-1 cust_shadow bg-info">
                                                                    <label for="phone" class="m-0 font-weight-bold text-light text_sm">Phone</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="tel" name="phone" id="phone" autocomplete="off" value="<?php echo $my['phone'] ?>" class="form-control no-outline">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--EMAIL-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-info">
                                                                    <label for="email" class="m-0 font-weight-bold text-light text_sm">Email</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="email" name="email" id="email" autocomplete="off" value="<?php echo $my['email'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--EXTENSION-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-info">
                                                                    <label for="extension" class="m-0 font-weight-bold text-light text_sm">Extension</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="text" name="extension" id="extension" autocomplete="off" value="<?php echo $my['extension'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!--WORK INFO-->
                                                <div class="w-100 mb-2">
                                                    <div class="w-100 d-flex flex-wrap align-content-center">
                                                        <div class="w-10 d-flex flex-wrap align-content-center">
                                                            <div class="w-100 bg-warning" style="height: 1px"></div>
                                                        </div>
                                                        <div class="pl-1 pr-1">Work Information</div>
                                                        <div class="w-30 d-flex flex-wrap align-content-center">
                                                            <div class="w-100 bg-warning" style="height: 1px"></div>
                                                        </div>
                                                    </div>

                                                    <div class="w-100 container">
                                                        <div class="w-100  input-group input-group-sm row no-gutters">
                                                            <!--Company-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-light-cyan">
                                                                    <label for="company" class="m-0 font-weight-bold text-dark text_sm">Company</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="text" name="company" id="company" autocomplete="off" value="<?php echo $my['company'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--branch-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-light-cyan">
                                                                    <label for="branch" class="m-0 font-weight-bold text-dark text_sm">Branch</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="text" name="branch" id="branch" autocomplete="off" value="<?php echo $my['branch'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--position-->
                                                            <div class="col-sm-3 p-1">
                                                                <div class="card p-1 h-100 cust_shadow bg-light-cyan">
                                                                    <label for="position" class="m-0 font-weight-bold text-dark text_sm">Position</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input required type="text" name="position" id="position" autocomplete="off" value="<?php echo $my['position'] ?>" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>




                                                        </div>
                                                    </div>
                                                </div>

                                                <button name="updateUserAccount" class="btn m-2 ml-4 btn-success">COMMIT CHANGE</button>
                                            </form>

                                        </div>

                                    </div>
                                </div>

                            </div>

                            <button class="btn btn-sm m-2 btn-warning">Change Password</button>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="h-50 container p-2 w-100 overflow-hidden">

        <div class="row no-gutters h-100">
            <div class="col-sm-2 p-1 border-right h-100">
                <ul class="nav flex-column nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#p_info">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#w_info">Work Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#s_info">Security</a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-10 p-2">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane container active" id="p_info">
                        <div class="row w-50">
                            <!--FULL NAME-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Full Name
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['first_name'] . ' ' . $my['last_name'] ?>
                            </div>

                            <!--PHONE-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Phone
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['phone']?>
                            </div>

                            <!--EMAIL-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Email
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['email']?>
                            </div>

                            <!--EXTENSION-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Extension
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['extension']?>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane container fade" id="w_info">
                        <div class="row w-50">

                            <!--Company-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Company
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['company']?>
                            </div>

                            <!--Branch-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Branch
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['branch']?>
                            </div>

                            <!--Position-->
                            <div class="col-sm-3 mb-2 bg-independence">
                                Position
                            </div>

                            <div class="col-sm-9 text-muted mb-2">
                                <?php echo $my['position']?>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane container fade" id="s_info">
                        <div class="btn btn-warning">
                            Change Password
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
