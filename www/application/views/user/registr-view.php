<!DOCTYPE html>
<html>
    <head>
        <base href="<?php echo base_url() ?>">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <link href="<?php echo base_url('css/cssf-base.css') ?>" rel="stylesheet">
    </head>
    <body>
        <div class="text-gray wp-background corners corners-8" style="width:500px; margin:0 auto; padding-top:30px;">
            <div class="page-layout">
                <div class="container">
                    <div class="layout-box w-100">
                        <h4 class="a-center"><?php echo $welcome; ?></h4>
                        <div class="box content w-70" style="margin-left:100px;">
                            <form action="<?php echo site_url('user/registration'); ?>" method="post" accept-charset="utf-8" role="form">
                                <fieldset>
                                    <div class="f-row">
                                        <label><?php echo $tlogin; ?></label>
                                        <div class="f-inputs">
                                            <?php echo form_input(array('name' => 'login', 'class' => 'i-login', 'value' => $login)); ?>
                                        </div>
                                    </div>
                                    <div class="f-row">
                                        <label><?php echo $password; ?></label>
                                        <div class="f-inputs">
                                            <?php echo form_password(array('name' => 'password', 'class' => 'i-login', 'value' => '')); ?>
                                        </div>
                                    </div>
                                    <div class="f-row">
                                        <label>Повторите пароль:</label>
                                        <div class="f-inputs">
                                            <?php echo form_password(array('name' => 'password_confirm', 'class' => 'i-login', 'value' => '')); ?>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="f-row f-actions">
                                    <div style="text-align: center;">
                                        <input type="submit" value="<?php echo $reg; ?>" name="" class="button"> | <a href="<?php print site_url('authorization/login'); ?>"><?php echo $auth; ?></a>
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
                    <?php echo $errors; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>