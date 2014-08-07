<!DOCTYPE html>
<html>
    <head>
        <base href="<?php echo base_url() ?>">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Регистрация</title>
        <link href="<?php echo base_url('css/cssf-base.css') ?>" rel="stylesheet">
    </head>
    <body>
        <div class="text-gray wp-background corners corners-8" style="width:500px; margin:0 auto; padding-top:30px;">
            <div class="page-layout">
                <div class="container">
                    <div class="layout-box w-100">
                        <h4 class="a-center">Зарегистрироваться в игре «Крестики-нолики»!</h4>
                        <div class="box content w-55" style="margin-left:125px;">
                            <form action="<?php echo site_url('user/registration'); ?>" method="post" accept-charset="utf-8" role="form">
                                <div class="f-row">
                                    <label>Логин:</label>
                                    <div class="f-inputs">
                                        <?php echo form_input(array('name' => 'login', 'class' => 'i-login', 'value' => $login)); ?>
                                    </div>
                                </div>
                                <div class="f-row">
                                    <label>Пароль:</label>
                                    <div class="f-inputs">
                                        <?php echo form_password(array('name' => 'password', 'class' => 'i-login', 'value' => '')); ?>
                                    </div>
                                </div>
                                <div class="f-row">
                                    <label>Пароль:</label>
                                    <div class="f-inputs">
                                        <?php echo form_password(array('name' => 'password_confirm', 'class' => 'i-login', 'value' => '')); ?>
                                    </div>
                                </div>
                                <div class="f-row">
                                    <div class="registr">
                                        <?php echo form_submit('', 'Зарегистрироваться', 'type="submit" class="button"'); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <em class="bl"></em>
                <em class="br"></em>
            </div>
            <div class="errors">
                <?php if($errors): ?>
                    <?php print $errors; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>