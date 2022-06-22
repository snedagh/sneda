<?php 
    include '../inc/session.php';
    include '../inc/db.php';
    $owner = $_SESSION['username'];

    function gb($to)
    {
        header("Location:".$to);
    }

    ########################
    ### Category Nave ######
    ########################
    if(isset($_GET['nav']))
    {
        $type = $_GET['type'];
        if($type == 'cat')//if we are navigating from cat to cat
        {
            $category = $_GET['catname'];
            echo 'we are navigating to '.$category;
        }
    }

    ########################
    ### Category ADD #######
    ########################
    elseif(isset($_POST['addCat']))
    {
        $category = htmlentities($_POST['categoryName']);

        //check if category exist
        $check = "SELECT * FROM `facCat` WHERE `name` = ?";
        $check_stmt = $pdo->prepare($check);
        $check_stmt->execute([$category]);
        if ($check_stmt->rowCount() > 0)
        {
            $_SESSION['notif'] = "Category Exist";
            gb($_SERVER['HTTP_REFERER']);
            exit();
        }

        else
        {

            //add facility
            $sql = "INSERT INTO `facCat` (`name` , `owner`) VALUES (?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$category , $owner]);
            $_SESSION['notif'] = $category ." Added Successfully";
            $_SESSION['fac_cat_main_view'] = 'view';

            //get current facility
            $cStndSql = "SELECT * FROM `facCat` WHERE `name` = ?";
            $cStndStmt = $pdo->prepare($cStndSql);
            $cStndStmt->execute([$category]);
            $cat_res = $cStndStmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['cat_standing'] = $cat_res['id'];

            header("Location:".$_SERVER['HTTP_REFERER']);
        }
    }

    ########################
    ### Category FAC #######
    ########################
    elseif(isset($_POST['addFacility']))
    {
        $facility = htmlentities($_POST['facName']);
        $category = $_SESSION['cat_standing'];
        $space = htmlentities($_POST['space']);
        $cost = htmlentities($_POST['cost']);

        //add facility
        $sql = "INSERT INTO `facilities` (`facCat` , `name` , `owner` , `total_space`,`cost`) VALUES (?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$category , $facility , $_SESSION['username'] , $space,$cost]);
        $_SESSION['notif'] = $facility . ' has been added to category ' . $category;
        header("Location:".$_SERVER['HTTP_REFERER']);
    }

    ########################
    ### RENAME   FAC #######
    ########################
    elseif (isset($_POST['rename_facility']))
    {
        $current_name = htmlentities($_POST['current_name']);
        $current_id = htmlentities($_POST['current_id']);
        $new_name = htmlentities($_POST['new_name']);
        $total_space = htmlentities($_POST['total_space']);
        //check if cacility exist with same name
        $v_sql = "SELECT * FROM `facilities` WHERE `name` = ? AND `id` != ?";
        $v_stmt = $pdo->prepare($v_sql);
        $v_stmt->execute([$new_name,$current_id]);
        $validate = $v_stmt->rowCount();

        if ($v_stmt->rowCount() > 0) //if name exist
        {
            $_SESSION['notif'] = 'New name taken, select a different name';
            header("Location:".$_SERVER['HTTP_REFERER']);
            exit();
        }
        else
        {

            //if name does not exist update
            $u_sql = "UPDATE `facilities` SET `name` = ? , `total_space` = ? WHERE `id` = ?";
            $u_stmt = $pdo->prepare($u_sql);
            $u_stmt->execute([$new_name , $total_space , $current_id]);

            if($u_stmt->rowCount() > 0)
            {
                $_SESSION['notif'] = 'Record Modified Successfully';
                header("Location:".$_SERVER['HTTP_REFERER']);
                exit();
            }
            else
            {
                $_SESSION['notif'] = 'Rename Not Successful';
                header("Location:".$_SERVER['HTTP_REFERER']);
                exit();
            }
        }

    }

    ########################
    ### DELETE   FAC #######
    ########################
    elseif (isset($_GET['delete']))
    {
        //delete facility
        $facility = $_GET['delete'];

        $del_sql = "DELETE FROM `facilities` WHERE `id` = ?";
        $del_stmt = $pdo->prepare($del_sql);
        $del_stmt->execute([$facility]);
        $_SESSION['notif'] = "Facility Deleted Successfully!!";
        gb($_SERVER['HTTP_REFERER']);
    }

    ########################
    ### RENAME   CAT #######
    ########################
    elseif (isset($_POST['commitEdit']))
    {
        $cat_target = htmlentities($_POST['currentCat']);
        $new_name = htmlentities($_POST['name']);
        $charge_type = htmlentities($_POST['charge_type']);
        $currentCatId = htmlentities($_POST['currentCatId']);

        //check if new name taken
        $check = "SELECT * FROM `facCat` WHERE `name` = ? AND `id` != ?";
        $check_stmt = $pdo->prepare($check);
        $check_stmt->execute([$new_name,$currentCatId]);
        if ($check_stmt->rowCount() > 0)
        {
            $_SESSION['notif'] = 'Category ' . $new_name . ' taken';
            header("Location:".$_SERVER['HTTP_REFERER']);
            exit();
        }
        else
        {
            //update
            $sql = "UPDATE `facCat` SET `name` = ? , `charges_type` = ? WHERE `id` = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$new_name , $charge_type ,$currentCatId]);
            $_SESSION['notif'] = 'Category Updated Successfully';
            $_SESSION['fac_cat_main_view'] = 'view';
            header("Location:".$_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    ########################
    ### DELETE   CAT #######
    ########################
    elseif (isset($_GET['deleteCategory']))
    {
        $catId = $_SESSION['cat_standing'];
        $del_sql = "DELETE FROM `facCat` WHERE `id` = ?";
        $del_stmt = $pdo->prepare($del_sql);
        $del_stmt->execute([$catId]);
        //get next
        $next_sql = "SELECT * FROM `facCat` WHERE `id` > ? LIMIT 1";
        $next_stmt = $pdo->prepare($next_sql);
        $next_stmt->execute([$_SESSION['cat_standing']]);

        if($next_stmt->rowCount() > 0)
        {
            $next_d = $next_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['cat_standing'] = $next_d['id'];
            $_SESSION['notif'] = "Category Deleted";
            gb($_SERVER['HTTP_REFERER']);
            exit();
        }
        else
        {
            //get previous
            $prev_sql = "SELECT * FROM `facCat` WHERE `id` < ? LIMIT 1";
            $prev_stmt = $pdo->prepare($prev_sql);
            $prev_stmt->execute([$_SESSION['cat_standing']]);
            if ($prev_stmt->rowCount() > 0)
            {
                $prev_d = $prev_stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['cat_standing'] = $prev_d['id'];
                $_SESSION['notif'] = "Category Deleted";
                gb($_SERVER['HTTP_REFERER']);
                exit();
            }
        }

        gb($_SERVER['HTTP_REFERER']);
    }

    #######################
    ### CHANGE RANGE ######
    #######################
    elseif (isset($_POST['face_range']))
    {
        //if facility range
        if (isset($_POST['task']) && $_POST['task'] === 'facility')
        {
            $_SESSION['facRange'] = $_POST['range'];
        }

        gb($_SERVER['HTTP_REFERER']);
    }