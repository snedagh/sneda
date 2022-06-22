<link rel="stylesheet" href="css/parts/client_mgmt_sys.css">
<div id="tMain" class="w-100 h-100">
    <div class="container-fluid p-0 h-100">
        <div class="row h-100 no-gutters">
            <!-- NAV -->
            <div class="col-sm-2 h-100">
                <header class="border-bottom text_primary font-weight-bolder d-flex flex-wrap align-content-center">Client Kit</header>
                <article>

                    <div class="w-100 pointer pl-2 nav_row d-flex flex-wrap text-muted">
                        <div class="h-100 d-flex flex-wrap align-content-center"><i class="fa fa-dashboard"></i></div>
                        <div class="h-100 d-flex flex-wrap align-content-center pl-2"><span class="nav_text">Dashboard</span></div>
                    </div>
                    <div class="w-100 pointer pl-2 nav_row d-flex flex-wrap text-muted">
                        <div class="h-100 d-flex flex-wrap align-content-center"><i class="fa fa-user"></i></div>
                        <div onclick="set_session('view=all')" class="h-100 d-flex flex-wrap align-content-center pl-2"><span class="nav_text">Clients</span></div>
                    </div>
                    <div class="w-100 pointer pl-2 nav_row d-flex flex-wrap text-muted">
                        <div class="h-100 d-flex flex-wrap align-content-center"><i class="fa fa-tasks"></i></div>
                        <div class="h-100 d-flex flex-wrap align-content-center pl-2"><span class="nav_text">Task</span></div>
                    </div>

                </article>
            </div>

            <!-- WORK SPACE -->
            <div class="col-sm-10 h-100">
                <header class="border-bottom text_primary font-weight-bolder d-flex flex-wrap align-content-center"></header>
                <article class="xbody p-5 overflow-hidden">

                    <?php if($module === 'clients'): ?>

                        <?php if($view === 'all'):
                            $all_clients = $pdo->query("select * from cli_mgmt_sys_clients order by `id` desc LIMIT 10");
                            ?>

                            <header>
                                <div class="w-100 h-100 d-flex flex-wrap justify-content-between">
                                    <strong class="text-muted">Clients</strong>
                                    <button data-toggle="modal" data-target="#newCustomer" class="btn btn-info btn-sm h-fit">ADD</button>
                                    <div id="newCustomer" class="modal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <strong class="modal-title">New Client</strong>
                                                </div>
                                                <form method="post" action="config/proc/form/app_form.php" id="regular_form" class="modal-body text-dark">
                                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                                    <input type="hidden" name="app" value="<?php echo $location ?>">
                                                    <input type="hidden" name="function" value="newClient">
                                                    <div class="w-100 d-flex flex-wrap justify-content-between">
                                                        <div class="input-group w-100 mb-2">
                                                            <label class="w-100">Full Name
                                                                <input type="text" name="Full Name" autocomplete="off" required class="form-control">
                                                            </label>
                                                        </div>
                                                        <div class="input-group mb-2 w-45">
                                                            <label class="w-100"> Email Address
                                                                <input type="email" name="email" autocomplete="off" required class="form-control">
                                                            </label>
                                                        </div>
                                                        <div class="input-group mb-2 w-45">
                                                            <label class="w-100"> Phone Number
                                                                <input type="tel" name="phone" autocomplete="off" required class="form-control">
                                                            </label>
                                                        </div>

                                                        <div class="input-group mb-2 w-45">
                                                            <label class="w-100"> Organization
                                                                <input type="text" name="org" value="Unknown" autocomplete="off" required class="form-control">
                                                            </label>
                                                        </div>
                                                        <div class="input-group mb-2 w-45">
                                                            <label class="w-100"> Position
                                                                <input type="text" name="pos" value="Unknown" autocomplete="off" required class="form-control">
                                                            </label>
                                                        </div>

                                                        <div class="w-100 text-center">
                                                            <button class="btn btn-success rounded-0" type="submit">SAVE</button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <article class="card cust_shadow rounded-0">
                                <div class="table table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Organization</th>
                                                <th>Response</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php while ($client = $all_clients->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr onclick="set_session('view=response,client=<?php echo $client['id'] ?>')" class="pointer">
                                                <td class="font-weight-bold"><?php echo $client['name'] ?></td>
                                                <td><?php echo $client['email'] ?></td>
                                                <td><?php echo $client['phone'] ?></td>
                                                <td><?php echo $client['org'] ?></td>
                                                <td><kbd>2</kbd></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </article>

                        <?php endif; ?>

                        <?php if($view === 'response'):
                            $id = getSession('client');
                            $client = fetchFunc('cli_mgmt_sys_clients',"`id` = $id",$pdo);
                            ?>



                            <article class="card h-100 cust_shadow rounded-0">
                                <div class="container-fluid h-100">
                                    <div class="row h-100">
                                        <div class="col-sm-3 h-100 p-2">
                                            <div class="w-100">
                                                <div class="w-50 mx-auto rounded-circle">
                                                    <img src="assets/profile_pics/test.jpg" alt="" class="img-fluid rounded-circle">
                                                </div>

                                                <strong class="text-muted">
                                                    <div class="text-center"><?php echo $client['name'] ?></div>
                                                </strong>

                                                <div class="w-75 mx-auto">
                                                    <span><i class="fa fa-briefcase"></i> <?php echo $client['org'] ?></span>
                                                    <span class="float-right"><i class="fa fa-user"></i> <?php echo $client['pos'] ?></span>
                                                </div>
                                                <hr class="w-75">

                                                <div class="w-75 mx-auto">
                                                    <button data-toggle="modal" data-target="#update" class="w-100 btn btn-sm btn-info rounded-0 text-left"><i class="fa fa-plus"></i> New Update</button>
                                                    <div class="modal" id="update">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <strong class="modal-title">New Response</strong>
                                                                    <button class="close" type="submit" form="regular_form">Save</button>
                                                                </div>

                                                                <form  method="post" action="config/proc/form/app_form.php" id="regular_form"  class="modal-body p-2">
                                                                    <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                                                    <input type="hidden" name="app" value="<?php echo $location ?>">
                                                                    <input type="hidden" name="function" value="update_client">
                                                                    <textarea name="update_details" style="height: 75vh" class="form-control" id="update_details"></textarea>
                                                                    <script src="js/htmeditor.min.js"      htmeditor_textarea="update_details"      full_screen="no"      editor_height="450"     run_local="no">
                                                                    </script>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="w-75">

                                                <div class="w-75 mx-auto mb-2">
                                                    <button class="w-100 btn btn-sm btn-info rounded-0 text-left"><i class="fa fa-sms"></i> Send SMS</button>
                                                </div>
                                                <div class="w-75 mx-auto">
                                                    <button class="w-100 btn btn-sm btn-warning rounded-0 text-left"><i class="fa fa-envelope"></i> Send Email</button>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="col-sm-9 h-100">
                                            <div class="p-2 h-100 overflow-hidden">
                                                <div class="cust_shadow card h-100 overflow-auto pb-2">
                                                    <div class="card-header">
                                                        <strong class="card-title text_primary">Follow Ups</strong><br>

                                                    </div>
                                                    <div class="card-body overflow-auto p-2">
                                                        <?php
                                                            $response = $pdo->query("select * from cli_mgmt_sys_response where `client` = '$id' order by `id` desc");
                                                            while($res = $response->fetch(PDO::FETCH_ASSOC)):
                                                        ?>
                                                        <small class="text_primary">@<?php echo $res['owner'] ?></small> <small><?php echo $res['created_at'] ?></small><br>
                                                            <?php echo base64_decode($res['details']) ?>
                                                        <hr>
                                                        <?php endwhile; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </article>

                        <?php endif; ?>

                    <?php endif; ?>

                </article>
            </div>

        </div>
    </div>
</div>