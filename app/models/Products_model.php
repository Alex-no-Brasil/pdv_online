<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllProducts() {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllProductsNames() {

        $this->db->select('products.*, categories.name as category')
        ->join('categories', "categories.id=products.category_id", 'LEFT')
        ->order_by("name", "asc");
        
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[$row->code] = [
                    'ean' => $row->ean,
                    'codigo' => $row->code,
                    'name' => $row->name,
                    'valor' => $row->price,
                    'category' => $row->category,
                    'image' => $row->image
                ];
            }
            return $data;
        }
        return false;

        $q = $this->db->get('products')->limit(100, 0);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[$row->name] = ['descricao' => $row->details];
            }
            return $data;
        }
        return false;
    }

    public function products_count($category_id = NULL) {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
            return $this->db->count_all_results("products");
        } else {
            return $this->db->count_all("products");
        }
    }

    public function fetch_products($limit, $start, $category_id = NULL) {
        $this->db->select('name, code, barcode_symbology, price')
                ->limit($limit, $start)->order_by("code", "asc");
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        $q = $this->db->get("products");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getProductByCode($code) {
        $q = $this->db->get_where('products', array('ean' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getByEan($ean) {
        $q = $this->db->get_where('products', array('ean' => $ean), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getByCode($code) {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductById($id) {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addProduct($data, $items = array()) {
        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            if (!empty($items)) {
                foreach ($items as $item) {
                    $item['product_id'] = $product_id;
                    $this->db->insert('combo_items', $item);
                }
            }
            return $product_id;
        }
        return false;
    }

    public function add_products($data = array()) {
        if ($this->db->insert_batch('products', $data)) {
            return true;
        }
        return false;
    }

    public function updatePrice($data = array()) {
        if ($this->db->update_batch('products', $data, 'code')) {
            return true;
        }
        return false;
    }

    public function updateProduct($id, $data = array(), $items = array(), $photo = NULL) {
        if ($photo) {
            $data['image'] = $photo;
        }
        if ($this->db->update('products', $data, array('id' => $id))) {
            if (!empty($items)) {
                $this->db->delete('combo_items', array('product_id' => $id));
                foreach ($items as $item) {
                    $item['product_id'] = $id;
                    $this->db->insert('combo_items', $item);
                }
            }
            return true;
        }
        return false;
    }

    public function getComboItemsByPID($product_id) {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('combo_items') . '.quantity as qty, ' . $this->db->dbprefix('products') . '.name as name')
                ->join('products', 'products.code=combo_items.item_code', 'left')
                ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function deleteProduct($id) {
        if ($this->db->delete('products', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getProductNames($term, $limit = 10) {
        $this->db->where("type != 'combo' AND (name LIKE '%" . $term . "%' OR ean LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getRelatorioVendas($dataI, $dataF) {
        $this->db->select('tec_products.code, tec_products.ean, tec_products.name, tec_products.price as pvalor, tec_sales.cod_loja, SUM(tec_sale_items.quantity) as qtd_total');
        $this->db->from('tec_sale_items');
        $this->db->join('tec_sales', 'tec_sales.id = tec_sale_items.sale_id', 'INNER');
        $this->db->join('tec_products', 'tec_products.id = tec_sale_items.product_id', 'INNER');
        $this->db->where('tec_sales.date BETWEEN "' . $dataI . ' 00:00:00" and "' . $dataF . ' 23:59:59" and tec_sales.status="paid"');
        $this->db->group_by('tec_sales.cod_loja, tec_sale_items.product_id');

        $q = $this->db->get();
        
        return $q->result();
    }

    public function getEstoqueLojas($code) {
        $query = $this->db->get_where('products_estoque_lojas', ['code' => $code]);

        return $query->result();
    }
    
    public function getEstoqueLoja($code, $cod_loja) {
        $query = $this->db->get_where('products_estoque_lojas', ['code' => $code, 'cod_loja' => $cod_loja], 1);

        return $query->row();
    }

    public function addEstoqueLoja($code, $ean, $cod_loja, $qtd) {
        $query = $this->db->get_where('products_estoque_lojas', ['code' => $code, 'cod_loja' => $cod_loja], 1);

        if ($query->num_rows() > 0) {
            $this->db->update('products_estoque_lojas', ['quantity' => $qtd], ['code' => $code, 'cod_loja' => $cod_loja]);
        } else {
            $this->db->insert('products_estoque_lojas', ['code' => $code, 'ean' => $ean, 'cod_loja' => $cod_loja, 'quantity' => $qtd]);
        }
    }

    public function getVariants($id_produto) {
        $query = $this->db->get_where('tec_products_variants', ['id_produto' => $id_produto]);

        return $query->result();
    }

    public function addVariants($id_produto, $items) {
        
        //marca para remover 
        $this->db->update('tec_products_variants', ['quantity' => -101], ['id_produto' => $id_produto]);
        
        foreach ($items as $item) {

            $id = $item['id'];
            unset($item['id']);

            if ($id > 0) {
                $this->db->update('tec_products_variants', $item, ['id' => $id]);
            } else {
                $item['id_produto'] = $id_produto;
                $this->db->insert('tec_products_variants', $item);
            }
        }
        
        $this->db->delete('tec_products_variants', ['id_produto' => $id_produto, 'quantity' => -101]);
    }

}
