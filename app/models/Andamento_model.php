<?php

class Andamento_model extends CI_Model {

    private $table = 'oficina_andamento';

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

        $sql = "SELECT an.*, of.nome AS oficina_nome, pc.arq_mostruario, pc.cod_corte "
                . "FROM tec_oficina_andamento an, tec_oficinas of, tec_oficina_piloto_cortes pc WHERE "
                . "an.oficina_id = of.id AND an.piloto_corte_id = pc.id";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function lista_finalizado() {

        $sql = "SELECT an.*, of.nome AS oficina_nome, pc.arq_mostruario, pc.cod_corte "
                . "FROM tec_oficina_andamento an, tec_oficinas of, tec_oficina_piloto_cortes pc WHERE "
                . "an.oficina_id = of.id AND an.piloto_corte_id = pc.id AND data_acabamento_chegada IS NOT NULL";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function lista_corte_pronto($data_inicio, $data_fim) {

        $sql = "SELECT an.*, of.nome AS oficina_nome, pc.arq_mostruario, pc.cod_corte "
                . "FROM tec_oficina_andamento an, tec_oficinas of, tec_oficina_piloto_cortes pc WHERE "
                . "an.data_envio >= '$data_inicio' AND an.data_envio <= '$data_fim' AND "
                . "an.oficina_id = of.id AND an.piloto_corte_id = pc.id AND data_paga_oficina IS NOT NULL AND data_paga_acabamento IS NOT NULL";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function lista_pendente($data_inicio, $data_fim) {

        $sql = "SELECT an.*, of.nome AS oficina_nome, pc.arq_mostruario, pc.cod_corte "
                . "FROM tec_oficina_andamento an, tec_oficinas of, tec_oficina_piloto_cortes pc WHERE "
                . "((an.data_envio >= '$data_inicio' AND an.data_envio <= '$data_fim') OR data_envio IS NULL) AND "
                . "an.oficina_id = of.id AND an.piloto_corte_id = pc.id AND (data_paga_oficina IS NULL OR data_paga_acabamento IS NULL)";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function corte_andamento($piloto_corte_id) {

        $query = $this->db->get_where($this->table, ['piloto_corte_id' => $piloto_corte_id], 1);

        if ($query->num_rows() == 1) {
            return true;
        }

        return false;
    }
    
    public function delete($id) {
        
        $this->db->delete($this->table, ['id' => $id]);        
    }
}
