<?php
class UserController extends Controller
{
    public function login()
    {
        
        $this->view('auth/login',array('text'));
        // $this->viewWithLayout('user/index',array('text'));
    }
    public function dashboard()
    {
        $data = ['title' => 'Dashboard'];
        $this->viewWithLayout('dashboard',$data);
    }
}
