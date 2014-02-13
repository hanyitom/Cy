<?php
namespace controller;

use Cy\Mvc\Controller\Controller;

class index extends Controller
{
    public function index()
    {
        echo 1;
    }
    public function jumpExaple(){
        $this->jump('success','test',$_SERVER['HTTP_HOST'].'/test');
        $this->jump('danger','test',$_SERVER['HTTP_HOST'].'/test');
        $this->jump('info','test',$_SERVER['HTTP_HOST'].'/test');
        $this->jump('warning','test',$_SERVER['HTTP_HOST'].'/test');
    }
}
