<?php
namespace Cy\Plugin\Filter;

class Filter{
    public function isEmail($email, $emailServer = array()){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
        if(empty($emailServer))
            return true;
        foreach($emailServer as $v)
            if(strstr($email, $v))
                return true;
        return false;
    }

    public function isIp($ip){
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public function isUrl($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public function email($email, $emailServer = array()){
        if($this->isEmail($email, $emailServer))
            return filter_input($email, FILTER_SANITIZE_EMAIL);
    }

    public function filterInput($isGet = false){
        if($isGet)
            return $this->filter($_GET);
        else
            return $this->filter($_POST);
    }

    private function filter($val){
        foreach($val as $k=>$v){
            $v = trim($v);
            switch(true){
                case $this->isEmail($v):
                    $val[$k] = $this->email($v);
                break;
                case $this->isUrl($v):
                    $val[$k] = url_encode(addslashes($v));
                break;
                case $this->inject_check($v):
                    $t = addslashes(str_ireplace($v);
                    $t = str_ireplace('union','',$t);
                    $t = str_ireplace('where','',$t);
                    $val[$k] = str_ireplace('join',$t);
                break;
                default:
                    $val[$k] = filter_var($v);
            }
            return $val;
        }
    }
    private function inject_check($val){
        $regexp = '%(&|\||\^|\$|\*i|unoin|join|where)%/ig';
        return preg_match($regexp,$val);
    }
}
