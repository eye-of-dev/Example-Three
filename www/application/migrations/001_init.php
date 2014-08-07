<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration {

    public function up() {
        echo 'Инициализация миграций...OK<br>';
    }

    public function down() {
        // Add comment column in to orders table
        echo 'Деинициализация миграций...OK<br>';
    }

}