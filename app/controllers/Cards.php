<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cards extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();


        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->library('form_validation');
        $this->load->model('cards_model');
    }

    public function tax()
    {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = 'Taxas';
        $bc = array(array('link' => '#', 'page' => 'Taxas'));
        $meta = array('page_title' => 'Taxas', 'bc' => $bc);

        $this->data['tax'] = [];
        
        $tax = $this->cards_model->getAllTax();

        foreach ($tax as $row) {
            $this->data['tax'][$row->type] = $row;
        }
        
        $this->page_construct('cards/tax', $this->data, $meta);
    }

    public function save_tax()
    {
        $post = $this->input->post();

        $data = [];

        if (isset($post['debit'])) {
            $data['debit'] = $post['debit'];
        }

        if (isset($post['credit'])) {
            $data['credit'] = $post['credit'];
        }
        
        for($i = 2; $i<=6; $i++) {
            $type = "credit_$i" . "x";
            if (isset($post[$type])) {
                $data[$type] = $post[$type];
            }
        }

        foreach ($data as $type => $row) {
            
            $row['type'] = $type;
            
            $row['updatedAt'] = date('Y-m-d H:i:s');

            if ($this->cards_model->getTax($type)) {
                $this->cards_model->updateTax($type, $row);
            } else {
                $row['createdAt'] = date('Y-m-d H:i:s');
                $this->cards_model->addTax($row);
            }
        }
        
        $this->session->set_flashdata('message', 'Taxas atualizadas');
        
        redirect('cards/tax');
    }
}
