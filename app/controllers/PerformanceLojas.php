<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PerformanceLojas extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->Admin) {
            redirect('pos');
        }

        $this->load->model('reports_model');
    }

    public function index() {
        $this->data['page_title'] = 'Desempenho de Lojas';

        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => $this->data['page_title']));
        $meta = array('page_title' => $this->data['page_title'], 'bc' => $bc);

        $this->data['date_start'] = strtotime('-30 days') * 1000;
        $this->data['date_end'] = time() * 1000;

        //print_r($this->reports_model->lojaPerformance('2021-04-01', '2021-04-20'));

        $this->page_construct('reports/performance_lojas', $this->data, $meta);
    }

    public function chart() {

        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $series = [
            'vendas' => [],
            'pecas' => [],
            'valor' => []
        ];

        $rows = $this->reports_model->lojaPerformanceVendas($date_start, $date_end);

        foreach ($rows as $row) {
            
            $series['valor'][] = [
                'label' => $row->label,
                'value' => floatval($row->valor),
                'col_color' => 'rgb(144, 237, 125)'
            ];
            
            $series['vendas'][] = [
                'label' => $row->label,
                'value' => intval($row->vendas),
                'col_color' => 'rgb(149, 206, 255)'
            ];
        }
        
        $rows = $this->reports_model->lojaPerformancePecas($date_start, $date_end);
        
        foreach ($rows as $row) {
            
            $series['pecas'][] = [
                'label' => $row->label,
                'value' => intval($row->pecas),
                'col_color' => 'rgb(128, 133, 233)'
            ];
        }

        header('Content-Type: application/json');

        echo json_encode($series);
    }

}
