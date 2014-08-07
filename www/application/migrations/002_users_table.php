<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Users_table extends CI_Migration
{

    public function up()
    {
        echo 'Добавляем таблицу для пользователей<br>';

        $this->db->query("CREATE TABLE IF NOT EXISTS `users`(
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`login` varchar(255) NOT NULL,
			`password` varchar(255) DEFAULT NULL,
			`sid` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");
        
        $this->db->insert('users', array(
            'login' => 'Player 1', 
            'password' => md5('1')
            )
        );
        
        $this->db->insert('users', array(
            'login' => 'Player 2', 
            'password' => md5('2')
            )
        );
        
        echo "OK!<br>";
    }

    public function down()
    {
        echo "Удаляем таблицу пользователей<br>";
        $this->db->query('DROP TABLE `users`');
        echo "OK!<br>";
    }

}
