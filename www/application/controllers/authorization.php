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
    }

    function login()
    {
        $cookieSUID = get_cookie($this->nameSUID);
        $userID = get_cookie($this->nameUID);
        
        // Если нет куки - редиректим
        if ($cookieSUID === FALSE && $userID === FALSE)
        {
            $this->authorization_model->login();
        }else{
            redirect('/');
        }
        
    }

    function logout()
    {
        $this->authorization_model->logout();
    }

}
