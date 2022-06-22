<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once '../include/core.php';

   if($_SERVER['REQUEST_METHOD'] === 'GET')
   {
       ###app nav
       if (isset($_GET['app']))
       {

           $token = session_id();
           $uni = $_GET['app'];

           if($uni === 'ae281c6806257c0ef2e30cae52c0c910')
           {
               br('Insall App');
               $_SESSION['location'] = 'install_app';
               die();
               back();
               die();
           }

           ;

           $app = fetchFunc('appstore', "`uni` = '$uni'", $pdo);





           $path = $app['path'];
           $set = $app['sets'];




           if($path === 'return')
           {
               $url = $_SERVER['HTTP_REFERER'];
           }
           else
           {
               $url = $path.'?token='.session_id();
           }
           br($url);







           if(count(explode('.',$set)) > 0)
           {
               $sets = explode(',',$set);

               foreach ($sets as $key => $value)
               {
                   $clean = explode(':', $value);
                   $set_index = $clean[0];
                   $set_val = $clean[1];

                   //set sessions
                   $_SESSION[$set_index] = $set_val;
                   br($set_index . ' set to '. $set_val);
               }
           }



           header("Location:".$url);
       }
   } elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
       ###app nav
       if (isset($_POST['app']))
       {

           $token = session_id();
           $uni = $_POST['app'];

           if($uni === 'ae281c6806257c0ef2e30cae52c0c910')
           {
               br('Insall App');
               $_SESSION['location'] = 'install_app';
               die();
               back();
               die();
           }

           ;

           $app = fetchFunc('appstore', "`uni` = '$uni'", $pdo);





           $path = $app['path'];
           $set = $app['sets'];




           if($path === 'return')
           {
               echo 'target%%reload';
           }
           else
           {
               $url = $path.'?token='.session_id();
               echo "target%%$url";
           }
//           br($url);







           if(count(explode('.',$set)) > 0)
           {
               $sets = explode(',',$set);

               foreach ($sets as $key => $value)
               {
                   $clean = explode(':', $value);
                   $set_index = $clean[0];
                   $set_val = $clean[1];

                   //set sessions
                   $_SESSION[$set_index] = $set_val;
                   //br($set_index . ' set to '. $set_val);
               }
           }



//           header("Location:".$url);
       }
   }

    ##file preview

