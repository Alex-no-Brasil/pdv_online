<?php

class Acabamentos_model extends CI_Model {

    private $table = 'oficina_acabamentos';

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

    public function entrega_acabamento($andamento_id) {

        $query = $this->db->get_where('oficina_andamento', ['id' => $andamento_id], 1);

        if (!$query->num_rows() == 1) {
            return;
        }

        $andamento = $query->row();

        $update = [];

        $sql1 = "SELECT COUNT(1) AS entregas, SUM(media_acabamento) AS soma_media "
                . "FROM tec_oficina_andamento "
                . "WHERE acabamento_id = $andamento->acabamento_id AND data_acabamento_chegada IS NOT NULL GROUP BY acabamento_id";

        $query1 = $this->db->query($sql1);

        if ($query1->num_rows() == 1) {

            $row = $query1->row();

            $update['qtd_entregas'] = $row->entregas;

            $update['media'] = intval($row->soma_media / $row->entregas);

            $update['nivel'] = nivel_acabamento($update['media']);
        }

        $sql2 = "SELECT COUNT(1) AS atrasos "
                . "FROM tec_oficina_andamento "
                . "WHERE acabamento_id = $andamento->acabamento_id AND media_acabamento >= 4 "
                . "GROUP BY acabamento_id";

        $query2 = $this->db->query($sql2);

        if ($query2->num_rows() == 1) {

            $row = $query2->row();

            $update['qtd_atrasos'] = $row->atrasos;
        }

        $this->db->update($this->table, $update, ['id' => $andamento->acabamento_id]);
    }
    
    public function delete($id) {
        
        $this->db->delete($this->table, ['id' => $id]);        
    }

}
