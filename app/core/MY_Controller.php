<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    private $siteLang = 'portugues';
    private $group_acl = [
        'estoque' => [
            'estoque',
            'reports/get_relatorios_estoque'
        ],
        'adm' => [
            'products',
            'products/*',
            'categories',
            'categories/*',
            'depositos/*',
            'sales',
            'sales/*',
            'sellers',
            'sellers/*',
            'stock/*',
            'reports/get_relatorios_estoque',
            'oficina/*',
            'producao/*',
            'relatorio/*',
            'logout'
        ],
        'oficina_master' => [
            'oficina/piloto_e_corte',
            'oficina/*',
            'producao/*',
            'logout'
        ],
        'oficina_model' => [
            'oficina/piloto_e_corte',
            'oficina/*',
            'producao/*',
            'logout'
        ]
    ];

    public function __construct() {

        parent::__construct();

        define("DEMO", 0);

        $this->Settings = $this->site->getSettings();

        $lang = $this->session->userdata('site_lang');

        if ($lang) {
            $this->siteLang = $lang;
        }

        $this->lang->load('app', $this->siteLang);

        $this->data['user_group'] = $this->session->userdata('group_name');
        
        $this->data['permissoes'] = $this->session->userdata('permissoes');

        $this->Admin = ($this->data['user_group'] === 'admin');

        $this->data['Admin'] = $this->Admin;
        
        $this->check_access();
        
        $this->Settings->pin_code = $this->Settings->pin_code ? md5($this->Settings->pin_code) : NULL;
        $this->theme = $this->Settings->theme . '/views/';
        $this->data['assets'] = base_url() . 'themes/default/assets/';
        $this->data['Settings'] = $this->Settings;
        $this->loggedIn = $this->tec->logged_in();
        $this->data['loggedIn'] = $this->loggedIn;
        $this->data['categories'] = $this->site->getAllCategories();

        $this->m = strtolower($this->router->fetch_class());
        $this->v = strtolower($this->router->fetch_method());
        $this->data['m'] = $this->m;
        $this->data['v'] = $this->v;
    }

    function page_construct($page, $data = array(), $meta = array()) {
        
        if (empty($meta)) {
            $meta['page_title'] = $data['page_title'];
        }
        
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        $meta['ip_address'] = $this->input->ip_address();

        $meta['user_group'] = $data['user_group'];

        $meta['Admin'] = $data['Admin'];
        $meta['loggedIn'] = $data['loggedIn'];
        $meta['Settings'] = $data['Settings'];
        $meta['assets'] = $data['assets'];
        $meta['suspended_sales'] = $this->site->getUserSuspenedSales();
        $meta['qty_alert_num'] = $this->site->getQtyAlerts();

        $meta['site_lang'] = $this->siteLang;

        $meta['permissoes'] = $data['permissoes'];
        
        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');
    }

    private function check_access() {
        
        if ($this->Admin) {
            return;
        }

        $uri = $this->router->uri->uri_string;

        if (empty($uri) || $uri === "/") {
            return;
        }
        
        if ($uri === "login" || $uri === "logout") {
            return;
        }
        
        if (strpos($uri, "users/profile/") === 0) {
           return; 
        }
        
        if (strpos($uri, "auth/edit_user/") === 0) {
           return; 
        }
        
        if (strpos($uri, "auth/change_password") === 0) {
           return; 
        }
        
        if (isset($this->data['permissoes']["/$uri"])) {
            return;
        }
        
        $parts = explode("/", $uri);
        
        if (isset($this->data['permissoes']["$parts[0]"])) {
            return;
        }
        
        if ($this->input->is_ajax_request()) {
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('/');
        }
    }

}
