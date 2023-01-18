<?php

class Resumocusto_model extends CI_Model {

    private $table = 'oficina_resumo_custo';

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

        return $this->db->insert($this->table, $dados);
    }

    public function update($id, $dados) {

        return $this->db->update($this->table, $dados, ['id' => $id]);
    }

    public function lista() {

        $sql = "SELECT rs.*, "
                . "of.nome AS oficina_nome, ad.cod_produto, pc.arq_mostruario, ad.qtd_pecas, ad.qtd_cortes, "
                . "ad.preco_unit AS valor_oficina, ad.valor_acabamento FROM "
                . "tec_oficina_resumo_custo rs, tec_oficina_andamento ad, tec_oficina_piloto_cortes pc, tec_oficinas of WHERE "
                . "ad.piloto_corte_id = rs.piloto_corte_id AND pc.id = rs.piloto_corte_id AND of.id = ad.oficina_id ORDER BY id DESC";
        
        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function delete($id) {
        
        $this->db->delete($this->table, ['id' => $id]);        
    }

}
