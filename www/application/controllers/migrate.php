<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Migrate extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function update($version = FALSE)
    {
        $this->load->library('migration');

        if ($version !== FALSE)
        {
            if ($this->migration->version($version) === TRUE)
            {
                echo 'Структура Вашей базы данных уже обновлена до данной версии!';
            }
            else if ($this->migration->version($version) === FALSE)
            {
                show_error($this->migration->error_string());
            }
            else
            {
                echo 'Обновление структуры базы данных успешно завершено! Текущая версия структуры #' . $version;
            }
        }
        else
        {
            if ($this->migration->latest() === FALSE)
            {
                show_error($this->migration->error_string());
            }
            else
            {
                echo 'У вас самая последняя версия структуры базы данных!';
            }
        }
    }

}
