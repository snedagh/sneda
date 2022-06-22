<?php
    require '../inc/db.php';
    require '../inc/session.php';

    if (isset($_GET['t']) && isset($_GET['id']))
    {
        $task = $_GET['t'];
        if($task === 'del_cat')
        {
            //delete
            $sql = "DELETE FROM `facCat` WHERE `id` = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_GET['id']]);
            $_SESSION['notif'] = "Category Delete Successfully";
            header("Location:".$_SERVER['HTTP_REFERER']);
        }
        elseif ($task === 'select_cat')
        {
            $_SESSION['cat_select'] = $_GET['id'];
            header("Location:".$_SERVER['HTTP_REFERER']);
        }
    }