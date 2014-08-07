<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('authorization_model', 'authorization_model');
    }

    function login()
    {
        $this->authorization_model->login();
    }

    function logout()
    {
        $this->authorization_model->logout();
    }

}
