<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller
{

    private $errors;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model', 'user_model');
        
        $this->lang->load('user', get_cookie('language'));
    }

    public function index()
    {
        redirect('user/registration');
    }

    /**
     * Регистрация нового игрока
     */
    public function registration()
    {

        $data = array();

        $data['title'] = $this->lang->line('title');
        $data['welcome'] = $this->lang->line('welcome');
        $data['tlogin'] = $this->lang->line('login');
        $data['password'] = $this->lang->line('password');
        $data['conf_pass'] = $this->lang->line('conf_pass');
        $data['button_text'] = $this->lang->line('button_text');
        $data['auth'] = $this->lang->line('auth');
        $data['reg'] = $this->lang->line('reg');
        
        if ($this->input->post() AND $this->valid())
        {
            $this->user_model->registration();
        }

        $data['login'] = $this->input->post('login');
        $data['errors'] = $this->errors;

        $this->load->view('user/registr-view', $data);
    }

    /**
     * Функция валидации формы ругистрации
     * @return boolean
     */
    private function valid()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('login', $this->lang->line('login'), 'required|trim|xss_clean|is_unique[users.login]');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'required|trim|xss_clean|matches[password_confirm]');

        if (!$this->form_validation->run())
        {
            $this->errors = validation_errors('<li>', '</li>');
            return FALSE;
        }

        return TRUE;
    }

}
