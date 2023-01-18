<?php

class MY_Exceptions extends CI_Exceptions {

    public function __construct() {
        parent::__construct();
    }

    public function show_404($page = '', $log_error = TRUE) {
        if (is_cli()) {
            $heading = 'Not Found';
            $message = 'The controller/method pair you requested was not found.';
        } else {
            $heading = '404 Page Not Found';
            $message = 'The page you requested was not found.';
        }

        /*
          if ($log_error) {
          log_message('error', $heading . ': ' . $page);
          }
         */

        echo $this->show_error($heading, $message, 'error_404', 404);

        exit(4); // EXIT_UNKNOWN_FILE
    }

}
