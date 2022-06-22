<?php
    @!session_start();
    //permissions
    $perm_sql = "select COLUMN_NAME from information_schema.columns where table_name='user_access_level' and column_name like 'Perm%'";
    $perm_stmt = $pdo->prepare($perm_sql);
    $perm_stmt->execute();
    while ($permissions = $perm_stmt->fetch(PDO::FETCH_ASSOC))
    {
        $permissions_column = $permissions['COLUMN_NAME'];
        $permission = explode('_' , $permissions_column);
        if (count($permission) > 2)
        {
            $toUser = strtolower($permission[1].'_'.$permission[2]);
        }
        else
        {
            $toUser = strtolower($permission[1]);
        }


        //select user permission
        $select_ual_sql = "SELECT $permissions_column FROM `user_access_level` WHERE `access_level` = ?";
        $select_ual_stmt = $pdo->prepare($select_ual_sql);
        $select_ual_stmt->execute([$_SESSION['ual']]);
        $ual_res = $select_ual_stmt->fetch(PDO::FETCH_ASSOC);


        if ($ual_res[$permissions_column] === 1)
        {

            $_SESSION[$toUser] = true;
        }
        else
        {
            $_SESSION[$toUser] = false;
        }

    }