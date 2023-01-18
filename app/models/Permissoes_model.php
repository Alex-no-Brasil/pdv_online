<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Permissoes_model extends CI_Model {

    private $table = 'permissoes';

    public function __construct() {
        parent::__construct();
    }

    public function lista($usuario_id) {

        $query = $this->db->get_where($this->table, ['usuario_id' => $usuario_id]);

        return $query->result();
    }

    public function atualiza($usuario_id, $permissoes) {

        $this->db->delete($this->table, ['usuario_id' => $usuario_id]);

        foreach ($permissoes as $rota) {

            $this->db->insert($this->table, ['usuario_id' => $usuario_id, 'rota' => $rota]);
        }
    }

}
