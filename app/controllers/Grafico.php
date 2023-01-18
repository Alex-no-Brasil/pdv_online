<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grafico extends MY_Controller {

    public function __construct() {
        
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('oficinarelatorio_model');
    }
    
    public function relatorio_peca() {

        $bc = array(array('link' => '#', 'page' => lang('Gráfico')), array('link' => '#', 'page' => lang('Relatório peças')));//rota na página
        
        $meta = array('page_title' => lang('Relatório peças'), 'bc' => $bc);//título na página
                
        $dados = [
            ["Element", "Quantidade", ["role" => "style"]]
        ];
        
        $oficinas = $this->oficinarelatorio_model->grafico_pecas();
        
        foreach ($oficinas as $oficina) {
            $dados[] = [$oficina->nome, intval($oficina->qtd_boa), '#00a65a'];
        }
        
        if(count($dados) > 1) {
            $this->data['dados'] = json_encode($dados);
        } else {
            $this->data['dados'] = '[]';
        }
        
        $this->page_construct('grafico/relatorio_peca', $this->data, $meta); //rota
    }
    
    public function relatorio_corte() {

        $bc = array(array('link' => '#', 'page' => lang('Gráfico')), array('link' => '#', 'page' => lang('Relatório cortes')));//rota na página
        
        $meta = array('page_title' => lang('Relatório cortes'), 'bc' => $bc);//título na página
        
        $dados = [
            ["Element", "Quantidade", ["role" => "style"]]
        ];
        
        $oficinas = $this->oficinarelatorio_model->grafico_cortes();
        
        foreach ($oficinas as $oficina) {
            $dados[] = [$oficina->nome, intval($oficina->cortes), '#00a65a'];
        }
        
        if (count($dados) > 1) {
            $this->data['dados'] = json_encode($dados);
        } else {
            $this->data['dados'] = '[]';
        }
        
        $this->page_construct('grafico/relatorio_corte', $this->data, $meta); //rota
    }
    
    public function relatorio_receita() {

        $bc = array(array('link' => '#', 'page' => lang('Gráfico')), array('link' => '#', 'page' => lang('Relatório receita')));//rota na página
        
        $meta = array('page_title' => lang('Relatório receita'), 'bc' => $bc);//título na página
        
        $dados = [
            ["Element", "Quantidade", ["role" => "style"]]
        ];
        
        $oficinas = $this->oficinarelatorio_model->grafico_receita();
        
        foreach ($oficinas as $oficina) {
            $dados[] = [$oficina->nome, floatval($oficina->receita), '#00a65a'];
        }
        
        if (count($dados) > 1) {
            $this->data['dados'] = json_encode($dados);
        } else {
            $this->data['dados'] = '[]';
        }
        
        $this->page_construct('grafico/relatorio_receita', $this->data, $meta); //rota
    }

}
