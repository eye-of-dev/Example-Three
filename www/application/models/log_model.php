<?php

class Log_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function error($message, $url = NULL)
    {
        $_SESSION['error'][] = $message;

        if (!is_null($url))
        {
            redirect($url);
        }
    }

    function success($message, $url = NULL)
    {
        $_SESSION['success'][] = $message;

        if (!is_null($url))
        {
            redirect($url);
        }
    }

    function show($display = TRUE)
    {
        $msg = '';
        if (isset($_SESSION['error']))
        {
            foreach ($_SESSION['error'] as $error)
            {
                if ($display)
                {
                    echo '<div class="amazing-error">' . $error . '</div>';
                }
                else
                {
                    $msg .= '<div class="amazing-error">' . $error . '</div>';
                }
            }

            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success']))
        {
            foreach ($_SESSION['success'] as $success)
            {
                if ($display)
                {
                    echo '<div class="amazing-success">' . $success . '</div>';
                }
                else
                {
                    $msg .= '<div class="amazing-error">' . $success . '</div>';
                }
            }

            unset($_SESSION['success']);
        }

        return $display ? NULL : $msg;
    }

}
