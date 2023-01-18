<?php

class Cortador_model extends CI_Model {

    private $table = 'oficina_cortador';

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

        $query = $this->db->get($this->table);

        return $query->result();
    }
    
    public function delete($id) {
        
        $this->db->delete($this->table, ['id' => $id]);        
    }
}
