<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajusteestoque_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllLoja($date_start, $date_end) {
        $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) AS username "
                . "FROM tec_ajuste_estoque a, tec_users u "
                . "WHERE createdAt > '$date_start 00:00:00' "
                . "AND createdAt < '$date_end 23:59:59' "
                . "AND u.id=a.createdBy "
                . "ORDER BY createdAt DESC";
        
        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function getByLoja($cod_loja, $date_start, $date_end) {
        $sql = "SELECT a.*, CONCAT(u.first_name, ' ', u.last_name) AS username "
                . "FROM tec_ajuste_estoque a, tec_users u "
                . "WHERE a.cod_loja = '$cod_loja' "
                . "AND createdAt > '$date_start 00:00:00' "
                . "AND createdAt < '$date_end 23:59:59' "
                . "AND u.id=a.createdBy "
                . "ORDER BY createdAt DESC";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function insert($data = array()) {
        if ($this->db->insert('ajuste_estoque', $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    public function getForSync($cod_loja) {

        $this->db->where(['cod_loja' => $cod_loja, 'sync_time' => 0]);

        $query = $this->db->get('ajuste_estoque');

        return $query->result();
    }

    public function confirmaAjuste($id, $cod_loja) {

        $this->db->where(['id' => $id, 'cod_loja' => $cod_loja]);

        $this->db->set('sync_time', time());

        $this->db->update('ajuste_estoque');
    }

}
