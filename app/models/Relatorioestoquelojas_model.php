<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Relatorioestoquelojas_model extends CI_Model
{

    private $tabela = 'relatorio_estoque_lojas';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllRelatorios()
    {
        $this->db->select('*');
        $q = $this->db->get($this->tabela);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function relatorios_count($id = NULL)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->count_all_results($this->tabela);
        } else {
            return $this->db->count_all($this->tabela);
        }
    }

    public function fetch_relatorios($limit, $start, $id = NULL)
    {
        $this->db->select('*')
            ->limit($limit, $start)->order_by("data", "asc");
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


    public function getRelatorioById($id)
    {
        $q = $this->db->get_where($this->tabela, array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getRelatorioBy($where)
    {
        $q = $this->db->get_where($this->tabela, $where);
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function addRelatorio($data)
    {
        if ($this->db->insert($this->tabela, $data)) {
            return true;
        }
        return false;
    }


    public function updateRelatorio($id, $data = array())
    {

        if ($this->db->update($this->tabela, $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteRelatorio($id)
    {
        if ($this->db->delete($this->tabela, array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getBy($where, $one = true)
    {
        $q = $this->db->get_where($this->tabela, $where);
        if ($q->num_rows() > 0) {

            if ($one) {
                return $q->row();
            }

            return $q->result();
        }
        return FALSE;
    }
}
