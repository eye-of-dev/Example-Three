<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller
{
    
    private $lenthWinComb = 3; // Длина выигрышной комбинации

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
     * @return void
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

        $date['step_0'] = $this->lang->line('step_0');
        $date['step_1'] = $this->lang->line('step_1');
        $date['step_2'] = $this->lang->line('step_2');

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
    
    /**
     * Ход
     * @return void
     */
    public function letsStep()
    {
        $data = array();

        $uid = $this->input->post('uid');

        // Вносим изменения в основной файл игры
        $this->changeFileGame();

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
     * @return void
     */
    public function confirmGame()
    {
        $game = 'game_' . $this->input->post('current_plaeyr') . 'x' . $this->input->post('rival_plaeyr');

        $fp = fopen(FCPATH . 'data/' . $game, 'w');
        fclose($fp);

        $step = 'step_' . $this->input->post('current_plaeyr') . 'x' . $this->input->post('rival_plaeyr');

        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $this->input->post('current_plaeyr') . '"]');

        $dom = dom_import_simplexml($player[0]);
        foreach ($player[0]->attributes() as $attr => $value)
        {
            $this->setAttribute($dom, $attr);
        }

        $rival_data = $this->user_model->getUserById($this->input->post('rival_plaeyr'));

        $this->setAttribute($dom, 'status', 'busy');
        $this->setAttribute($dom, 'rival_id', $this->input->post('rival_plaeyr'));
        $this->setAttribute($dom, 'rival_name', $rival_data->login);
        $this->setAttribute($dom, 'game', $game);
        $this->setAttribute($dom, 'mark', 'circle');

        set_cookie('mark', 'circle', '2678400');
        set_cookie('game', $game, '2678400');
        set_cookie('step', $step, '2678400');
        set_cookie('move', '0', '2678400');
        set_cookie('rival_id', $this->input->post('rival_plaeyr'), '2678400');

        $player = $results->xpath('//player[@id="' . $this->input->post('rival_plaeyr') . '"]');

        $dom = dom_import_simplexml($player[0]);
        $this->setAttribute($dom, 'game', $game);

        // Вносим изменения в файл игры
        $this->changeFileStep($step, $this->input->post('rival_plaeyr'));

        $results->asXML(FCPATH . 'data/players.xml');

        redirect('/');
    }

    /**
     * Предложить игру
     * @return void
     */
    public function letsPlay()
    {

        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $this->authorization_model->profile->id . '"]');

        $dom = dom_import_simplexml($player[0]);
        foreach ($player[0]->attributes() as $attr => $value)
        {
            $this->setAttribute($dom, $attr);
        }

        $game = 'game_' . $this->input->post('players_list') . 'x' . $this->authorization_model->profile->id;

        $fp = fopen(FCPATH . 'data/' . $game, 'w');
        fclose($fp);

        $step = 'step_' . $this->input->post('players_list') . 'x' . $this->authorization_model->profile->id;

        $fp = fopen(FCPATH . 'data/' . $step, 'w');

        $data[$this->authorization_model->profile->id] = '2';
        $data[$this->input->post('players_list')] = '0';

        fwrite($fp, serialize($data));
        fclose($fp);

        $rival_data = $this->user_model->getUserById($this->input->post('players_list'));

        $this->setAttribute($dom, 'status', 'busy');
        $this->setAttribute($dom, 'rival_id', $this->input->post('players_list'));
        $this->setAttribute($dom, 'rival_name', $rival_data->login);
        $this->setAttribute($dom, 'mark', 'cross');

        $player = $results->xpath('//player[@id="' . $this->input->post('players_list') . '"]');

        if (!$player)
        {
            set_cookie('errors', $this->lang->line('out'), '2678400');
        }

        $results->asXML(FCPATH . 'data/players.xml');

        set_cookie('mark', 'cross', '2678400');
        set_cookie('game', $game, '2678400');
        set_cookie('step', $step, '2678400');
        set_cookie('move', '0', '2678400');
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

                        // Проверка соперника в игре или нет
                        $this->checkRival($data['current']);

                        set_cookie('game', ( string ) $attribute['game'], '2678400');
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

    /**
     * Игра окончена
     * @param int $uid Идентификатор игрока
     * @param string $draw Маркер ничьи
     * @return void
     */
    private function gameOver($uid, $draw = NULL)
    {
        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $uid . '"]');
        $dom = dom_import_simplexml($player[0]);

        $rival_id = ($dom->getAttribute('rival_id')) ? $dom->getAttribute('rival_id') : 0;

        foreach ($player[0]->attributes() as $attr => $value)
        {
            $this->setAttribute($dom, $attr);
        }

        $this->setAttribute($dom, 'status', 'free');
        $this->setAttribute($dom, 'result', ($draw) ? $draw : 'win');

        $player = $results->xpath('//player[@id="' . $rival_id . '"]');
        $dom = dom_import_simplexml($player[0]);

        foreach ($player[0]->attributes() as $attr => $value)
        {
            $this->setAttribute($dom, $attr);
        }

        $this->setAttribute($dom, 'status', 'free');
        $this->setAttribute($dom, 'result', ($draw) ? $draw : 'lose');

        $results->asXML(FCPATH . 'data/players.xml');

        delete_cookie('game');
        delete_cookie('mark');
        delete_cookie('step');
        delete_cookie('move');
    }

    /**
     * Смена языка
     * @return void
     */
    public function switchLang()
    {
        set_cookie('language', $this->input->post('language'), '2678400');
        redirect('/');
    }

    /**
     * Удаляем атрибут
     * @param object $dom Объект XML
     * @param string $attr Атрибут
     * @param string $val Значение
     * @return void
     */
    private function setAttribute($dom, $attr, $val = '')
    {

        if ($attr != 'id' && $attr != 'suid')
        {
            $dom->setAttribute($attr, $val);
        }
    }

    /**
     * Вносим изменения в основной файл игры
     * @return void
     */
    private function changeFileGame()
    {

        $mark = $this->input->post('mark');
        $field_id = $this->input->post('field_id');
        $x = $this->input->post('x');
        $y = $this->input->post('y');

        $game = get_cookie('game');
        $data = file_get_contents(FCPATH . 'data/' . $game);
        $data = unserialize($data);

        $data[$y][$x] = $mark;

        file_put_contents(FCPATH . 'data/' . $game, serialize($data));

        $this->checkGameResult($data);
    }

    /**
     * Вносим изменения в файл ходов
     * @param string $file Файл ходов
     * @param int $uid Идентификатор игрока
     * @return void
     */
    private function changeFileStep($file, $uid = NULL)
    {
        $data = file_get_contents(FCPATH . 'data/' . $file);
        $data = unserialize($data);
        foreach ($data as $key => $value)
        {
            if ($uid == $key)
            {
                $data[$key] = '1';
            }
            else
            {
                $data[$key] = '0';
            }
        }

        file_put_contents(FCPATH . 'data/' . $file, serialize($data));
    }

    /**
     * Функция вычисления выигрыша
     * @param array $data Массив крестики-нолики
     */
    private function checkGameResult($data)
    {
        $uid = $this->input->post('uid');
        $mark = $this->input->post('mark');

        $cross = 0;
        $circle = 0;
        // Проверка выигрыша по горизонтали
        foreach ($data as $key => $value)
        {
            extract(array_count_values($value)); 
            
            if ($cross === $this->lenthWinComb || $circle === $this->lenthWinComb)
            {
                $this->gameOver($uid);
                continue;
            }
        }

        $cross = 0;
        $circle = 0;
        // Проверка выигрышной комбинации по горизонтали слева направо
        foreach ($data as $y => $yvalue)
        {
            if (isset($data[$y][$y]) && $data[$y][$y] === 'cross')
            {
                $cross++;
            }
            elseif (isset($data[$y][$y]) && $data[$y][$y] === 'circle')
            {
                $circle++;
            }
        }

        if ($cross === $this->lenthWinComb || $circle === $this->lenthWinComb)
        {
            $this->gameOver($uid);
        }
        
        // Проверка выигрышной комбинации по вуртикали
        foreach ($data as $y => $yvalue)
        {
            foreach ($yvalue as $x => $xvalue)
            {

                $cross = 0;
                $circle = 0;
                if (isset($data[$y][$x]) && $data[$y][$x] === 'cross')
                {
                    $cross++;
                }
                elseif (isset($data[$y][$x]) && $data[$y][$x] === 'circle')
                {
                    $circle++;
                }
                if (isset($data[$y + 1][$x]) && $data[$y + 1][$x] === 'cross')
                {
                    $cross++;
                }
                elseif (isset($data[$y + 1][$x]) && $data[$y + 1][$x] === 'circle')
                {
                    $circle++;
                }
                if (isset($data[$y + 2][$x]) && $data[$y + 2][$x] === 'cross')
                {
                    $cross++;
                }
                elseif (isset($data[$y + 2][$x]) && $data[$y + 2][$x] === 'circle')
                {
                    $circle++;
                }
            }
            
            if ($cross === $this->lenthWinComb || $circle === $this->lenthWinComb)
            {
                $this->gameOver($uid);
                continue;
            }
            
        }
        // Проверка выигрышной комбинации по горизонтали справа налево
        // т.к. достаточно только 3 совпадения, то и ищем минимум 3 совпадения
        foreach ($data as $y => $yvalue)
        {
            foreach ($yvalue as $x => $xvalue)
            {
                $cross = 0;
                $circle = 0;
                if (isset($data[$y][$x]) && $data[$y][$x] === 'cross')
                {
                    $cross++;
                }
                elseif (isset($data[$y][$x]) && $data[$y][$x] === 'circle')
                {
                    $circle++;
                }
                if (isset($data[$y + 1][$x - 1]) && $data[$y + 1][$x - 1] === 'cross')
                {
                    $cross++;
                }
                elseif (isset($data[$y + 1][$x - 1]) && $data[$y + 1][$x - 1] === 'circle')
                {
                    $circle++;
                }
                if (isset($data[$y + 2][$x - 2]) && $data[$y + 2][$x - 2] === 'cross')
                {
                    $cross++;
                }
                elseif (isset($data[$y + 2][$x - 2]) && $data[$y + 2][$x - 2] === 'circle')
                {
                    $circle++;
                }
            }

            if ($cross > 2 || $circle > 2)
            {
                $this->gameOver($uid);
                continue;
            }
        }

        // Проверка на ничью
        $counter = 0;
        foreach ($data as $y => $yvalue)
        {
            foreach ($yvalue as $x => $xvalue)
            {
                $counter++;
            }
        }

        if ($counter === 9)
        {
            $this->gameOver($uid, 'draw');
        }
    }

    /**
     * Проверка соперника в игре или нет
     * @param array $data Массив данных игрока
     */
    private function checkRival($data)
    {

        if ($data['status'] === 'busy' && $data['game'])
        {
            $results = simplexml_load_file(base_url('data/players.xml'));

            $player = $results->xpath('//player[@id="' . $data['rival_id'] . '"]');

            if (!$player)
            {
                $player = $results->xpath('//player[@id="' . $data['id'] . '"]');

                $dom = dom_import_simplexml($player[0]);
                foreach ($player[0]->attributes() as $attr => $value)
                {
                    $this->setAttribute($dom, $attr);
                }

                $this->setAttribute($dom, 'status', 'free');

                $results->asXML(FCPATH . 'data/players.xml');

                set_cookie('errors', $this->lang->line('out'), '2678400');
            }
        }
    }

    public function deleteplayer()
    {
        
        
        $results = simplexml_load_file(base_url('data/players.xml'));
        $player = $results->xpath('//player[@id="' . $this->input->post('uid') . '"]');

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
    }

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */