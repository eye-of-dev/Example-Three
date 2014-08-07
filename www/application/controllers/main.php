<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');

        $this->load->model('authorization_model');
        $this->authorization_model->check();
        
        $this->lang->load('main', get_cookie('language'));
    }

    /**
     * Точка входа в контролер
     */
    public function index()
    {

        $date = array();
        // Lang
        $date['title'] = $this->lang->line('title');
        $date['game_field'] = $this->lang->line('game_field');
        $date['players_list'] = $this->lang->line('players_list');
        $date['select_player'] = $this->lang->line('select_player');
        $date['submit_game'] = $this->lang->line('submit_game');
        $date['signed'] = $this->lang->line('signed');
        $date['enemy'] = $this->lang->line('enemy');
        $date['game'] = $this->lang->line('game');
        $date['logout'] = $this->lang->line('logout');
        $date['again'] = $this->lang->line('again');
        $date['received'] = $this->lang->line('received');
        $date['agree'] = $this->lang->line('agree');
        $date['lang'] = $this->lang->line('lang');
        
        $date['login'] = $this->authorization_model->profile->login;
        $date['suid'] = get_cookie('GAME_SUID');
        $date['uid'] = get_cookie('GAME_UID');
        $date['mark'] = get_cookie('mark');
        $date['errors'] = get_cookie('errors');
        $date['rival_id'] = get_cookie('rival_id');
        $date['language'] = get_cookie('language');
        
        delete_cookie('errors');
        delete_cookie('rival_id');
        
        $this->load->view('main', $date);
    }

    public function letsStep()
    {
        $data = array();

        $uid = $this->input->post('uid');
        $mark = $this->input->post('mark');
        $field_id = $this->input->post('field_id');

        // Вносим изменения в основной файл игры
        $game = get_cookie('game');
        $data = file_get_contents(FCPATH . 'data/' . $game);
        $data = unserialize($data);
        $data[$field_id] = $mark;
        file_put_contents(FCPATH . 'data/' . $game, serialize($data));

        // Вносим изменения в файл ходов
        $step = get_cookie('step');
        $data = file_get_contents(FCPATH . 'data/' . $step);
        $data = unserialize($data);
        foreach ($data as $key => $value)
        {
            $data[$key] = '1';
        }
        $data[$uid] = '0';
        file_put_contents(FCPATH . 'data/' . $step, serialize($data));
    }

    /**
     * Отправка согласия на игру
     */
    public function confirmGame()
    {
        $game = 'game_' . $this->input->post('current_plaeyr') . 'x' . $this->input->post('rival_plaeyr');

        $fp = fopen(FCPATH . 'data/' . $game, 'w');
        fclose($fp);

        $step = 'step_' . $this->input->post('current_plaeyr') . 'x' . $this->input->post('rival_plaeyr');

        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $this->input->post('current_plaeyr') . '"]');

        foreach ($player as $value)
        {
            $dom = dom_import_simplexml($value);
            $dom->removeAttribute('status');
            $dom->removeAttribute('rival_id');
            $dom->removeAttribute('rival_name');
            $dom->removeAttribute('game');
            $dom->removeAttribute('mark');
            $dom->removeAttribute('result');
        }

        $rival_data = $this->user_model->getUserById($this->input->post('rival_plaeyr'));

        $player['0']->addAttribute('status', 'busy');
        $player['0']->addAttribute('rival_id', $this->input->post('rival_plaeyr'));
        $player['0']->addAttribute('rival_name', $rival_data->login);
        $player['0']->addAttribute('game', $game);
        $player['0']->addAttribute('mark', 'circle');
        $player['0']->addAttribute('result', '');

        set_cookie('mark', 'circle', '2678400');
        set_cookie('game', $game, '2678400');
        set_cookie('step', $step, '2678400');
        set_cookie('move', '0', '2678400');
        set_cookie('rival_id', $this->input->post('rival_plaeyr'), '2678400');
        
        $player = $results->xpath('//player[@id="' . $this->input->post('rival_plaeyr') . '"]');

        foreach ($player as $value)
        {
            $dom = dom_import_simplexml($value);
            $dom->removeAttribute('game');
        }

        $player['0']->addAttribute('game', $game);

        $results->asXML(FCPATH . 'data/players.xml');

        redirect('/');
    }

    /**
     * Предложить игру
     */
    public function letsPlay()
    {

        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $this->authorization_model->profile->id . '"]');

        foreach ($player as $value)
        {
            $dom = dom_import_simplexml($value);
            $dom->removeAttribute('status');
            $dom->removeAttribute('rival_id');
            $dom->removeAttribute('rival_name');
            $dom->removeAttribute('mark');
            $dom->removeAttribute('result');
        }

        $game = 'game_' . $this->input->post('players_list') . 'x' . $this->authorization_model->profile->id;

        $fp = fopen(FCPATH . 'data/' . $game, 'w');
        fclose($fp);

        $step = 'step_' . $this->input->post('players_list') . 'x' . $this->authorization_model->profile->id;

        $fp = fopen(FCPATH . 'data/' . $step, 'w');

        $data[$this->authorization_model->profile->id] = '1';
        $data[$this->input->post('players_list')] = '0';

        fwrite($fp, serialize($data));
        fclose($fp);

        $rival_data = $this->user_model->getUserById($this->input->post('players_list'));

        $player['0']->addAttribute('status', 'busy');
        $player['0']->addAttribute('rival_id', $this->input->post('players_list'));
        $player['0']->addAttribute('rival_name', $rival_data->login);
        $player['0']->addAttribute('mark', 'cross');
        $player['0']->addAttribute('result', '');

        $player = $results->xpath('//player[@id="' . $this->input->post('players_list') . '"]');
        
        if( ! $player)
        {
            set_cookie('errors', $this->lang->line('out'), '2678400');
        }
        
        $results->asXML(FCPATH . 'data/players.xml');

        set_cookie('mark', 'cross', '2678400');
        set_cookie('game', $game, '2678400');
        set_cookie('step', $step, '2678400');
        set_cookie('move', '1', '2678400');
        set_cookie('rival_id', $this->input->post('players_list'), '2678400');

        redirect('/');
    }

    /**
     * Получаем всех подключенных игроков
     * Получаем игровое поле
     * Получаем очередность хода
     */
    public function getGame()
    {
        $data = array();

        $results = simplexml_load_file(base_url('data/players.xml'));

        if ($results && md5(file_get_contents(FCPATH . 'data/players.xml')) != get_cookie('players'))
        {
            foreach ($results->xpath('//player') as $player)
            {

                $attributes = ( array ) $player->attributes();

                foreach ($attributes as $key => $attribute)
                {
                    if ($this->authorization_model->profile->id != $attribute['id'])
                    {
                        $data['players'][$attribute['id']]['id'] = ( string ) $attribute['id'];
                        $data['players'][$attribute['id']]['status'] = ( string ) $attribute['status'];
                        $data['players'][$attribute['id']]['player'] = ( string ) $player;
                        $data['players'][$attribute['id']]['rival_id'] = ( string ) $attribute['rival_id'];
                        $data['players'][$attribute['id']]['rival_name'] = ( string ) $attribute['rival_name'];
                        $data['players'][$attribute['id']]['game'] = ( string ) $attribute['game'];
                        $data['players'][$attribute['id']]['suid'] = ( string ) $attribute['suid'];
                        $data['players'][$attribute['id']]['result'] = ( string ) $attribute['result'];
                    }
                    else
                    {
                        $data['current']['id'] = ( string ) $attribute['id'];
                        $data['current']['status'] = ( string ) $attribute['status'];
                        $data['current']['player'] = ( string ) $player;
                        $data['current']['rival_id'] = ( string ) $attribute['rival_id'];
                        $data['current']['rival_name'] = ( string ) $attribute['rival_name'];
                        $data['current']['game'] = ( string ) $attribute['game'];
                        $data['current']['suid'] = ( string ) $attribute['suid'];
                        $data['current']['result'] = ( string ) $attribute['result'];
                    }
                }
            }

            set_cookie('players', md5(file_get_contents(FCPATH . 'data/players.xml')), '10');
        }

        $game = get_cookie('game');

        if ($game && file_exists(FCPATH . 'data/' . $game))
        {
            $content = file_get_contents(FCPATH . 'data/' . $game);
            $data['table'] = unserialize($content);
        }

        $step = get_cookie('step');
        if ($step && file_exists(FCPATH . 'data/' . $step))
        {
            $step = file_get_contents(FCPATH . 'data/' . $step);
            $step = unserialize($step);
            set_cookie('move', $step[$this->authorization_model->profile->id], '2678400');
        }

        $this->output->set_output(json_encode($data));
    }

    public function gameOver()
    {
        $rival_id = 0;

        $uid = $this->input->post('uid');

        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $this->authorization_model->profile->id . '"]');

        foreach ($player as $value)
        {
            $dom = dom_import_simplexml($value);
            $rival_id = $dom->getAttribute('rival_id');
            
            $dom->removeAttribute('result');
            $dom->removeAttribute('status');
            $dom->removeAttribute('rival_name');
            $dom->removeAttribute('mark');
            $dom->removeAttribute('game');
            $dom->removeAttribute('rival_id');
        }
        
        $player['0']->addAttribute('rival_id', '');
        $player['0']->addAttribute('rival_name', '');
        $player['0']->addAttribute('mark', '');
        $player['0']->addAttribute('game', '');
        $player['0']->addAttribute('status', 'free');
        $player['0']->addAttribute('result', 'win');

        $player = $results->xpath('//player[@id="' . $rival_id . '"]');
        foreach ($player as $value)
        {
            $dom = dom_import_simplexml($value);
            $dom->removeAttribute('result');
            $dom->removeAttribute('status');
            $dom->removeAttribute('rival_name');
            $dom->removeAttribute('mark');
            $dom->removeAttribute('game');
            $dom->removeAttribute('rival_id');
        }
        
        $player['0']->addAttribute('rival_id', '');
        $player['0']->addAttribute('rival_name', '');
        $player['0']->addAttribute('mark', '');
        $player['0']->addAttribute('game', '');
        $player['0']->addAttribute('status', 'free');
        $player['0']->addAttribute('result', 'lose');

        $results->asXML(FCPATH . 'data/players.xml');

        $mark = $this->input->post('mark');
        $field_id = $this->input->post('field_id');

        // Вносим изменения в основной файл игры
        $game = get_cookie('game');
        $data = file_get_contents(FCPATH . 'data/' . $game);
        $data = unserialize($data);
        $data[$field_id] = $mark;
        file_put_contents(FCPATH . 'data/' . $game, serialize($data));
        
        // Вносим изменения в файл ходов
        $step = get_cookie('step');
        $data = file_get_contents(FCPATH . 'data/' . $step);
        $data = unserialize($data);
        foreach ($data as $key => $value)
        {
            $data[$key] = '0';
        }
        file_put_contents(FCPATH . 'data/' . $step, serialize($data));
        
        delete_cookie('game');
        delete_cookie('mark');
        delete_cookie('step');
        delete_cookie('move');

    }

    public function switchLang()
    {
        set_cookie('language', $this->input->post('language'), '2678400');
        redirect('/');
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */