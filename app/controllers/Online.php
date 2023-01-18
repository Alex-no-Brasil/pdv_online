<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Online extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('pedidoonline_model');
    }

    public function pedido() {

        $bc = array(array('link' => '#', 'page' => lang('Online')), array('link' => '#', 'page' => lang('Pedidos'))); //rota na página

        $meta = array('page_title' => lang('Pedido'), 'bc' => $bc); //título na página

        $this->data['date_start'] = strtotime("-30 days") * 1000;

        $this->data['date_end'] = time() * 1000;

        $this->page_construct('online/pedido', $this->data, $meta); //rota
    }

    public function get_pedidos() {

        $this->load->library('datatables');

        $pedido = $this->db->dbprefix('online_pedidos');

        $cliente = $this->db->dbprefix('online_pedido_clientes');

        $entrega = $this->db->dbprefix('online_pedido_entregas');

        $this->datatables->select("$pedido.id, $pedido.externalId, $pedido.externalCreated,"
                . "$cliente.name as cliente, $entrega.name as entrega, $pedido.totalItems, $pedido.totalAmount,"
                . "$pedido.status, $entrega.service, $pedido.origem, $pedido.sellerName, $pedido.paymentType,"
                . "$pedido.confirmaPacote,$pedido.confirmaEnvio,"
                . "$pedido.paymentId,$pedido.comprovante", FALSE);

        $this->datatables->from($pedido)
                ->join($cliente, "$cliente.pedidoId=$pedido.id", 'LEFT')
                ->join($entrega, "$entrega.pedidoId=$pedido.id", 'LEFT');

        $date_start = date('Y-m-d', $this->input->post('start') / 1000);
        $date_end = date('Y-m-d', $this->input->post('end') / 1000);

        $this->datatables->where("$pedido.externalCreated >=", "$date_start 00:00:00");
        $this->datatables->where("$pedido.externalCreated <=", "$date_end 23:59:59");

        $this->datatables->add_column("Actions", "$1,$2,$3", "$pedido.id,$pedido.paymentId,$pedido.comprovante");

        $this->datatables->unset_column("$pedido.id");

        $this->datatables->unset_column("$pedido.paymentId");

        $this->datatables->unset_column("$pedido.comprovante");

        echo $this->datatables->generate();
    }

    public function modal_pedido_detalhes($pedidoId) {

        $pedido = $items = $this->pedidoonline_model->select($pedidoId);

        $items = $this->pedidoonline_model->selectItems($pedidoId);

        $cliente = $this->pedidoonline_model->selectCliente($pedidoId);

        $entrega = $this->pedidoonline_model->selectEntrega($pedidoId);

        $dados = [
            'pedido' => $pedido,
            'items' => $items,
            'cliente' => $cliente,
            'entrega' => $entrega
        ];

        $this->load->view($this->theme . 'online/modal_pedido_detalhes', $dados);
    }
    
    public function imprimir_romaneio($pedidoId) {

        $pedido = $items = $this->pedidoonline_model->select($pedidoId);

        $items = $this->pedidoonline_model->selectItems($pedidoId);

        $cliente = $this->pedidoonline_model->selectCliente($pedidoId);

        $entrega = $this->pedidoonline_model->selectEntrega($pedidoId);

        $dados = [
            'pedido' => $pedido,
            'items' => $items,
            'cliente' => $cliente,
            'entrega' => $entrega
        ];

        $this->load->view($this->theme . 'online/imprimir_romaneio', $dados);
    }

    public function resumo_vendedor() {

        $bc = array(array('link' => '#', 'page' => lang('Online')), array('link' => '#', 'page' => lang('Resumo vendedor'))); //rota na página

        $meta = array('page_title' => lang('Resumo vendedor'), 'bc' => $bc); //título na página

        $this->data['date_start'] = strtotime(date("Y-m-01")) * 1000;

        $this->data['date_end'] = time() * 1000;

        $this->page_construct('online/resumo_vendedor', $this->data, $meta); //rota
    }

    public function get_resumo_vendedor() {

        $this->load->model('onlineresumo_model');

        $date_start = date('Y-m-d', $this->input->get('start') / 1000);

        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $resumo = $this->onlineresumo_model->resumo_vendendor($date_start, $date_end);

        $dados = [];

        foreach ($resumo as $vendedor) {
            $dados[] = array_values((array) $vendedor);
        }

        header('Content-Type: application/json');

        echo json_encode($dados);
    }

    public function resumo_pagamento() {

        $bc = array(array('link' => '#', 'page' => lang('Online')), array('link' => '#', 'page' => lang('Resumo forma de pagamento'))); //rota na página

        $meta = array('page_title' => lang('Resumo forma de pagamento'), 'bc' => $bc); //título na página

        $this->data['date_start'] = strtotime(date("Y-m-01")) * 1000;

        $this->data['date_end'] = time() * 1000;

        $this->page_construct('online/resumo_forma_pagamento', $this->data, $meta); //rota
    }

    public function get_resumo_pagamento() {

        $this->load->model('onlineresumo_model');

        $date_start = date('Y-m-d', $this->input->get('start') / 1000);

        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $resumo = $this->onlineresumo_model->resumo_pagamento($date_start, $date_end);

        $dados = [];

        foreach ($resumo as $vendedor) {
            $dados[] = array_values((array) $vendedor);
        }

        header('Content-Type: application/json');

        echo json_encode($dados);
    }

    public function pedido_upload_comprovante() {

        $this->load->library('upload');

        $uploads = 'uploads/pedidos/';

        if (!file_exists($uploads)) {
            mkdir($uploads);
        }

        $config['upload_path'] = $uploads;
        $config['allowed_types'] = array('png', 'jpg', 'jpeg', 'pdf');
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            echo $this->upload->display_errors();
            exit;
        }

        echo site_url() . $uploads . $this->upload->file_name;
    }

    public function pedido_atualiza() {

        $post = $this->input->post();

        if (empty($post['id'])) {
            exit;
        }

        $dados = [];

        if (!empty($post['comprovante'])) {
            $dados['comprovante'] = $post['comprovante'];
        }

        if (!empty($post['confirma_pacote'])) {
            $dados['confirmaPacote'] = time();
        }

        if (!empty($post['confirma_envio'])) {
            $dados['confirmaEnvio'] = time();
        }

        if (empty($dados)) {
            exit;
        }

        $this->pedidoonline_model->update($post['id'], $dados);
    }
    
    /*Página espelho Pedidos, para vendedoras gerar romaneio */
    
    public function gerar_romaneio() {

        $bc = array(array('link' => '#', 'page' => lang('Online')), array('link' => '#', 'page' => lang('Gerar Romaneio '))); //rota na página

        $meta = array('page_title' => lang('Gerar Romaneio'), 'bc' => $bc); //título na página

        $this->data['date_start'] = strtotime("-30 days") * 1000;

        $this->data['date_end'] = time() * 1000;

        $this->page_construct('online/gerar_romaneio', $this->data, $meta); //rota
    }
    
    public function get_pedidos_romaneio() {

        $this->load->library('datatables');

        $pedido = $this->db->dbprefix('online_pedidos');

        $cliente = $this->db->dbprefix('online_pedido_clientes');

        $entrega = $this->db->dbprefix('online_pedido_entregas');

        $this->datatables->select("$pedido.id, $pedido.externalId, $pedido.externalCreated,"
                . "$cliente.name as cliente, $pedido.totalItems, $pedido.totalAmount,"
                . "$pedido.status, $pedido.origem, $pedido.sellerName,"
                . "$pedido.paymentId,$pedido.comprovante", FALSE);

        $this->datatables->from($pedido)
                ->join($cliente, "$cliente.pedidoId=$pedido.id", 'LEFT')
                ->join($entrega, "$entrega.pedidoId=$pedido.id", 'LEFT');

        $date_start = date('Y-m-d', $this->input->post('start') / 1000);
        $date_end = date('Y-m-d', $this->input->post('end') / 1000);

        $this->datatables->where("$pedido.externalCreated >=", "$date_start 00:00:00");
        $this->datatables->where("$pedido.externalCreated <=", "$date_end 23:59:59");

        $this->datatables->add_column("Actions", "$1,$2,$3", "$pedido.id,$pedido.paymentId,$pedido.comprovante");

        $this->datatables->unset_column("$pedido.id");

        $this->datatables->unset_column("$pedido.paymentId");

        $this->datatables->unset_column("$pedido.comprovante");

        echo $this->datatables->generate();
    }
    
    public function imprimir_romaneio_tel($pedidoId) {

        $pedido = $items = $this->pedidoonline_model->select($pedidoId);

        $items = $this->pedidoonline_model->selectItems($pedidoId);

        $cliente = $this->pedidoonline_model->selectCliente($pedidoId);

        $entrega = $this->pedidoonline_model->selectEntrega($pedidoId);

        $dados = [
            'pedido' => $pedido,
            'items' => $items,
            'cliente' => $cliente,
            'entrega' => $entrega
        ];

        $this->load->view($this->theme . 'online/imprimir_romaneio_tel', $dados);
    }
    
}
