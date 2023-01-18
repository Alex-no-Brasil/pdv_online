<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Session extends CI_Session
{

    public function __construct(array $params = array())
    {
        $CI = get_instance();

        if (in_array($CI->router->class, array('api'))) {
            return;
        }
        
        parent::__construct($params);
    }
}