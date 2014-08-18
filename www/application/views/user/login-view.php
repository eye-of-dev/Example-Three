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
                        <div class="box content w-55" style="margin-left:125px;">
                            <form action="<?php echo site_url('authorization/login'); ?>" method="post" accept-charset="utf-8" role="form" onsubmit="return validate_login_form();">
                                <fieldset>
                                    <div class="f-row">
                                        <label><?php echo $tlogin; ?></label>
                                        <div class="f-inputs">
                                            <input type="text" class="i-login" value="<?php echo $login; ?>" name="login" id="login">
                                        </div>
                                    </div>
                                    <div class="f-row">
                                        <label><?php echo $password; ?></label>
                                        <div class="f-inputs">
                                            <input type="password" class="i-login" value="" name="password" id="password">
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="f-row f-actions">
                                    <div style="text-align: center;">
                                        <input type="submit" value="<?php echo $button_text; ?>" name="" class="button"> | <a href="<?php print site_url('user/registration'); ?>"><?php echo $reg; ?></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <em class="bl"></em>
                <em class="br"></em>
            </div>
            <div class="errors" id="login-errors">
                <?php if($errors): ?>
                    <?php echo $errors; ?>
                <?php endif; ?>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        
        function validate_login_form(){
            var login = document.getElementById('login').value;
            var password = document.getElementById('password').value;
            var counter = 0;

            var div = document.getElementById('login-errors');
            div.innerHTML = '';

            if (login.length < 1 || password.length < 1){
                div.innerHTML += '<li><?php echo $this->lang->line('error_login'); ?></li>';
                counter++;
            }

            if (counter > 0)
                return false;
            else
                return true;
        }

        
    </script>
</html>