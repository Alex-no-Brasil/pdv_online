<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Depositoprodutoentradas_model extends CI_Model
{

    private $tabela = 'deposito_produto_entradas';

    public function __construct()
    {
        parent::__construct();
    }

  
    public function getAll() {
        $this->db->select('id,cod,nome,obs,tipo');
        $q = $this->db->get($this->tabela);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }    

    public function registros_count($id = NULL) {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->count_all_results($this->tabela);
        } else {
            return $this->db->count_all($this->tabela);
        }
    }

    public function fetch_registros($limit, $start, $id = NULL) {
        $this->db->select('*')
        ->limit($limit, $start)->order_by("cod", "asc");
        if ($id) {
            $this->db->where('id', $id);
        }
        $q = $this->db->get($this->tabela);

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getByCod($cod) {
        $q = $this->db->get_where($this->tabela, array('cod' => $cod), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getByCodAndToken($cod, $token) {
        $q = $this->db->get_where($this->tabela, array('cod' => $cod, 'token' => $token), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getById($id) {
        $q = $this->db->get_where($this->tabela, array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBy($where) {
        $q = $this->db->get_where($this->tabela, $where);
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }


    public function add($data) {
        if ($this->db->insert($this->tabela, $data)) {           
            return true;
        }
        return false;
    }
   

    public function update($id, $data = array()) {
       
        if ($this->db->update($this->tabela, $data, array('id' => $id))) {      
            return true;
        }
        return false;
    }   

    public function delete($id) {
        if ($this->db->delete($this->tabela, array('id' => $id))) {
            return true;
        }
        return FALSE;
    } 

    public function getTotalEntradas(){

        $this->db->select('tec_products.name, SUM(qtd) as qtd_total_entradas');
        $this->db->from($this->tabela);
        $this->db->join('tec_products ', 'tec_products.code = tec_deposito_produto_entradas.cod_produto', 'INNER');
        $this->db->group_by('cod_produto');

        $q = $this->db->get();
        return $q->result();        

    }
}


