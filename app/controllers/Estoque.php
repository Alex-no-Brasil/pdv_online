<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estoque extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }
    }

    public function index()
    {
        $this->data['page_title'] = 'Relatório de Estoque';

        $this->load->helper('relatorio_estoque');

        $this->data['thead'] = relatorio_estoque_thead();

        $this->data['date_start'] = strtotime('-7 days') * 1000;
        $this->data['date_end'] = time() * 1000;

        $this->load->view($this->theme . 'estoque', $this->data);
    }
}
