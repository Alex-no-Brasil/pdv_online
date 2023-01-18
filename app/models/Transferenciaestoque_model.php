<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transferenciaestoque_model extends CI_Model
{

    private $tabela = 'transferencia_estoque';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllLojas()
    {
        $q = $this->db->get($this->tabela);
        
        if ($q->num_rows() > 0) {
            
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            
            return $data;
        }
        
        return false;
    }

    public function lojas_count($id = NULL)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->count_all_results($this->tabela);
        } else {
            return $this->db->count_all($this->tabela);
        }
    }

    public function fetch_lojas($limit, $start, $id = NULL)
    {
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

    public function getLojaByCod($cod)
    {
        $q = $this->db->get_where($this->tabela, array('cod' => $cod), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getLojaById($id)
    {
        $q = $this->db->get_where($this->tabela, array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getBy($colunas, $cond, $limit = false, $start = 0, $order = false)
    {

        $this->db->select($colunas);
        $this->db->from($this->tabela);
        $this->db->where($cond);

        if ($limit) {
            $this->db->limit($limit, $start);
        }

        if ($order) {

            $this->db->order_by($order);
        }

        $query = $this->db->get();
        return $query->row();
    }

    public function get($colunas, $cond, $limit = false, $start = 0, $order = false)
    {

        $this->db->select($colunas);
        $this->db->from($this->tabela);
        $this->db->where($cond);

        if ($limit) {
            $this->db->limit($limit, $start);
        }

        if ($order) {

            $this->db->order_by($order);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function getTransferencias($colunas, $cond, $limit = false, $start = 0, $order = false)
    {
        
        //$this->db->save_queries = TRUE;
        $this->db->select($colunas);
        $this->db->from($this->tabela);
        $this->db->join('products', 'products.code = transferencia_estoque.cod_produto');
        $this->db->join('categories', 'categories.id = products.category_id');
        $this->db->where($cond);

        if ($limit) {
            $this->db->limit($limit, $start);
        }

        if ($order) {

            $this->db->order_by($order);
        }

        $query = $this->db->get();
        

        return $query->result();
    }

    public function getTransferenciasDepositos($colunas, $cond, $limit = false, $start = 0, $order = false, $qual = "origem")
    {
        
        //$this->db->save_queries = TRUE;
        $this->db->select($colunas);
        $this->db->from($this->tabela);
        $this->db->join('products', 'products.code = transferencia_estoque.cod_produto');
        $this->db->join('categories', 'categories.id = products.category_id');             
        $this->db->join('lojas as lo', "lo.cod = transferencia_estoque.cod_loja_origem");             
        $this->db->join('lojas as ld', "ld.cod = transferencia_estoque.cod_loja_destino");             
        $this->db->where($cond);
        $this->db->where("transferencia_estoque.cod_loja_$qual IN (SELECT cod from tec_lojas WHERE tipo = 'DEPOSITO')");

        if ($limit) {
            $this->db->limit($limit, $start);
        }

        if ($order) {

            $this->db->order_by($order);
        }

        $query = $this->db->get();
        

        return $query->result();
    }

    public function getTotalRecebidasByLoja($cod, $status = false)
    {
        $this->db->select("COUNT(*) as num");
        $this->db->from($this->tabela);
        $this->db->where(['cod_loja_destino' => $cod]);
        if ($status) {
            $this->db->where(['status' => $status]);
        }

        $query = $this->db->get();

        $result = $query->row();
        if (isset($result))
            return $result->num;
        return 0;
    }

    public function getTotaisRecebidasDepositos($status = false)
    {
        $this->db->select("COUNT(*) as num");
        $this->db->from($this->tabela);
        $this->db->join('lojas', 'lojas.cod = transferencia_estoque.cod_loja_destino', 'INNER');
        $this->db->where(['lojas.tipo' =>'DEPOSITO']);
        if ($status) {
            $this->db->where(['status' => $status]);
        }

        $query = $this->db->get();
       
        $result = $query->row();
        if (isset($result))
            return $result->num;
        return 0;
    }

    public function getTotais($where)
    {
        $this->db->select("COUNT(*) as num");
        $this->db->from($this->tabela);
        $this->db->where($where);
       
        $query = $this->db->get();

        $result = $query->row();
        if (isset($result))
            return $result->num;
        return 0;
    }

    public function getTotaisDepositos($where, $qual = "origem")
    {
        $this->db->select("COUNT(*) as num");
        $this->db->from($this->tabela);
        $this->db->where($where);
        $this->db->where("transferencia_estoque.cod_loja_$qual IN (SELECT cod from tec_lojas WHERE tipo = 'DEPOSITO')");
       
        $query = $this->db->get();

        $result = $query->row();
        if (isset($result))
            return $result->num;
        return 0;
    }

    public function add($data)
    {
        if ($this->db->insert($this->tabela, $data)) {
            return $this->db->insert_id();
        }

        _logErro(__FILE__, __FUNCTION__ . " (Linha: " . __LINE__ . ")", __LINE__, json_encode($this->db->error()));
        return false;
    }


    public function edt($id, $data = array())
    {

        if ($this->db->update($this->tabela, $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        if ($this->db->delete($this->tabela, array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteMulti($arrIds)
    {
        if ($this->db->delete($this->tabela, "id IN (".implode(",",$arrIds).")")) {
            return true;
        }
        return FALSE;
    }
}
