<?php

class Pilotocorte_model extends CI_Model {

    private $table = 'oficina_piloto_cortes';

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

    public function lista($data_inicio, $data_fim) {

        $sql = "SELECT * FROM tec_$this->table WHERE data_pedido >= '$data_inicio' AND data_pedido <= '$data_fim'";
        
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function lista_cortado() {

        $sql = "SELECT * FROM tec_$this->table WHERE data_corte IS NOT NULL";
        
        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function delete($id) {
        
        $this->db->delete($this->table, ['id' => $id]);        
    }
}
