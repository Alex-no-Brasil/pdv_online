<?php

class Tasks extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->tec->in_group('admin') && php_sapi_name() !== 'cli') {
            exit("Acesso negado");
        }
    }

    public function updateDatabase() {
        ini_set('display_errors', 0);
        
        $files = glob("update_db/*/*.sql");

        foreach ($files as $file) {
            $sql = file_get_contents($file);

            $sqls = explode(';', $sql);
            array_pop($sqls);

            foreach ($sqls as $statement) {
                $stm = $statement . ";";
                $this->db->query($stm);
            }

            unlink($file);
        }
    }

    public function importaEstoque()
    {
        $this->load->model('products_model');
        $this->load->model('depositoproduto_model');
        
        $map = [
            1 => 'EST_3',
            2 => '4001',
            3 => '4002',
            4 => '4017',
            5 => '4019'
        ];

        $fh = fopen("estoque.csv", "r");

        $header = fgetcsv($fh, 1024, ",");

        $this->products_model->db->update('products', ['quantity' => 0]);
        
        while (($row = fgetcsv($fh, 1024, ",")) !== false) {

            $code = trim($row[0]);

            $prod = $this->products_model->getByCode($code);

            if (!$prod) {
                echo "Não achou; $code\n";
                continue;
            }
                
            foreach ($map as $i => $cod)  {
                $total = trim($row[$i]);
                
                $this->depositoproduto_model->updateEstoqueDeposito([
                    'cod_loja' => $cod,
                    'cod_produto' => $prod->ean,
                    'qtd' => $total
                ]);
            }
        }
    }
}
