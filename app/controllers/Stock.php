<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('reports_model');
        $this->load->model('lojas_model');
        $this->load->model('categories_model');

    }
    
    public function mapa_loja() {

        $bc = array(array('link' => '#', 'page' => lang('Estoque')), array('link' => '#', 'page' => lang('Mapa da loja'))); //rota na página
        
        $meta = array('page_title' => lang('Mapa da loja'), 'bc' => $bc); //título na página
        
        $this->page_construct('stock/mapa_loja', $this->data, $meta); //rota
    }
    
    public function missao_loja() {

        $bc = array(array('link' => '#', 'page' => lang('Estoque')), array('link' => '#', 'page' => lang('Missão da loja'))); //rota na página
        
        $meta = array('page_title' => lang('Missão da loja'), 'bc' => $bc); //título na página
        
        $lojas = $this->lojas_model->getAllCod('LOJA');
        
        $lista_lojas = [];
        
        foreach ($lojas as $cod) {
            
            if ($cod === 'ONLINE') {
                continue;
            }
            
            $lista_lojas[$cod] = [];
        }
        
        $this->data['lojas'] = $lista_lojas;
        
        $this->page_construct('stock/missao_loja', $this->data, $meta); //rota
    }    
    
    function estoque() {
        $this->data['page_title'] = 'Relatório de Estoque';
        $bc = array(array('link' => '#', 'page' => lang('stock')), array('link' => '#', 'page' => 'Relatório de Estoque'));
        $meta = array('page_title' => 'Relatório de Estoque', 'bc' => $bc);

        $this->load->helper('relatorio_estoque');

        $this->data['thead'] = relatorio_estoque_thead();

        $this->data['date_start'] = strtotime('-7 days') * 1000;
        $this->data['date_end'] = time() * 1000;

        $meta['menu_fixed'] = true;

        $this->page_construct('stock/estoque', $this->data, $meta);
    }

    function foto_estoque() {

        $bc = array(array('link' => '#', 'page' => lang('Relatórios')), array('link' => '#', 'page' => lang('Estoque com foto'))); //rota na página
        $meta = array('page_title' => lang('Estoque com foto'), 'bc' => $bc); //título na página


        $this->load->helper('relatorio_estoque');
        
        $this->data['thead'] = relatorio_estoque_thead(true);

        $this->data['date_start'] = strtotime('-7 days') * 1000;
        $this->data['date_end'] = time() * 1000;

        $meta['menu_fixed'] = true;

        $this->page_construct('stock/foto_estoque', $this->data, $meta); //rota
    }
    
    function conferencia() {

        $bc = array(array('link' => '#', 'page' => lang('Relatórios')), array('link' => '#', 'page' => lang('Conferência'))); //rota na página
        $meta = array('page_title' => lang('Conferência'), 'bc' => $bc); //título na página

        $categorias = $this->categories_model->findAll();

        foreach ($categorias as $categoria) {
            $this->data['categorias'][$categoria->id] = $categoria->name;
        }
        
        
        $lojas = $this->lojas_model->getAllLojas();

        foreach ($lojas as $loja) {
            $this->data['lojas'][$loja->cod] = $loja->nome;
        }
        

        $this->page_construct('stock/conferencia', $this->data, $meta); //rota
    }
    
    function conferencia_filtro() {
        
        $cod_loja = $this->input->get('cod_loja');
        
        $categoria_id = $this->input->get('categoria_id');
        
        $valor = $this->input->get('valor');
        
        $manga = $this->input->get('manga');
        
        $todos_valor = $this->input->get('todos_valor');
        
        if (empty($cod_loja) || empty($categoria_id) || (empty($valor) && $todos_valor === "false")) {
            exit;
        }
        
        if ($todos_valor === "false") {
            $valor = str_replace(",", ".", $valor);
        } else {
            $valor = "";
        }
        
        $like = '';
        
        if ($manga == 'longa') {
            $like = "1 %";
        }
        
        if ($manga =='curta') {
            $like = "2 %";
        }
        
        $produtos = $this->reports_model->conferencia_produto($cod_loja, $categoria_id, $valor, $like);
        
        $this->data['produtos'] = $produtos;
        
        $this->load->view($this->theme . 'stock/conferencia_lista', $this->data);
    }

    public function exporta_conferencia() {
        
        $cod_loja = $this->input->get('cod_loja');
        
        $categoria_id = $this->input->get('categoria_id');
        
        $valor = $this->input->get('valor');
        
        $manga = $this->input->get('manga');
        
        $todos_valor = $this->input->get('todos_valor');
        
        if (empty($cod_loja) || empty($categoria_id) || (empty($valor) && $todos_valor === "false")) {
            exit;
        }
        
        if ($todos_valor === "false") {
            $valor = str_replace(",", ".", $valor);
        } else {
            $valor = "";
        }
        
        $like = '';
        
        if ($manga == 'longa') {
            $like = "1 %";
        }
        
        if ($manga == 'curta') {
            $like = "2 %";
        }
        
        $produtos = $this->reports_model->conferencia_produto($cod_loja, $categoria_id, $valor, $like);
                
        $this->data['produtos'] = $produtos;
        
        $this->load->model('categories_model');
        
        $categoria = $this->categories_model->select($categoria_id);
        
        $this->data['categoria'] = $categoria->name;   
        
        $html =  $this->load->view($this->theme . 'stock/conferencia_exporta', $this->data, true);
        
        $file = tempnam("/tmp", "conferencia_exporta");
        
        file_put_contents($file, $html);
        
        require_once 'vendor/autoload.php';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        
        $spreadsheet = $reader->load($file);

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"conferencia.xlsx\"");
        header("Content-Transfer-Encoding: binary");
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        
        $writer->save('php://output');
    }

}
