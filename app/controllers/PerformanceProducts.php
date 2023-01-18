<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PerformanceProducts extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->Admin) {
            redirect('pos');
        }

        $this->load->model('reports_model');
    }

    public function index() {
        $this->data['page_title'] = 'Desempenho de Produtos';

        $bc = array(array('link' => '#', 'page' => lang('reports')), array('link' => '#', 'page' => 'Desempenho de Produtos'));
        $meta = array('page_title' => 'Desempenho de Produtos', 'bc' => $bc);

        $this->data['date_start'] = strtotime('-30 days') * 1000;
        $this->data['date_end'] = time() * 1000;

        $this->page_construct('reports/performance_products', $this->data, $meta);
    }

    public function model() {
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $data = [
            'id' => 'model',
            'title' => 'Modelo',
            'series' => [],
            'colors' => ['#a4c2f4', '#f7a35c']
        ];

        $rows = $this->reports_model->productsPerformance('model', $date_start, $date_end);

        foreach ($rows as $row) {
            $data['series'][] = $row;
        }

        $this->send($data);
    }

    public function category() {
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $this->load->model('categories_model');

        $categories = [];

        $rows = $this->categories_model->findAll();

        foreach ($rows as $row) {
            $categories[$row->id] = $row->name;
        }

        $data = [
            'id' => 'category',
            'title' => 'Categoria',
            'series' => [],
            'colors' => null
        ];

        $rows = $this->reports_model->productsPerformance('category_id', $date_start, $date_end);

        foreach ($rows as $row) {
            $row->label = $categories[$row->label];
            $data['series'][] = $row;
        }

        $this->send($data);
    }

    public function season() {
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $data = [
            'id' => 'season',
            'title' => 'Estação',
            'series' => [],
            'colors' => ['#2b908f', '#e4d354']
        ];

        $rows = $this->reports_model->productsPerformance('season', $date_start, $date_end);

        foreach ($rows as $row) {
            $data['series'][] = $row;
        }

        $this->send($data);
    }

    public function material() {
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $data = [
            'id' => 'material',
            'title' => 'Tecido',
            'series' => [],
            'colors' => null
        ];

        $rows = $this->reports_model->productsPerformance('material', $date_start, $date_end);

        foreach ($rows as $row) {
            $data['series'][] = $row;
        }

        $this->send($data);
    }

    public function stamp() {
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $data = [
            'id' => 'stamp',
            'title' => 'Estampa',
            'series' => [],
            'colors' => ["#90ed7d", "#f7a35c", "#8085e9", "#f15c80"]
        ];

        $rows = $this->reports_model->productsPerformance('stamp', $date_start, $date_end);

        foreach ($rows as $row) {
            if (empty($row->label)) {
                $row->label = 'Desconhecida';
            }
            
            $data['series'][] = $row;
        }

        $this->send($data);
    }

    public function manga() {
        $date_start = date('Y-m-d', $this->input->get('start') / 1000);
        $date_end = date('Y-m-d', $this->input->get('end') / 1000);

        $data = [
            'id' => 'manga',
            'title' => 'Manga',
            'series' => [],
            'colors' => ["#2b908f", "#f45b5b", "#f15c80"]
        ];

        $rows = $this->reports_model->productsPerformance('manga', $date_start, $date_end);

        foreach ($rows as $row) {
            if (empty($row->label)) {
                $row->label = 'Desconhecida';
            }

            $data['series'][] = $row;
        }

        $this->send($data);
    }

    private function send($data) {

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }

}
