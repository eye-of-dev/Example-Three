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
                            <input type="hidden" name="current_plaeyr" value="<?php print $uid; ?>">
                            <input type="hidden" name="rival_plaeyr" value="">
                            <span id="rival_plaeyr"><?php echo $received; ?> <b></b></span> 
                            <br/><input type="submit" value="Согласиться на игру">
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
                            <?php echo $status; ?>
                            <span id="step_0" style="display: none;"><?php echo $step_0; ?></span>
                            <span id="step_1" style="display: none;"><?php echo $step_1; ?></span>
                            <span id="step_2" style="display: none;"><?php echo $step_2; ?></span>
                        </li>
                        <li>
                            <a href="<?php echo site_url('authorization/logout') ?>"><?php echo $logout; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>