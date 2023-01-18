<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorio extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('oficinarelatorio_model');
    }

    public function chegada_peca() {

        $bc = array(array('link' => '#', 'page' => lang('Relatório')), array('link' => '#', 'page' => lang('Chegada peças'))); //rota na página

        $meta = array('page_title' => lang('Chegada peças'), 'bc' => $bc); //título na página

        $this->load->helper('daterange');

        $meses = [];

        $range = daterange(date('Y-m-01', strtotime("-12 month")), date('Y-m-d'));

        foreach ($range as $day) {
            list($y, $m, $d) = explode("-", $day);
            $meses["$y-$m"] = "$m/$y";
        }

        krsort($meses);

        $this->data['meses'] = $meses;

        $mes = $this->input->get('mes');

        if (!$mes) {
            $mes = date('Y-m');
        }
            
        $this->data['mes'] = $mes;
        
        $dias = [];

        for ($d = 1; $d <= 31; $d++) {

            if ($d < 10) {
                $d = "0$d";
            }

            $dias[$d] = 0;
        }
        
        $relatorios = [];
        
        $totais = $dias;
        
        $defeitos = $dias;
        
        $oficinas = $this->oficinarelatorio_model->pecas_diaria($mes);
        
        foreach ($oficinas as $oficina) {
            
            if (!isset($relatorios[$oficina->nome])) {
                $relatorios[$oficina->nome] = $dias;
            }
            
            $relatorios[$oficina->nome][$oficina->dia] += $oficina->qtd_boa;
                        
            $defeitos[$oficina->dia] += $oficina->qtd_defeito;
            
            $totais[$oficina->dia] += $oficina->qtd_boa + $oficina->qtd_defeito;
        }
        
        $this->data['relatorios'] = $relatorios;
        
        $this->data['defeitos'] = $defeitos;
        
        $this->data['totais'] = $totais;

        $this->page_construct('relatorio/chegada_peca', $this->data, $meta); //rota
    }

    public function chegada_corte() {

        $bc = array(array('link' => '#', 'page' => lang('Relatório')), array('link' => '#', 'page' => lang('Chegada cortes'))); //rota na página
        
        $meta = array('page_title' => lang('Chegada cortes'), 'bc' => $bc); //título na página
        
        $this->load->helper('daterange');

        $meses = [];

        $range = daterange(date('Y-m-01', strtotime("-12 month")), date('Y-m-d'));

        foreach ($range as $day) {
            list($y, $m, $d) = explode("-", $day);
            $meses["$y-$m"] = "$m/$y";
        }

        krsort($meses);

        $this->data['meses'] = $meses;

        $mes = $this->input->get('mes');

        if (!$mes) {
            $mes = date('Y-m');
        }
            
        $this->data['mes'] = $mes;
        
        $dias = [];

        for ($d = 1; $d <= 31; $d++) {

            if ($d < 10) {
                $d = "0$d";
            }

            $dias[$d] = 0;
        }
        
        $relatorios = [];
        
        $totais = $dias;
                
        $oficinas = $this->oficinarelatorio_model->cortes_diario($mes);
        
        foreach ($oficinas as $oficina) {
            
            if (!isset($relatorios[$oficina->nome])) {
                $relatorios[$oficina->nome] = $dias;
            }
            
            $relatorios[$oficina->nome][$oficina->dia]++;
                                    
            $totais[$oficina->dia]++;
        }
        
        $this->data['relatorios'] = $relatorios;
                
        $this->data['totais'] = $totais;
        
        $this->page_construct('relatorio/chegada_corte', $this->data, $meta); //rota
    }

    public function chegada_pagamento() {

        $bc = array(array('link' => '#', 'page' => lang('Relatório')), array('link' => '#', 'page' => lang('Chegada pagamento oficina'))); //rota na página
        
        $meta = array('page_title' => lang('Chegada pagamento oficina'), 'bc' => $bc); //título na página
        
        $this->load->helper('daterange');

        $meses = [];

        $range = daterange(date('Y-m-01', strtotime("-12 month")), date('Y-m-d'));

        foreach ($range as $day) {
            list($y, $m, $d) = explode("-", $day);
            $meses["$y-$m"] = "$m/$y";
        }

        krsort($meses);

        $this->data['meses'] = $meses;

        $mes = $this->input->get('mes');

        if (!$mes) {
            $mes = date('Y-m');
        }
            
        $this->data['mes'] = $mes;
        
        $dias = [];

        for ($d = 1; $d <= 31; $d++) {

            if ($d < 10) {
                $d = "0$d";
            }

            $dias[$d] = 0;
        }
        
        $relatorios = [];
        
        $pendentes = $dias;
        
        $pendentes_oficinas = [];
        
        $totais = $dias;
                
        $oficinas = $this->oficinarelatorio_model->pagamento_diario($mes);
        
        foreach ($oficinas as $oficina) {

            if (!isset($relatorios[$oficina->nome])) {
                $relatorios[$oficina->nome] = $dias;
            }

            $relatorios[$oficina->nome][$oficina->dia] += $oficina->paga_oficina;

            $totais[$oficina->dia] += $oficina->paga_oficina;

            if (empty($oficina->data_paga_oficina)) {
                
                $pendentes[$oficina->dia] += $oficina->paga_oficina;
                
                $pendentes_oficinas[$oficina->nome][$oficina->dia] = $oficina->paga_oficina;
            }
        }

        $this->data['relatorios'] = $relatorios;
                
        $this->data['totais'] = $totais;
        
        $this->data['pendentes'] = $pendentes;
        
        $this->data['pendentes_oficinas'] = $pendentes_oficinas;
        
        $this->page_construct('relatorio/chegada_pagamento', $this->data, $meta); //rota
    }
    
    
    public function chegada_pagamento_acabamento() {

        $bc = array(array('link' => '#', 'page' => lang('Relatório')), array('link' => '#', 'page' => lang('Chegada pagamento acabamento'))); //rota na página
        
        $meta = array('page_title' => lang('Chegada pagamento acabamento'), 'bc' => $bc); //título na página
                
        $this->load->helper('daterange');

        $meses = [];

        $range = daterange(date('Y-m-01', strtotime("-12 month")), date('Y-m-d'));

        foreach ($range as $day) {
            list($y, $m, $d) = explode("-", $day);
            $meses["$y-$m"] = "$m/$y";
        }

        krsort($meses);

        $this->data['meses'] = $meses;

        $mes = $this->input->get('mes');

        if (!$mes) {
            $mes = date('Y-m');
        }
            
        $this->data['mes'] = $mes;
        
        $dias = [];

        for ($d = 1; $d <= 31; $d++) {

            if ($d < 10) {
                $d = "0$d";
            }

            $dias[$d] = 0;
        }
        
        $relatorios = [];
        
        $pendentes = $dias;
        
        $pendentes_oficinas = [];
        
        $totais = $dias;
                
        $oficinas = $this->oficinarelatorio_model->pagamento_acabamento($mes);
        
        foreach ($oficinas as $oficina) {

            if (!isset($relatorios[$oficina->nome])) {
                $relatorios[$oficina->nome] = $dias;
            }

            $relatorios[$oficina->nome][$oficina->dia] += $oficina->paga_acabamento;

            $totais[$oficina->dia] += $oficina->paga_acabamento;

            if (empty($oficina->data_paga_acabamento)) {
                
                $pendentes[$oficina->dia] += $oficina->paga_acabamento;
                
                $pendentes_oficinas[$oficina->nome][$oficina->dia] = $oficina->paga_acabamento;
            }
        }

        $this->data['relatorios'] = $relatorios;
                
        $this->data['totais'] = $totais;
        
        $this->data['pendentes'] = $pendentes;
        
        $this->data['pendentes_oficinas'] = $pendentes_oficinas;
        
        $this->page_construct('relatorio/chegada_pagamento_acabamento', $this->data, $meta); //rota
    }

}
