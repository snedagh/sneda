<?php

namespace\tools::class;

class tools
{
    public function setSession($variable,$value)
    {
        $_SESSION["$variable"] = $value;
    }

    public function getSession($variable)
    {
        if(isset($_SESSION["$variable"]))
        {
            return $_SESSION["$variable"];
        }
        else
        {
            return "NOT SET";
        }
    }

    public function formData($method,$variable)
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

        return htmlentities($res);
    }

    public function back()
    {
        header("Location:".$_SERVER['HTTP_REFERER']);
    }

    public function br($str)
    {
        echo date("Y-m-d H:m:s") . " > $str";
    }


}

// forms
class forms
{
    public $name;
    public $type;
    public $placeholder;
    public $details;
    public $req;
    public $class;
    public $value;
    /**
     * @var int|mixed
     */
    public $rows;

    function __construct($name, $type, $value = '',$class='') {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->class = $class;

    }

    function input_text($placeholder='',$details='',$req='no'): string
    {
        $this->placeholder = $placeholder;
        $this->details = $details;
        $this->req = $req;

        if($this->req === 'yes')
        {
            $input =  "
                    <div class='input-group mb-1'>
                        <input autocomplete='off' required type='$this->type' name='$this->name' value='$this->value' class='$this->class' 
                        placeholder='$this->placeholder'>
                        <small class='text-info'>$this->details</small>
                    </div>
                ";
        }
        else
        {
            $input =  "
                    <div class='input-group mb-1'>
                        <input autocomplete='off' type='$this->type' name='$this->name' class='form-control w-100 rounded-0' 
                        placeholder='$this->placeholder'>
                        <small class='text-info'>$this->details</small>
                    </div>
                ";
        }

        return $input;

    }

    function button(): string
    {

        return "<button name='$this->name' class='$this->class' type='$this->type'>$this->value</button>";
    }

    function textarea($placeholder = '', $rows = 3):string
    {
        $this->placeholder = $placeholder;
        $this->rows = $rows;
        return "
            <div class='input-group mb-2'>
                <textarea class='der_det w-100' name='$this->name' rows='$this->rows' placeholder='$this->placeholder'>$this->value</textarea>
            </div>
        ";
    }

}