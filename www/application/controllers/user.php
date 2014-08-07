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

        $this->form_validation->set_rules('login', 'Логин', 'required|trim|xss_clean|is_unique[users.login]');
        $this->form_validation->set_rules('password', 'Пароль', 'required|trim|xss_clean|matches[password_confirm]');

        if (!$this->form_validation->run())
        {
            $this->errors = validation_errors('<li>', '</li>');
            return FALSE;
        }

        return TRUE;
    }

}
