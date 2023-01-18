<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produtosedicoes_model extends CI_Model
{

    private $tabela = 'produtos_edicoes';

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllProdutosEdicoes()
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


    public function pedicoes_count($id = NULL)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->count_all_results($this->tabela);
        } else {
            return $this->db->count_all($this->tabela);
        }
    }

    public function fetch_pedicoes($limit, $start, $id = NULL)
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


    public function getProdutosEdicoesNaoRealizadosByLoja($cod, $limit = 10)
    {

        $this->db->select('*');
        $this->db->from($this->tabela);

        $sql = "SELECT 	id_produtos_edicoes 
        from tec_produtos_edicoes_lojas 
        WHERE id_produtos_edicoes = tec_produtos_edicoes.id 
        AND tec_produtos_edicoes_lojas.cod_loja = '$cod'";

        $this->db->where("tec_produtos_edicoes.id NOT IN ($sql) ");
        
        $this->db->limit($limit);
        
        $q = $this->db->get();

        return $q->result();
    }


    public function getProdutoEdicaoById($id)
    {
        $q = $this->db->get_where($this->tabela, array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addProdutoEdicao($data)
    {
        if ($this->db->insert($this->tabela, $data)) {
            return true;
        }
        return false;
    }


    public function updateProdutoEdicao($id, $data = array())
    {

        if ($this->db->update($this->tabela, $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function deleteProdutoEdicao($id)
    {
        if ($this->db->delete($this->tabela, array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
}
