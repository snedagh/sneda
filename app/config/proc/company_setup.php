<?php
    include '../inc/session.php';
    include '../inc/db.php';
    $main = $_SESSION['company_setup_main'];
    $config = $_SESSION['company_setup_config'];

    if(isset($_GET['main_nav']))
    {
        $_SESSION['company_setup_main'] = $_GET['main']; //get main
        header("Location:".$_SERVER['HTTP_REFERER']); //redirect to previous page
        exit();
    }

    ##############################
    ### SUB NAV ##################
    ##############################
    elseif(isset($_GET['sub_nav']))
    {
        if (isset($_GET['edit_all']) && !empty($_GET['edit_all']))
        {
            if ($_GET['edit_all'] === 'yes')
            {
                $_SESSION['edit_all'] = true;
                header("Location:".$_SERVER['HTTP_REFERER']);

            }
            elseif($_GET['edit_all'] === 'no')
            {
                $_SESSION['edit_all'] = false;
                header("Location:".$_SERVER['HTTP_REFERER']);
            }
        }
        else
        {
            $_SESSION['company_setup_sub'] = $_GET['sub'];
            header("Location:".$_SERVER['HTTP_REFERER']);
        }

    }

    ##############################
    ### SAVING EDIT ##############
    ##############################
    elseif(isset($_POST['commitEdit']))
    {
        $main = $_SESSION['company_setup'];

        if($main == 'Tax')// if edititng a tax item
        {
            echo "Saving tax edit";
        }
        elseif($main == "Others") // if editing othere
        {
           ####
           # if edtiting company setup
           ####
           
           if($config == "csetup")
           {
               echo 'saving company setup edit';
           }

           ####
           # if edtiting compare
           ####
           
           if($config == "compare")
           {
               echo 'saving compare edit';
           }

        }
        elseif($config == 'cursetup')// if we editing currency setup
        {
            echo "Hello";
        }
    }

    ##############################
    ### SAVING NEW ###############
    ##############################
    elseif(isset($_POST['saveNew']))
    {
        $main = $_SESSION['company_setup'];

        if($main == 'Tax') //adding new tax
        {
            echo "Saving tax New";
        }
        elseif($main == "Others") //other
        {
            if($config == "cursetup") //adding new currency
            {
                echo 'adding new currency';
            }
        }
    }

    ##############################
    ### DEL ITEM #################
    ##############################
    elseif(isset($_GET['del']))
    {
        $main = $_SESSION['company_setup'];

        if($main == "Tax")
        {
            echo 'Deleting Tax Item';
        }
    }

    ##############################
    ### ITEM NAV #################
    ##############################
    elseif(isset($_GET['item_nav']))
    {
        if($main == "Tax")
        {
            $direction = $_GET['direction'];
            if($direction == "Previous")
            {
                echo 'Moving back tax';
            }
            else
            {
                echo 'Moving Fromt Tax';
            }
        }
    }

    ##############################
    ### ENABLE DISABLE ###########
    ##############################
    elseif(isset($_GET['en~db']))
    {
        $wht = $_GET['wht'];
        $task = $_GET['t'];
        if($wht == "pmtd" && $task == "en")
        {   
            echo "Enabling Payment";
        }
        elseif($wht == "pmtd" && $task == "db")
        {
            echo "Disabling Payment";
        }
        elseif($wht == "curChng")
        {   
            $task = $_GET['t'];
            if($task == "db")
            {
                echo 'currencey db';
            }
            else
            {
                echo 'currencey en';
            }
        }
    }

    ##############################
    ### Backup Database ##########
    ##############################
    elseif(isset($_GET['backup_db']))
    {
        echo "Performing a database backup";
    }

    ##############################
    ### RESTORE Database #########
    ##############################
    elseif(isset($_POST['restoredb']))
    {
        echo "Performing a database Restore";
    }

    ##############################
    ### Config Change #############
    ##############################
    elseif(isset($_GET['config_chg']))
    {
        $_SESSION['company_setup_config'] = $_GET['config'];
        header("location:".$_SERVER['HTTP_REFERER']);

    }
?>