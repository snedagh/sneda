<?php


class tools
{
    private function formData($method,$variable)
    {
        if($method == 'POST')
        {
            $res = $_POST["$variable"];
        }
        elseif ($method == 'GET')
        {
            $res = $_GET["$variable"];
        }
        else
        {
            $res = "NOT SET";
        }

        return $res;
    }
}