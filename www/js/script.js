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

var wins_combinations = [
    ['field_1', 'field_2', 'field_3'],
    ['field_4', 'field_5', 'field_6'],
    ['field_7', 'field_8', 'field_9'],
    ['field_1', 'field_4', 'field_7'],
    ['field_2', 'field_5', 'field_8'],
    ['field_3', 'field_6', 'field_9'],
    ['field_1', 'field_5', 'field_3'],
    ['field_3', 'field_5', 'field_7']
];

function letsStep(uid, mark, field_id) {

    $('.errors').html('');

    if (!mark)
        mark = $.cookie('mark');

    var move = $.cookie('move');

    if (parseInt(move) > 0 && ! $('#' + field_id).hasClass('cross') && ! $('#' + field_id).hasClass('circle'))
    {
        $.cookie('move', '0');

        $('#' + field_id).addClass(mark);

        var length = wins_combinations.length;
        for (var i = 0; i < length; i++) {

            var counter = 0;
            var new_length = wins_combinations[i].length;
            for (var y = 0; y < new_length; y++) {

                if ($('#' + wins_combinations[i][y]).hasClass(mark))
                    counter++;
            }
            if (counter === 3)
                break;
        }

        if (counter === 3)
        {
            $.ajax({
                type: 'POST',
                url: 'main/gameOver',
                data: 'uid=' + uid + '&mark=' + mark + '&field_id=' + field_id,
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
            $.ajax({
                type: 'POST',
                url: 'main/letsStep',
                data: 'uid=' + uid + '&mark=' + mark + '&field_id=' + field_id,
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

    }
    else
    {
        $('.errors').html('Не Ваш ход или Вы пытаетесь отметить выбранную клетку.');
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
                        $('.lose').append('<span class="text">Поздравляю, игра окончена. Вы проиграли!</span>');
                        $('.lose').css('display', 'block');
                        break;
                    case 'draw':
                        $('.win').append('<span class="text">Поздравляю, игра окончена. У Вас ничья!</span>');
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
                    }

                    $('#players_list').append('<option value="' + json['players'][key]['id'] + '" class="' + json['players'][key]['status'] + '" data-status="' + json['players'][key]['status'] + '">' + json['players'][key]['player'] + '</option>');
                }
            }

            if (json['table'])
            {
                for (var key in json['table'])
                    $('#' + key).addClass(json['table'][key]);
            }

        }
    });
}
setInterval(get_players, 2000);