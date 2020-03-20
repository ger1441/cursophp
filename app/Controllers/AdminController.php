<?php
namespace App\Controllers;

use Laminas\Diactoros\Response\RedirectResponse;

class AdminController extends BaseController
{
    public function getIndex(){
        return $this->renderHTML('admin.twig');
    }
    public function getAdmin(){
        return new RedirectResponse('/admin');
    }
}