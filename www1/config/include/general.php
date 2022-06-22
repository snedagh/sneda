<?php
    include 'session_local.php';
    

    ##############################
    ### ENABLE EDIT ALL ##########
    ##############################
    if($_GET['t'] == 'edit_all')
    {
        $stat = $_GET['s'];
        echo $stat;
        if($stat == 'e')
        {
            $_SESSION['edit_all'] = true;
            header("Location:".$_SERVER['HTTP_REFERER']);
        }
        else
        {
            $_SESSION['edit_all'] = false;
            header("Location:".$_SERVER['HTTP_REFERER']);
        }
    }
?>