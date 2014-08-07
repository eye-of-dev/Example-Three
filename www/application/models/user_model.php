<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model
{

    // Заводим некоторые переменные для удобства дальннейшего использования
    private $usersTAB = 'users';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('authorization_model', 'authorization_model');
    }

    /**
     * Регистрация нового игрока
     * Авторизация его и передаем данные на авторизацию
     */
    public function registration()
    {

        $data = array(
            'login' => $this->input->post('login'),
            'password' => md5($this->input->post('password'))
        );

        $this->db->insert($this->usersTAB, $data);

        // Авторизируем игрока
        $this->authorization_model->login();
    }
    
    /**
     * Получение пользовалтеля по его идентификатору
     * @param int $user_id Идентификатор пользователя
     * @return array Данные о пользователе
     */
    public function getUserById($user_id)
    {
        $this->db->where('id', $user_id);
        $query = $this->db->get($this->usersTAB);
        
        return $query->row();
    }

}
