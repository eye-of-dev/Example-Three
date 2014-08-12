$(document).ready(function() {

    get_players();

    $('.win form, .lose form').submit(function() {
        $('.errors').html('');

        var status = $('#players_list option:selected').data('status');

        if (status === 'busy')
        {
            $('.errors').html('Выбранный Вами игрок - занят');
            return false;
        }
        
        var game = $.cookie('game');
        if (game)
        {
            $('.errors').html('Вы уже в игре');
            return false;
        }
    });

    $('#sidebar #letsplay_form').submit(function() {

        $('.errors').html('');

        var status = $('#players_list option:selected').data('status');

        if (status === 'busy')
        {
            $('.errors').html('Выбранный Вами игрок - занят');
            return false;
        }

        if (!$('#players_list').val())
        {
            $('.errors').html('Не выбран игрок');
            return false;
        }
        
        var game = $.cookie('game');
        if (game)
        {
            $('.errors').html('Вы уже в игре');
            return false;
        }

    });

});

function letsStep(uid, mark, field_id, x, y) {

    $('.errors').html('');

    if (!mark)
        mark = $.cookie('mark');

    var move = $.cookie('move');

    if($('#' + field_id).hasClass('cross') || $('#' + field_id).hasClass('circle')){
        $('.errors').html('Вы пытаетесь отметить выбранную клетку.');
        return false;
    }

    if (parseInt(move) === 1)
    {
        $.cookie('move', '0');

        $('#' + field_id).addClass(mark);

        $.ajax({
            type: 'POST',
            url: 'main/letsStep',
            data: 'uid=' + uid + '&mark=' + mark + '&field_id=' + field_id + '&x=' + x + '&y=' + y,
            dataType: 'json',
            async: false,
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
            }
        });

    }
    else
    {
        $('.errors').html('Ждите хода соперника.');
    }

}

// Получаем всех подключенных игроков
function get_players() {
    $.ajax({
        type: 'POST',
        url: 'main/getgame',
        dataType: 'json',
        async: false,
        beforeSend: function() {

        },
        complete: function() {

        },
        success: function(json) {

            var move = $.cookie('move');
            $('li[id^=step_]').css('display', 'none');
            $('#step_' + move).css('display', 'block');

            if (json['current'])
            {
                $('#' + json['current']['suid'] + ' #rival b').html(json['current']['rival_name']);
                $('#' + json['current']['suid'] + ' #game b').html(json['current']['game']);

                $('.win .text').html('');
                $('.lose .text').html('');
                switch (json['current']['result'])
                {
                    case 'win':
                        $('.win').append('<span class="text">Поздравляю, игра окончена. Вы победили!</span>');
                        $('.win').css('display', 'block');
                        break;
                    case 'lose':
                        $('.lose').append('<span class="text">Игра окончена. Вы проиграли!</span>');
                        $('.lose').css('display', 'block');
                        break;
                    case 'draw':
                        $('.win').append('<span class="text">Игра окончена. У Вас ничья!</span>');
                        $('.win').css('display', 'block');
                        break;
                }

            }

            if (json['players'])
            {

                $('#players_list').html('');
                $('#players_list').append('<option disabled="disabled">Выберите игрока</option>');

                for (var key in json['players'])
                {
                    if (json['players'][key]['rival_id'] && ! json['players'][key]['game'])
                    {
                        $('#rival_plaeyr b').html(json['players'][key]['player']);
                        $('#confirm_' + json['players'][key]['rival_id'] + ' input[name=rival_plaeyr]').val(json['players'][key]['id']);
                        $('#confirm_' + json['players'][key]['rival_id']).css('display', 'block');
                        
                        $('.win').css('display', 'none');
                        $('.lose').css('display', 'none');
                    }

                    $('#players_list').append('<option value="' + json['players'][key]['id'] + '" class="' + json['players'][key]['status'] + '" data-status="' + json['players'][key]['status'] + '" data-game="' + json['players'][key]['game'] + '">' + json['players'][key]['player'] + '</option>');
                }
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
                $('.errors').html(errors);
                $.removeCookie('errors', { path: '/' });
                $.removeCookie('move', { path: '/' });
            }
                

        }
    });
}
setInterval(get_players, 2000);