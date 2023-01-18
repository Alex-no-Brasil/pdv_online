<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Depositoproduto_model extends CI_Model
{

    private $tabela = 'deposito_produto';

    public function __construct()
    {
        parent::__construct();
    }

    public function updateEstoqueDeposito($dados)
    {

        $this->db->select('*');
        $this->db->from($this->tabela);

        $this->db->where('cod_loja', $dados['cod_loja']);
        $this->db->where('cod_produto', $dados['cod_produto']);

        $q = $this->db->get();

        if ($q->num_rows() == 1) {

            return $this->editEstoqueDeposito($dados['cod_loja'], $dados['cod_produto'], ['qtd' => $dados['qtd']]);
        } else {

            return $this->addEstoqueProduto($dados);
        }
    }

    public function getBy($where, $one = true)
    {
        $q = $this->db->get_where($this->tabela, $where);
        if ($q->num_rows() > 0) {

            if($one){
                return $q->row();
            }

            return $q->result();
        }
        return FALSE;
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


    public function getRelatoriosNaoRealizadosByLoja($cod)
    {

        $this->db->select('*');
        $this->db->from($this->tabela);

        $sql = "SELECT id_relatorio_estoque 
        from tec_relatorio_estoque_lojas 
        WHERE id_relatorio_estoque = tec_relatorio_estoque.id 
        AND tec_relatorio_estoque_lojas.cod_loja = '$cod'";

        $this->db->where("tec_relatorio_estoque.id NOT IN ($sql) ");
        $q = $this->db->get();

        return $q->result();
    }

    public function getEstoqueByCod($cod_produto)
    {
        $this->db->select("tec_lojas.nome, tec_lojas.tipo, tec_lojas.cod, tec_products.code, tec_products.name, tec_deposito_produto.qtd");
        
        $this->db->from($this->tabela);
        
        $this->db->join('tec_lojas', 'tec_lojas.cod = tec_deposito_produto.cod_loja', 'INNER');
        
        $this->db->join('tec_products ', 'tec_products.code = tec_deposito_produto.cod_produto', 'INNER');

        $this->db->where('cod_produto', $cod_produto);
        
        $this->db->where('tec_lojas.tipo', 'DEPOSITO');

        $query = $this->db->get();

        return $query->result();
    }

    public function getAllEstoque()
    {
        $this->db->select("tec_deposito_produto.cod_loja, tec_products.name, tec_deposito_produto.qtd");
        
        $this->db->from($this->tabela);
        
        $this->db->join('tec_products ', 'tec_products.code = tec_deposito_produto.cod_produto', 'INNER');     

        $query = $this->db->get();

        return $query->result();
    }


    public function getRelatorioById($id)
    {
        $q = $this->db->get_where($this->tabela, array('id' => $id), 1);
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        
        return FALSE;
    }

    public function addEstoqueProduto($data)
    {
        if ($this->db->insert($this->tabela, $data)) {
            return true;
        }
        return false;
    }


    public function editEstoqueDeposito($cod_loja, $cod_produto, $data = array())
    {

        if ($this->db->update($this->tabela, $data, array('cod_loja' => $cod_loja, 'cod_produto' => $cod_produto))) {
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

    public function getDepositoEstoque($cod_loja, $cod_produto)
    {
       
        $q = $this->db->get_where($this->tabela, array('cod_loja' => $cod_loja, 'cod_produto' => $cod_produto), 1);
        
        if ($q->num_rows() > 0) {

            $res = $q->row();
          
            return $res->qtd;
        }
     
        return 0;
    }
}