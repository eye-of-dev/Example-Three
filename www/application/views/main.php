<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="<?php echo base_url('js/jquery.cookie.js') ?>"></script>
        <script src="<?php echo base_url('js/script.js') ?>"></script>
        <link href="<?php echo base_url('css/main.css') ?>" rel="stylesheet">
    </head>
    <body>
        <div id="container">
            <h1><?php echo $title; ?></h1>
            <div class="game_field">
                <div id="body">
                    <h3><?php echo $game_field; ?></h3>
                    <div class="game_div">
                        <?php $field_id = 0; ?>
                        <table id="game_field">
                        <?php for ($y = 0; $y < $width; $y++): ?>
                            <tr>
                            <?php for ($x = 0; $x < $height; $x++): ?>
                                <td class="" data-x="<?php echo $x; ?>" data-y="<?php echo $y; ?>" id="field_<?php echo $field_id; ?>" data-field="field_<?php echo $field_id; ?>" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_<?php echo $field_id++; ?>', '<?php echo $x; ?>', '<?php echo $y; ?>')"></td>
                            <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                        </table>
                    </div>
                    <div class="confirm_game" id="confirm_<?php print $uid; ?>" style="display: none;">
                        <form action="<?php echo site_url('main/confirmGame'); ?>" method="post">
                            <input type="hidden" name="current_player" value="<?php print $uid; ?>">
                            <input type="hidden" name="rival_player" value="">
                            <span id="rival_player"><?php echo $received; ?> <b></b></span> 
                            <br/><input type="submit" value="<?php echo $agree; ?>" class="button success-game">
                        </form>
                        <form action="<?php echo site_url('main/canselgame'); ?>" method="post">
                            <input type="hidden" name="current_player" value="<?php print $uid; ?>">
                            <input type="hidden" name="rival_player" value="">
                            <input type="submit" value="<?php echo $cansel; ?>" class="button cansel-game">
                        </form>
                    </div>
                    <div class="win" style="display: none;">
                        <form action="<?php echo site_url('main/letsplay'); ?>" method="post">
                            <input type="hidden" name="players_list" value="<?php print $rival_id; ?>">
                            <input type="submit" value="<?php echo $again; ?>" class="button success-game">
                        </form>
                    </div>
                    <div class="lose" style="display: none;">
                        <form action="<?php echo site_url('main/letsplay'); ?>" method="post">
                            <input type="hidden" name="players_list" value="<?php print $rival_id; ?>">
                            <input type="submit" value="<?php echo $again; ?>" class="button success-game">
                        </form>
                    </div>
                    <div class="recall" style="display: none;">
                        <form action="<?php echo site_url('main/canselgame'); ?>" method="post">
                            <input type="hidden" name="current_player" value="<?php print $uid; ?>">
                            <input type="hidden" name="rival_player" value="">
                            <br/><input type="submit" value="<?php echo $recall; ?>" class="button cansel-game">
                        </form>
                    </div>
                    <div class="notification">
                        <span id="step_0" style="display: none;"><?php echo $status; ?> <?php echo $step_0; ?></span>
                        <span id="step_1" style="display: none;"><?php echo $status; ?> <?php echo $step_1; ?></span>
                        <span id="step_2" style="display: none;"><?php echo $status; ?> <?php echo $step_2; ?></span>
                    </div>
                    <div class="errors">
                        <?php if($errors): ?>
                            <?php print $errors; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="sidebar">
                    <h3><?php echo $players_list; ?></h3>
                    <form action="<?php echo site_url('main/letsplay'); ?>" method="post" id="letsplay_form">
                        <select size="19" name="players_list" id="players_list">
                            <option disabled="disabled" value="-1"><?php echo $select_player; ?></option>
                        </select>
                        <p>
                            <input type="submit" name="" value="<?php echo $submit_game; ?>" id="letsplay">
                        </p>
                    </form>
                </div>
                                    <div class="information">
                        <ul id="<?php print $suid; ?>">
                            <li>
                                <?php echo $lang; ?>
                                <form action="<?php echo site_url('main/switchlang'); ?>" method="post" id="switch_lang">
                                    <select name="language" onchange="$('#switch_lang').submit()">
                                        <?php if ($language == 'english'): ?>
                                            <option value="english" selected="selected">English</option>
                                            <option value="russian">Russian</option>
                                        <?php else: ?>
                                            <option value="english">English</option>
                                            <option value="russian" selected="selected">Russian</option>
                                        <?php endif; ?>
                                    </select>
                                </form>
                            </li>
                            <li>
                                <?php echo $signed; ?> <b><?php print $login; ?></b>
                            </li>
                            <li id="rival">
                                <?php echo $enemy; ?> <b></b>
                            </li>
                            <li id="game">
                                <?php echo $game; ?> <b></b>
                            </li>
                            <li>
                                <?php echo $comb; ?> <b><?php echo $lenthWinComb; ?></b>
                            </li>
                            <li>
                                <a href="<?php echo site_url('authorization/logout') ?>"><?php echo $logout; ?></a>
                            </li>
                        </ul>
                    </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.win form, .lose form').submit(function() {
                $('.errors').html('');

                var status = $('#players_list option:selected').data('status');

                if (status === 'busy')
                {
                    $('.errors').html('<?php echo $busy; ?>');
                    return false;
                }

                var game = $.cookie('game');
                if (game)
                {
                    $('.errors').html('<?php echo $in_game; ?>');
                    return false;
                }
            });
            
            $('#sidebar #letsplay_form').submit(function() {

                $('.errors').html('');

                var status = $('#players_list option:selected').data('status');

                if (status === 'busy')
                {
                    $('.errors').html('<?php echo $busy; ?>');
                    return false;
                }

                if (!$('#players_list').val())
                {
                    $('.errors').html('<?php echo $not_selected; ?>');
                    return false;
                }

                var game = $.cookie('game');
                if (game)
                {
                    $('.errors').html('<?php echo $in_game; ?>');
                    return false;
                }

            });
            
        });

        
        // Проверка на существование элемента в массиве
        function in_array(value, array) 
        {
            for(var i = 0; i < array.length; i++) 
            {
                if(array[i] === value) return true;
            }
            return false;
        }
        
        function letsStep(uid, mark, field_id, x, y) {

            $('.errors').html('');

            if (!mark)
                mark = $.cookie('mark');

            var move = $.cookie('move');

            if($('#' + field_id).hasClass('cross') || $('#' + field_id).hasClass('circle')){
                $('.errors').html('<?php echo $selected_cell; ?>');
                return false;
            }

            if (parseInt(move) === 1)
            {
                $.cookie('move', '0');

                $('#' + field_id).addClass(mark);

                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url('main/letsstep'); ?>',
                    data: 'uid=' + uid + '&mark=' + mark + '&field_id=' + field_id + '&x=' + x + '&y=' + y,
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                    }
                });

            }
            else
            {
                $('.errors').html('<?php echo $wait ;?>');
            }

        }
        
        // Получаем всех подключенных игроков
        function get_players() {
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('main/getgame'); ?>',
                dataType: 'json',
                async: false,
                success: function(json) {

                    var errors = $.trim($('.errors').html());
                    if (errors){
                        
                        var error_timer = $.cookie('error_timer');
                        if ( ! error_timer){
                            error_timer = 0;
                        }
                        
                        $.cookie('error_timer', ++error_timer);
                        
                        if(error_timer > 2){
                            $('.errors').html('');
                            $.removeCookie('error_timer', { path: '/' });
                        }

                    }

                    if($.cookie('game')){
                        var move = $.cookie('move');
                        $('.recall').css('display', 'none');
                        $('.notification span[id^=step_]').css('display', 'none');
                        $('#step_' + move).css('display', 'inline-block');
                    }

                    if (json['current'])
                    {
                        $('#' + json['current']['suid'] + ' #rival b').html(json['current']['rival_name']);
                        $('#' + json['current']['suid'] + ' #game b').html(json['current']['game']);

                        $('.win .text').html('');
                        $('.lose .text').html('');
                        switch (json['current']['result'])
                        {
                            case 'win':
                                $('.notification span[id^=step_]').css('display', 'none');
                                $('.win').append('<span class="text"><?php echo $player_win; ?></span>');
                                $('.win').css('display', 'block');
                                break;
                            case 'lose':
                                $('.notification span[id^=step_]').css('display', 'none');
                                $('.lose').append('<span class="text"><?php echo $player_lose; ?></span>');
                                $('.lose').css('display', 'block');
                                break;
                            case 'draw':
                                $('.notification span[id^=step_]').css('display', 'none');
                                $('.win').append('<span class="text"><?php echo $player_draw; ?></span>');
                                $('.win').css('display', 'block');
                                break;
                        }

                        if(json['current']['comment']){
                            $('.confirm_game').css('display', 'none');
                            $('.recall').css('display', 'none');
                            $('.win').css('display', 'none');
                            $('.lose').css('display', 'none');
                            $('.errors').html(json['current']['comment']);
                        }

                        var recall = $.cookie('recall');
                        if(recall){
                            $('.recall').css('display', 'block');
                            $('.recall input[name=rival_player]').val(json['current']['rival_id']);
                            $.removeCookie('recall', { path: '/' });
                        }

                    }

                    if (json['players'])
                    {
                        var exist = new Array('-1');
                        
                        for (var key in json['players']){
                            if( ! $('#players_list option[value="' + key + '"]').text()){
                                $('#players_list').append('<option value="' + json['players'][key]['id'] + '" class="' + json['players'][key]['status'] + '" data-status="' + json['players'][key]['status'] + '" data-game="' + json['players'][key]['game'] + '">' + json['players'][key]['player'] + '</option>');
                            }
                            
                            if (json['players'][key]['rival_id'] && ! json['players'][key]['game'])
                            {
                                $('#rival_player b').html(json['players'][key]['player']);
                                $('#confirm_' + json['players'][key]['rival_id'] + ' input[name=rival_player]').val(json['players'][key]['id']);
                                $('#confirm_' + json['players'][key]['rival_id']).css('display', 'block');

                                $('.win').css('display', 'none');
                                $('.lose').css('display', 'none');
                                $('.errors').css('display', 'none');
                            }
                            
                            exist.push(json['players'][key]['id']);
                        }
                        
                        $('#players_list option').each(function (){
                            if( ! in_array($(this).val(), exist)){
                                $('#players_list option[value="' + $(this).val() + '"]').remove();
                            };
                        });
                        
                    }else{
                        $('#players_list').html('');
                        $('#players_list').append('<option disabled="disabled" value="-1"><?php echo $select_player; ?></option>');
                    }

                    if (json['table'])
                    {
                        $('#game_field td').each(function (){
                            var x = $(this).data('x');
                            var y = $(this).data('y');

                            if(json['table'][y] && json['table'][y][x])
                                $(this).addClass(json['table'][y][x]);

                        });
                    }

                    var errors = $.cookie('errors');
                    if (errors){
                        
                        $('.win').css('display', 'none');
                        $('.lose').css('display', 'none');
                        $('.recall').css('display', 'none');
                        
                        $('.errors').html(errors);
                        
                        $.removeCookie('errors', { path: '/' });
                        $.removeCookie('move', { path: '/' });
                    }

                }
            });
        }
        setInterval(get_players, 2000);
    </script>
</html>