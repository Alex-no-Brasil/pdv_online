<?php

class Alteracaopreco_model extends CI_Model {

    private $table = 'alteracao_preco';
    
    private $table_lojas = 'alteracao_preco_lojas';

    public function __construct() {

        parent::__construct();
    }

    public function select($id) {

        $query = $this->db->get_where($this->table, ['id' => $id], 1);

        if ($query->num_rows() == 1) {
            return $query->row();
        }

        return;
    }

    public function insert($dados) {

        $this->db->insert($this->table, $dados);
        
        return $this->db->insert_id();
    }
    
    public function insert_lojas($dados) {

        $this->db->insert($this->table_lojas, $dados);
        
        return $this->db->insert_id();
    }

    public function update($id, $dados) {

        return $this->db->update($this->table, $dados, ['id' => $id]);
    }
    
    public function delete($id) {
        
        $this->db->delete($this->table, ['id' => $id]);
        
        $this->db->delete($this->table_lojas, ['cod_alteracao' => $id]);
    }

    public function lista() {

        $sql = "SELECT alt.*, cat.name as categoria "
                . "FROM tec_$this->table alt, tec_products prod, tec_categories cat "
                . "WHERE prod.code = alt.cod_produto and cat.id = prod.category_id order by data_criada desc";
        
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function lista_lojas($cod_alteracao) {
        
        $query = $this->db->get_where($this->table_lojas, ['cod_alteracao' => $cod_alteracao]);
        
        return $query->result();
    }
    
    public function alteracoes_loja($cod_loja) {
        
        $sql = "SELECT alt.*, loj.data_confirma "
                . "FROM tec_$this->table alt, tec_$this->table_lojas loj "
                . "WHERE loj.cod_loja = '$cod_loja' and alt.id = loj.cod_alteracao and alt.data_aprovacao IS NOT NULL order by data_criada desc";
        
        $query = $this->db->query($sql);

        return $query->result();
        
    }
    
    public function confirma_alteracao_loja($cod_alteracao, $cod_loja) {
        
        $where = ['cod_loja' => $cod_loja, 'cod_alteracao' => $cod_alteracao];
        
        $update = ['data_confirma' => date('Y-m-d H:i:s')];
        
        $this->db->update($this->table_lojas, $update, $where);
    }
}
