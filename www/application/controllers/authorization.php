<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization extends CI_Controller
{

    private $nameSUID = 'GAME_SUID';
    private $nameUID = 'GAME_UID';
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('authorization_model', 'authorization_model');
        
        $this->lang->load('auth', get_cookie('language'));
        
    }

    function login()
    {
        $data['errors'] = '';
        $data['title'] = $this->lang->line('title');
        $data['welcome'] = $this->lang->line('welcome');
        $data['tlogin'] = $this->lang->line('login');
        $data['password'] = $this->lang->line('password');
        $data['button_text'] = $this->lang->line('button_text');
        $data['reg'] = $this->lang->line('reg');
        
        $cookieSUID = get_cookie($this->nameSUID);
        $userID = get_cookie($this->nameUID);
        
        // Если нет куки - редиректим
        if ($cookieSUID === FALSE && $userID === FALSE)
        {
            $data['errors'] = $this->authorization_model->login();
        }else{
            redirect('/');
        }
        
        $data['login'] = $this->input->post('login');
        
        $this->load->view('user/login-view', $data);
        
    }

    function logout()
    {
        $this->authorization_model->logout();
    }

}
