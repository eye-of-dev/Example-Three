<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization_model extends CI_Model
{

    // Заводим некоторые переменные для удобства дальннейшего использования
    private $usersTAB = 'users';
    private $cookieTIME = 2678400;
    private $nameSUID = 'GAME_SUID';
    private $nameUID = 'GAME_UID';
    // Профиль текущего пользователя
    public $profile = NULL;

    public function check()
    {

        // Есть небольшой косяк в этом методе - когда переходишь на страницу authorization/login и уже авторизован,
        // то тебя должно кидать на главную внутреннюю страницу, а вместо этого показывается форма авторизации
        // Получаем текущий урл для проверки, вырезаем лишний слеш для контрола
        $currentURL = site_url(preg_replace('#^/#', NULL, uri_string()));

        // Формируем искомый урл страницы авторизации для сверки
        $baseURL = site_url('authorization/login');

        // Формируем урл страницы миграции для сверки
        $migrateURL = site_url('migrate/update');

        // Если это авторизация или миграция - пропускаем
        if ($currentURL === $baseURL OR $currentURL === $migrateURL)
        {
            return;
        }

        // Получаем уникальные идентификаторы пользователя из cookies
        $cookieSUID = get_cookie($this->nameSUID);
        $userID = get_cookie($this->nameUID);

        // Если нет куки - редиректим
        if ($cookieSUID === FALSE && $userID === FALSE)
        {
            $this->logout();
        }

        // Пытаемся получить пользователя
        $this->db->where('id', $userID);
        $query = $this->db->get($this->usersTAB);

        // Если пользователь на найден - редиректим
        if ($query->num_rows() === 0)
        {
            $this->logout();
        }
        elseif ($query->num_rows() > 1)
        {
            log_message('error', 'authorization_model->check(): Количество пользователей с userID = ' . $userID . ' в таблице ' . $this->usersTAB . ' больше 1');
        }

        $this->profile = $query->row();


        // Если хеш не входит в массив записанных в базе - редиректим
        if ($this->profile->sid != $cookieSUID)
        {
            $this->logout();
        }
        else
        {
            return;
        }

        redirect('/');
    }

    public function login()
    {
        $password = $this->input->post('password');
        $login = $this->input->post('login');

        if ($password && $login)
        {
            // Проверяем правильность введенных данных
            $this->db->where('login', $login);
            $this->db->where('password', md5($password));
            $query = $this->db->get($this->usersTAB);
            $this->profile = $query->row();

            if ($this->profile)
            {
                // Генерируем SUID и записываем его в Cookies
                $SUID = md5($this->profile->login . uniqid(rand(), 1));

                // Обновляем хеш, а так же дату и время последнего входа в систему
                $this->db->where('id', $this->profile->id);
                $this->db->set('sid', $SUID);
                $this->db->update($this->usersTAB);

                $results = simplexml_load_file(base_url('data/players.xml'));
                $player = $results->players->addChild('player', $this->profile->login);
                $player->addAttribute('id', $this->profile->id);
                $player->addAttribute('status', 'free');
                $player->addAttribute('rival_id', '');
                $player->addAttribute('rival_name', '');
                $player->addAttribute('game', '');
                $player->addAttribute('suid', $SUID);
                $player->addAttribute('mark', '');
                $player->addAttribute('result', '');
                $player->addAttribute('comment', '');
                $results->asXML(FCPATH . 'data/players.xml');

                // Добавляем куки
                set_cookie($this->nameSUID, $SUID, $this->cookieTIME);
                set_cookie($this->nameUID, $this->profile->id, $this->cookieTIME);
                
                set_cookie('language', (get_cookie('language')) ? get_cookie('language') : $this->config->item('language'), $this->cookieTIME);
                
                redirect('/');
            }
            return $this->lang->line('error_login');
        }
    }

    public function logout()
    {
        // Заводим некоторые переменные для удобства дальннейшего использования
        $baseURL = site_url('authorization/login');

        $userID = get_cookie($this->nameUID);
        
        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $userID . '"]');
        
        foreach ($player as $child)
        {
            $dom = dom_import_simplexml($child);
            $dom->parentNode->removeChild($dom);
        }
        $results->asXML(FCPATH . 'data/players.xml');
        
        // Очищаем основные cookie
        delete_cookie($this->nameSUID);
        delete_cookie($this->nameUID);
        delete_cookie('game');
        delete_cookie('mark');
        delete_cookie('step');
        delete_cookie('players');
        delete_cookie('move');
        
        // Редиректим на авторизацию
        redirect($baseURL);
    }

}
