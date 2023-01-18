<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NotaFiscal extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('empresa_model');
        $this->load->model('cliente_model');
        $this->load->model('transportadora_model');
        $this->load->model('estoque_nota_model');
        $this->load->model('chave_nota_model');
        $this->load->model('produto_nota_model');
        $this->load->model('nfce_model');
    }

    public function empresa() {

        $bc = array(array('link' => '#', 'page' => lang('Nota Fiscal')), array('link' => '#', 'page' => lang('Empresas'))); //rota na página

        $meta = array('page_title' => lang('Empresas'), 'bc' => $bc); //título na página

        $cadastros = $this->empresa_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('nota_fiscal/empresa', $this->data, $meta); //rota
    }

    public function modal_cadastro_empresa($id = null) {

        $empresa = [
            'id' => '',
            'nome' => '',
            'razaoSocial' => '',
            'cnpj' => '',
            'ie' => '',
            'endereco' => '',
            'numero' => '',
            'complemento' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'cep' => '',
            'telefone' => '',
            'certArquivo' => '',
            'certSenha' => '',
            'serie_danf' => '',
            'numero_danf' => '',
            'serie_nfce' => '',
            'numero_nfce' => '',
            'token_nfce' => '',
            'id_token_nfce' => ''
        ];

        if ($id > 0) {
            $empresa = get_object_vars($this->empresa_model->select($id));
        }

        $this->load->view($this->theme . 'nota_fiscal/modal_cadastro_empresa', $empresa);
    }

    public function salvar_empresa() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'id',
            'nome',
            'razaoSocial',
            'cnpj',
            'ie',
            'endereco',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
            'cep',
            'telefone',
            'certArquivo',
            'certSenha',
            'serie_danf',
            'numero_danf',
            'serie_nfce',
            'numero_nfce',
            'token_nfce',
            'id_token_nfce'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->empresa_model->insert($dados);

            $this->session->set_flashdata('message', 'Empresa cadastrada');

            redirect('notaFiscal/empresa');
        }

        $this->empresa_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Empresa atualizada');

        redirect('notaFiscal/empresa');
    }

    public function empresa_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->empresa_model->delete($id);

        exit('Ok');
    }

    public function cliente() {

        $bc = array(array('link' => '#', 'page' => lang('Nota Fiscal')), array('link' => '#', 'page' => lang('Clientes'))); //rota na página

        $meta = array('page_title' => lang('Clientes'), 'bc' => $bc); //título na página

        $cadastros = $this->cliente_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('nota_fiscal/cliente', $this->data, $meta); //rota
    }

    public function modal_cadastro_cliente($id = null) {

        $cliente = [
            'id' => '',
            'nome' => '',
            'tipo' => '',
            'cpf_cnpj' => '',
            'ie' => '',
            'endereco' => '',
            'numero' => '',
            'complemento' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'cep' => '',
            'telefone' => ''
        ];

        if ($id > 0) {
            $cliente = get_object_vars($this->cliente_model->select($id));
        }

        $this->load->view($this->theme . 'nota_fiscal/modal_cadastro_cliente', $cliente);
    }

    public function salvar_cliente() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'id',
            'nome',
            'tipo',
            'cpf_cnpj',
            'ie',
            'endereco',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
            'cep',
            'telefone'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->cliente_model->insert($dados);

            $this->session->set_flashdata('message', 'Cliente cadastrado');

            redirect('notaFiscal/cliente');
        }

        $this->cliente_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Cliente atualizado');

        redirect('notaFiscal/cliente');
    }

    public function cliente_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->cliente_model->delete($id);

        exit('Ok');
    }

    public function transportadora() {

        $bc = array(array('link' => '#', 'page' => lang('Nota Fiscal')), array('link' => '#', 'page' => lang('Transportadora'))); //rota na página

        $meta = array('page_title' => lang('Transportadora'), 'bc' => $bc); //título na página

        $cadastros = $this->transportadora_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('nota_fiscal/transportadora', $this->data, $meta); //rota
    }

    public function modal_cadastro_transportadora($id = null) {

        $transportadora = [
            'id' => '',
            'razaoSocial' => '',
            'cnpj' => '',
            'endereco' => '',
            'numero' => '',
            'complemento' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'cep' => '',
            'telefone' => ''
        ];

        if ($id > 0) {
            $transportadora = get_object_vars($this->transportadora_model->select($id));
        }

        $this->load->view($this->theme . 'nota_fiscal/modal_cadastro_transportadora', $transportadora);
    }

    public function salvar_transportadora() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'id',
            'razaoSocial',
            'cnpj',
            'endereco',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
            'cep',
            'telefone'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->transportadora_model->insert($dados);

            $this->session->set_flashdata('message', 'Transportadora cadastrada');

            redirect('notaFiscal/transportadora');
        }

        $this->transportadora_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Transportadora atualizada');

        redirect('notaFiscal/transportadora');
    }

    public function transportadora_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->transportadora_model->delete($id);

        exit('Ok');
    }

    public function estoque() {

        $bc = array(array('link' => '#', 'page' => lang('Nota Fiscal')), array('link' => '#', 'page' => lang('Estoque'))); //rota na página

        $meta = array('page_title' => lang('Estoque'), 'bc' => $bc); //título na página

        $cadastros = $this->estoque_nota_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('nota_fiscal/estoque', $this->data, $meta); //rota
    }

    public function modal_cadastro_estoque($id = null) {

        $estoque = [
            'origem' => '',
        ];

        $this->load->view($this->theme . 'nota_fiscal/modal_cadastro_estoque', $estoque);
    }

    public function salvar_estoque() {

        $post = $this->input->post();

        $origem = $post['origem'];

        $xml = $_FILES['xml']['tmp_name'];

        $procNfe = simplexml_load_file($xml);

        foreach ($procNfe->protNFe->infProt as $item) {

            $chave = [
                'chnfe' => trim((string) $item->chNFe),
                'data' => (string) $item->dhRecbto
            ];

            print_r($chave);

            $listagem_chave = $this->chave_nota_model->lista_chave($chave['chnfe']);

            if ($listagem_chave) {
                $this->session->set_flashdata('error', 'NFe já foi cadastrada!');
                redirect('notaFiscal/estoque');
            } 
            
            else {
                $this->chave_nota_model->insert($chave);

                foreach ($procNfe->NFe->infNFe->det as $item) {

                    $prods = [
                        'codigo' => trim((string) $item->prod->cProd),
                        'descricao' => (string) $item->prod->xProd,
                        'ncm' => (string) $item->prod->NCM,
                        'unidade' => (string) $item->prod->uCom,
                        'quantidade' => (string) $item->prod->qCom,
                        'valor_unt' => (string) $item->prod->vUnCom,
                        'valor_total' => (string) $item->prod->vProd,
                        'origem' => $origem
                    ];

                    print_r($prods);

                    $listagem = $this->estoque_nota_model->lista_cod($prods['codigo']);

                    if ($listagem) {
                        $prods['quantidade'] += $listagem->quantidade;
                        $prods['valor_total'] = $prods['quantidade'] * $prods['valor_unt'];
                        $this->estoque_nota_model->update($listagem->id, $prods);
                    } else {
                        $this->estoque_nota_model->insert($prods);
                    }
                }

                $this->session->set_flashdata('message', 'NFe cadastrada');
                redirect('notaFiscal/estoque');
            }
        }
    }
    
    public function nfce() {

        $bc = array(array('link' => '#', 'page' => lang('Nota Fiscal')), array('link' => '#', 'page' => lang('NFC-e'))); //rota na página

        $meta = array('page_title' => lang('NFC-e'), 'bc' => $bc); //título na página

        $cadastros = $this->nfce_model->lista();

        $this->data['cadastros'] = $cadastros;
        
        $empresas = $this->empresa_model->lista();
        foreach ($empresas as $empresa) {
            $this->data['empresas'][$empresa->id] = $empresa->nome;
        }
        
        $clientes = $this->cliente_model->lista();
        foreach ($clientes as $cliente) {
            $this->data['clientes'][$cliente->id] = $cliente->nome;
        }

        $this->page_construct('nota_fiscal/nfce', $this->data, $meta); //rota
    }
    
    public function salvar_nfce(){
        $this->load->model('nfce_model');
        
        $post = $this->input->post();
        
        $campos = [
            'id',
            'id_empresa',
            'id_cliente',
            'status'
        ];
        
        $dados = [];
        
        foreach ($post as $campo =>$valor){
            $dados[$campo] = trim($valor);
        }
        
        $this->nfce_model->insert($dados);
        
        redirect('notaFiscal/nfce');
    }
    
    /*public function modal_cadastro_nfce($id = null) {

        $nfce = [
            'id' => '',
            'id_empresa' => '',
            'id_cliente' => '',
            'status' => ''
        ];

        if ($id > 0) {
            $nfce = get_object_vars($this->nfce_model->select($id));
        }

        $this->load->view($this->theme . 'nota_fiscal/modal_cadastro_nfce', $nfce);
    }
    
    public function salvar_nfce() {

        
        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'id',
            'id_empresa',
            'id_cliente',
            'status'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->nfce_model->insert($dados);

            $this->session->set_flashdata('message', 'NFC-e gerado, cadastre produtos para finalizar!');

            redirect('notaFiscal/nfce');
        }

        $this->nfce_model->update($id, $dados);

        $this->session->set_flashdata('message', 'NFC-e atualizada');

        redirect('notaFiscal/nfce');
    }*/
    
    public function nfce_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->nfce_model->delete($id);

        exit('Ok');
    }
   
}
