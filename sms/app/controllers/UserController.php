<?php
class UserController extends Controller
{
    public function index()
    {
        
        $this->view('auth/login',array('text'));
        // $this->viewWithLayout('user/index',array('text'));
    }
    public function dashboard()
    {
        $data       = ['title' => 'HAMOS | Dashboard',
                       'breadcrums1' => 'Home',
                       'breadcrums2' => 'Dashboard'];
        $this->viewWithLayout('dashboard',$data);
    }
}