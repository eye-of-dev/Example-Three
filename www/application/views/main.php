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
    <body onUnLoad="alert('окно закрывается!')">
        <div id="container">
            <h1><?php echo $title; ?></h1>
            <div id="body">
                <h3><?php echo $game_field; ?></h3>
                <div class="game_div">
                    <ul class="step">
                        <li id="step_0" style="display: none;"><?php echo $step_0; ?></li>
                        <li id="step_1" style="display: none;"><?php echo $step_1; ?></li>
                        <li id="step_2" style="display: none;"><?php echo $step_2; ?></li>
                    </ul>
                    <table id="game_field">
                        <tr>
                            <td class="" data-x="1" data-y="1" id="field_1" data-field="field_1" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_1', '1', '1')"></td>
                            <td class="" data-x="2" data-y="1" id="field_2" data-field="field_2" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_2', '2', '1')"></td>
                            <td class="" data-x="3" data-y="1" id="field_3" data-field="field_3" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_3', '3', '1')"></td>
                        </tr>
                        <tr>
                            <td class="" data-x="1" data-y="2" id="field_4" data-field="field_4" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_4', '1', '2')"></td>
                            <td class="" data-x="2" data-y="2" id="field_5" data-field="field_5" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_5', '2', '2')"></td>
                            <td class="" data-x="3" data-y="2" id="field_6" data-field="field_6" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_6', '3', '2')"></td>
                        </tr>
                        <tr>
                            <td class="" data-x="1" data-y="3" id="field_7" data-field="field_7" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_7', '1', '3')"></td>
                            <td class="" data-x="2" data-y="3" id="field_8" data-field="field_8" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_8', '2', '3')"></td>
                            <td class="" data-x="3" data-y="3" id="field_9" data-field="field_9" onclick="letsStep('<?php print $uid; ?>', '<?php print $mark; ?>', 'field_9', '3', '3')"></td>
                        </tr>
                    </table>
                </div>
                <div class="confirm_game" id="confirm_<?php print $uid; ?>" style="display: none;">
                    <form action="<?php echo site_url('main/confirmGame'); ?>" method="post">
                        <input type="hidden" name="current_plaeyr" value="<?php print $uid; ?>">
                        <input type="hidden" name="rival_plaeyr" value="">
                        <span id="rival_plaeyr"><?php echo $received; ?> <b></b></span> <input type="submit" value="Согласиться на игру">
                    </form>
                </div>
                <div class="win" style="display: none;">
                    <form action="<?php echo site_url('main/letsplay'); ?>" method="post">
                        <input type="hidden" name="players_list" value="<?php print $rival_id; ?>">
                        <input type="submit" value="<?php echo $again; ?>">
                    </form>
                </div>
                <div class="lose" style="display: none;">
                    <form action="<?php echo site_url('main/letsplay'); ?>" method="post">
                        <input type="hidden" name="players_list" value="<?php print $rival_id; ?>">
                        <input type="submit" value="<?php echo $again; ?>">
                    </form>
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
                    <select size="20" name="players_list" id="players_list">
                        <option disabled="disabled"><?php echo $select_player; ?></option>
                    </select>
                    <p>
                        <input type="submit" name="" value="<?php echo $submit_game; ?>" id="letsplay">
                    </p>
                </form>

                <ul id="<?php print $suid; ?>">
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
                        <?php echo $lang; ?>
                        <form action="<?php echo site_url('main/switchlang'); ?>" method="post" id="switch_lang">
                            <select name="language" onchange="$('#switch_lang').submit()">
                                <?php if($language == 'english'): ?>
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
                        <a href="<?php echo site_url('authorization/logout') ?>"><?php echo $logout; ?></a>
                    </li>
                </ul>

            </div>
        </div>
    </body>
</html>