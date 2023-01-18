<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sellers extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('sellers_model');
        $this->load->model('lojas_model');
    }

    public function index()
    {
        $this->data['page_title'] = 'Vendedores';
        $bc = array(array('link' => '#', 'page' => 'Vendedores'));
        $meta = array('page_title' => 'Vendedores', 'bc' => $bc);
        $this->page_construct('sellers/index', $this->data, $meta);
    }

    public function get_sellers()
    {
        $this->load->library('datatables');

        $sel = $this->db->dbprefix('sellers');
        $loj = $this->db->dbprefix('lojas');
        
        $this->datatables->select("$sel.id as sid, name, $loj.nome as loja, status");

        $this->datatables->from($sel)
        ->join($loj, "$loj.cod=$sel.cod_loja");

        $this->datatables->add_column("Actions", "<div class='text-center'>"
            . "<div class='btn-group actions'>"
            . "<a href='" . site_url('sellers/edit/$1') . "' title='" . lang("edit") . "' class='tip btn btn-warning btn-xs'>"
            . "<i class='fa fa-edit'></i>"
            . "</a>"
            . "</div>"
            . "</div>", "sid");
        $this->datatables->unset_column('sid');

        echo $this->datatables->generate();
    }

    public function add()
    {
        $this->data['page_title'] = 'Vendedor';
        $bc = array(array('link' => '#', 'page' => 'Vendedor'));
        $meta = array('page_title' => 'Vendedor', 'bc' => $bc);

        $this->data['id'] = '';
        $this->data['name'] = '';
        $this->data['cod_loja'] = '';
        $this->data['status'] = '';

        $this->data['lojas'] = $this->lojas_model->getAllLojas();

        $this->page_construct('sellers/form', $this->data, $meta);
    }

    public function edit($id)
    {
        $seller = $this->sellers_model->get($id);

        if (!$seller) {
            $this->session->set_flashdata('error', 'Vendedor não encontrado');
            redirect('sellers/index');
        }
        
        $this->data['page_title'] = 'Vendedor';
        $bc = array(array('link' => '#', 'page' => 'Vendedor'));
        $meta = array('page_title' => 'Vendedor', 'bc' => $bc);

        $this->data['id'] = $seller->id;
        $this->data['name'] = $seller->name;
        $this->data['cod_loja'] = $seller->cod_loja;
        $this->data['status'] = $seller->status;

        $this->data['lojas'] = $this->lojas_model->getAllLojas();

        $this->page_construct('sellers/form', $this->data, $meta);
    }

    public function save()
    {
        $post = $this->input->post();

        $data = [
            'name' => $post['name'],
            'cod_loja' => $post['cod_loja'],
            'status' => $post['status'],
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        if ($post['id'] > 0) {
            $this->sellers_model->update($post['id'], $data);
            $this->session->set_flashdata('message', 'Vendedor atualizado');
        } else {
            $data['createdAt'] = date('Y-m-d H:i:s');
            $this->sellers_model->insert($data);
            $this->session->set_flashdata('message', 'Vendedor cadastrado');
        }

        redirect('sellers/index');
    }
}
