<?php if($view == 'all'): ?>

    <div class="w-100 h-100 bg-celadon-green overflow-hidden container-fluid">

        <button onclick="set_session('view=new','<?php echo $token ?>')" class="new_app btn btn-outline-success m-2">
            N
        </button>
        <div class="modal fade" id="newapp">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong class="modal-title">Add App</strong>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <form action="config/proc/ajax.php" method="POST" class="p-2" id="regular_for">
                            <div class="row mb-2">
                                <!--APP NAME -->
                                <div class="col-sm-6 p-2">
                                    <div class="w-100 p-2 cust_shadow">
                                        <label class="w-100">App Name
                                            <input type="text" name="name" class="form-control" autocomplete="off">
                                        </label>
                                    </div>
                                </div>

                                <!-- TRIGGER -->
                                <div class="col-sm-6 p-2">
                                    <div class="w-100 p-2 cust_shadow">
                                        <label class="w-100">Trigger
                                            <input type="text" name="trigger" class="form-control" autocomplete="off">
                                        </label>
                                    </div>
                                </div>
                            </div>



                            <!-- URL && TYPE -->
                            <div class="row">
                                <!--URL -->
                                <div class="col-sm-6 p-2">
                                    <div class="w-100 p-1 cust_shadow">
                                        <label class="w-100">Url
                                            <input required type="text" name="url" class="form-control" autocomplete="off">
                                        </label>
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-sm-6 p-2">
                                    <div class="w-100 p-1 cust_shadow">
                                        <label class="w-100">Type
                                            <select class="form-control" name="type" required>
                                                <option>offline</option>
                                                <option>admin</option>
                                                <option>store</option>
                                                <option>nav</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="w-100 p-2 mb-2 cust_shadow">
                                <label for="" class="w-100">
                                    Description
                                </label>
                                <textarea name="details" class="form-control" id="" rows="2"></textarea>
                            </div>

                            <input type="hidden" name="token" value="<?php echo session_id() ?>">
                            <input type="hidden" name="function" value="new_app">

                            <!-- SET -->
                            <div class="w-100 p-2 cust_shadow">
                                <label for="" class="w-100">
                                    Session Configurations <br>
                                    <small>Separate each session variable vale with <kbd>:</kbd> and session element with <kbd>,</kbd> ie.</small><br>
                                    <small><kbd><span class="text-info">sess1var</span>:sess1value,<span class="text-info">sess2var</span>:sess2value</kbd></small>
                                </label>
                                <textarea name="sets" class="form-control" id="" rows="2"></textarea>
                            </div>

                            <button class="btn btn-info m-1" type="submit" name="update_appp">UPDATE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-dark no-gutters">
            <?php while ($ap = $apps->fetch(PDO::FETCH_ASSOC)):
                $type = $ap['type'];
                $form_id = '123'.$ap['id'];
                ?>
                <div class="col-sm-2 p-2">
                    <div data-toggle="modal" data-target="#app_details<?php echo $ap['id'] ?>" class="card app_st_cont rounded-0">
                        <img src="assets/icons/apps_icon/def.jpg" alt="" class="card-img">
                        <div class="card-body p-2">
                            <div class="w-100 clearfix">
                                <p class="text_eclips font-weight-bolder w-75 float-left m-0"><?php echo $ap['name'] ?></p>
                                <div class="text_eclips font-weight-bolder w-25 text-right float-right m-0">
                                    <?php
                                    if($type == 'admin')
                                    {
                                        echo '<img src="assets/icons/apps_icon/admin_apps.png" style="width: 15px; height: 15px" alt="">';
                                    } elseif ($type == 'store')
                                    {
                                        echo '<img src="assets/icons/apps_icon/app_store.png" style="width: 15px; height: 15px" alt="">';
                                    } elseif ($type == 'nav')
                                    {
                                        echo '<img src="assets/icons/apps_icon/nav.png" style="width: 15px; height: 15px" alt="">';
                                    } elseif ($type == 'offline')
                                    {
                                        echo '<img src="assets/icons/apps_icon/offline.png" style="width: 15px; height: 15px" alt="">';
                                    }
                                    ?>
                                </div>
                            </div>
                            <small class="text-muted">Description  <?php echo $type ?></small>
                        </div>
                    </div>

                    <div class="modal fade" id="app_details<?php echo $ap['id'] ?>">
                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <strong class="modal-title">App</strong>
                                    <button onclick="enable_form('<?php echo $form_id ?>')" class="close btn btn-sm btn-info">EDIT</button>
                                    <button class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body p-2">
                                    <form action="config/proc/ajax.php" method="POST" class="p-2" id="<?php echo $form_id ?>">
                                        <div class="row mb-2">
                                            <!--APP NAME -->
                                            <div class="col-sm-6 p-2">
                                                <div class="w-100 p-2 cust_shadow">
                                                    <label class="w-100">App Name
                                                        <input type="text" name="name" class="form-control" autocomplete="off" value="<?php echo $ap['name'] ?>">
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- TRIGGER -->
                                            <div class="col-sm-6 p-2">
                                                <div class="w-100 p-2 cust_shadow">
                                                    <label class="w-100">Trigger
                                                        <input type="text" name="trigger" class="form-control" autocomplete="off" value="<?php echo $ap['app_trigger'] ?>">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- URL && TYPE -->
                                        <div class="row">
                                            <!--URL -->
                                            <div class="col-sm-6 p-2">
                                                <div class="w-100 p-1 cust_shadow">
                                                    <label class="w-100">Url
                                                        <input required type="text" name="url" class="form-control" autocomplete="off" value="<?php echo $ap['path'] ?>">
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Type -->
                                            <div class="col-sm-6 p-2">
                                                <div class="w-100 p-1 cust_shadow">
                                                    <label class="w-100">Type
                                                        <select class="form-control" name="type" required>
                                                            <option>offline</option>
                                                            <option>admin</option>
                                                            <option>store</option>
                                                            <option>nav</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DESCRIPTION -->
                                        <div class="w-100 p-2 mb-2 cust_shadow">
                                            <label for="" class="w-100">
                                                Description
                                            </label>
                                            <textarea name="details" class="form-control" id="" rows="2"><?php echo $ap['details'] ?></textarea>
                                        </div>

                                        <input type="hidden" name="token" value="<?php echo session_id() ?>">
                                        <input type="hidden" name="app" value="<?php echo $ap['id'] ?>">
                                        <input type="hidden" name="function" value="update_app">

                                        <!-- SET -->
                                        <div class="w-100 p-2 cust_shadow">
                                            <label for="" class="w-100">
                                                Session Configurations <br>
                                                <small>Separate each session variable vale with <kbd>:</kbd> and session element with <kbd>,</kbd> ie.</small><br>
                                                <small><kbd><span class="text-info">sess1var</span>:sess1value,<span class="text-info">sess2var</span>:sess2value</kbd></small>
                                            </label>
                                            <textarea name="sets" class="form-control" id="" rows="2"><?php echo $ap['sets'] ?></textarea>
                                        </div>

                                        <button class="btn btn-info m-1" type="submit" name="update_appp">UPDATE</button>
                                    </form>
                                    <script>
                                        var form = document.getElementById(<?php echo $form_id ?>);
                                        var elements = form.elements;
                                        for (var i = 0, len = elements.length; i < len; ++i) {
                                            elements[i].disabled = true;
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

<?php endif; ?>

<?php if($view == 'new'): ?>

    <div class="w-100 h-100 bg-cus-muted overflow-hidden container-fluid">
        <form action="config/proc/ajax.php" method="POST" class="p-2 w-75 mx-auto" id="regular_for">
            <div class="row mb-2">
                <!--APP NAME -->
                <div class="col-sm-6 p-2">
                    <div class="w-100 p-2 cust_shadow">
                        <label class="w-100">App Name
                            <input type="text" name="name" class="form-control" autocomplete="off">
                        </label>
                    </div>
                </div>

                <!-- TRIGGER -->
                <div class="col-sm-6 p-2">
                    <div class="w-100 p-2 cust_shadow">
                        <label class="w-100">Trigger
                            <input type="text" name="trigger" class="form-control" autocomplete="off">
                        </label>
                    </div>
                </div>
            </div>



            <!-- URL && TYPE -->
            <div class="row">
                <!--URL -->
                <div class="col-sm-6 p-2">
                    <div class="w-100 p-1 cust_shadow">
                        <label class="w-100">Url
                            <input required type="text" name="url" class="form-control" autocomplete="off">
                        </label>
                    </div>
                </div>

                <!-- Type -->
                <div class="col-sm-6 p-2">
                    <div class="w-100 p-1 cust_shadow">
                        <label class="w-100">Type
                            <select class="form-control" name="type" required>
                                <option>offline</option>
                                <option>admin</option>
                                <option>store</option>
                                <option>nav</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="w-100 p-2 mb-2 cust_shadow">
                <label for="" class="w-100">
                    Description
                </label>
                <textarea name="details" class="form-control" id="" rows="2"></textarea>
            </div>

            <input type="hidden" name="token" value="<?php echo session_id() ?>">
            <input type="hidden" name="function" value="new_app">

            <!-- SET -->
            <div class="w-100 p-2 cust_shadow">
                <label for="" class="w-100">
                    Session Configurations <br>
                    <small>Separate each session variable vale with <kbd>:</kbd> and session element with <kbd>,</kbd> ie.</small><br>
                    <small><kbd><span class="text-info">sess1var</span>:sess1value,<span class="text-info">sess2var</span>:sess2value</kbd></small>
                </label>
                <textarea name="sets" class="form-control" id="" rows="2"></textarea>
            </div>

            <div class="w-100 d-flex flex-wrap justify-content-between">
                <button class="btn btn-info m-1" type="button" name="update_appp">MEDIA</button>
                <button class="btn btn-success m-1" type="submit" name="update_appp">CREATE</button>
            </div>
        </form>
    </div>

<?php endif; ?>
