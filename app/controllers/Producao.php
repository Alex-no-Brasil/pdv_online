<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Producao extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('oficinas_model');

        $this->load->model('modelista_model');

        $this->load->model('piloteira_model');

        $this->load->model('cortador_model');

        $this->load->model('andamento_model');

        $this->load->model('acabamentos_model');

        $this->load->model('pilotocorte_model');

        $this->load->model('resumocusto_model');

        $this->load->model('ampliador_model');
    }

    public function cadastro_piloteira() {

        $bc = array(array('link' => '#', 'page' => lang('Produção')), array('link' => '#', 'page' => lang('Cadastro piloteira'))); //rota na página
        $meta = array('page_title' => lang('Cadastro piloteira'), 'bc' => $bc); //título na página

        $cadastros = $this->piloteira_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('producao/cadastro_piloteira', $this->data, $meta); //rota
    }

    public function modal_cadastro_piloteira($id = null) {

        $piloteira = [
            'id' => '',
            'nome' => '',
            'telefone' => '',
            'endereco' => '',
            'numero' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'complemento' => ''
        ];

        if ($id > 0) {
            $piloteira = get_object_vars($this->piloteira_model->select($id));
        }

        $this->load->view($this->theme . 'producao/modal_cadastro_piloteira', $piloteira);
    }

    public function salvar_piloteira() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'nome',
            'telefone',
            'endereco',
            'numero',
            'bairro',
            'cidade',
            'uf',
            'complemento'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->piloteira_model->insert($dados);

            $this->session->set_flashdata('message', 'Piloteira cadastrada');

            redirect('producao/cadastro_piloteira');
        }

        $this->piloteira_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Piloteira atualizada');

        redirect('producao/cadastro_piloteira');
    }

    public function piloteira_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->piloteira_model->delete($id);

        exit('Ok');
    }

    //Ampliador
    public function cadastro_ampliador() {

        $bc = array(array('link' => '#', 'page' => lang('Produção')), array('link' => '#', 'page' => lang('Cadastro ampliador'))); //rota na página
        $meta = array('page_title' => lang('Cadastro ampliador'), 'bc' => $bc); //título na página

        $cadastros = $this->ampliador_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('producao/cadastro_ampliador', $this->data, $meta); //rota
    }

    public function modal_cadastro_ampliador($id = null) {

        $ampliador = [
            'id' => '',
            'nome' => '',
            'telefone' => '',
            'endereco' => '',
            'numero' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'complemento' => ''
        ];

        if ($id > 0) {
            $ampliador = get_object_vars($this->ampliador_model->select($id));
        }

        $this->load->view($this->theme . 'producao/modal_cadastro_ampliador', $ampliador);
    }

    public function salvar_ampliador() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'nome',
            'telefone',
            'endereco',
            'numero',
            'bairro',
            'cidade',
            'uf',
            'complemento'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->ampliador_model->insert($dados);

            $this->session->set_flashdata('message', 'Ampliador(a) cadastrado(a)');

            redirect('producao/cadastro_ampliador');
        }

        $this->ampliador_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Ampliador atualizado(a)');

        redirect('producao/cadastro_ampliador');
    }

    public function ampliador_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->ampliador_model->delete($id);

        exit('Ok');
    }

    //Fim Ampliador

    public function cadastro_modelista() {

        $bc = array(array('link' => '#', 'page' => lang('Produção')), array('link' => '#', 'page' => lang('Cadastro modelista'))); //rota na página
        $meta = array('page_title' => lang('Cadastro modelista'), 'bc' => $bc); //título na página

        $cadastros = $this->modelista_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('producao/cadastro_modelista', $this->data, $meta); //rota
    }

    public function modal_cadastro_modelista($id = null) {

        $modelista = [
            'id' => '',
            'nome' => '',
            'telefone' => '',
            'endereco' => '',
            'numero' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'complemento' => ''
        ];

        if ($id > 0) {
            $modelista = get_object_vars($this->modelista_model->select($id));
        }

        $this->load->view($this->theme . 'producao/modal_cadastro_modelista', $modelista);
    }

    public function salvar_modelista() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'nome',
            'telefone',
            'endereco',
            'numero',
            'bairro',
            'cidade',
            'uf',
            'complemento'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->modelista_model->insert($dados);

            $this->session->set_flashdata('message', 'Modelista cadastrada');

            redirect('producao/cadastro_modelista');
        }

        $this->modelista_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Modelista atualizada');

        redirect('producao/cadastro_modelista');
    }

    public function modelista_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->modelista_model->delete($id);

        exit('Ok');
    }

    public function cadastro_cortador() {

        $bc = array(array('link' => '#', 'page' => lang('Produção')), array('link' => '#', 'page' => lang('Cadastro cortador'))); //rota na página
        $meta = array('page_title' => lang('Cadastro cortador'), 'bc' => $bc); //título na página

        $cadastros = $this->cortador_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('producao/cadastro_cortador', $this->data, $meta); //rota
    }

    public function modal_cadastro_cortador($id = null) {

        $cortador = [
            'id' => '',
            'nome' => '',
            'telefone' => '',
            'endereco' => '',
            'numero' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'complemento' => ''
        ];

        if ($id > 0) {
            $cortador = get_object_vars($this->cortador_model->select($id));
        }

        $this->load->view($this->theme . 'producao/modal_cadastro_cortador', $cortador);
    }

    public function salvar_cortador() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'nome',
            'telefone',
            'endereco',
            'numero',
            'bairro',
            'cidade',
            'uf',
            'complemento'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->cortador_model->insert($dados);

            $this->session->set_flashdata('message', 'Cortador cadastrado');

            redirect('producao/cadastro_cortador');
        }

        $this->cortador_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Cortador atualizado');

        redirect('producao/cadastro_cortador');
    }

    public function cortador_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->cortador_model->delete($id);

        exit('Ok');
    }

    public function cadastro_oficina() {

        $bc = array(array('link' => '#', 'page' => lang('Produção')), array('link' => '#', 'page' => lang('Cadastro oficina'))); //rota na página
        $meta = array('page_title' => lang('Cadastro oficina'), 'bc' => $bc); //título na página

        $oficinas = $this->oficinas_model->lista();

        $this->data['oficinas'] = $oficinas;

        $this->page_construct('producao/cadastro_oficina', $this->data, $meta); //rota
    }

    public function modal_cadastro_oficina($id = null) {

        $oficina = [
            'id' => '',
            'nome' => '',
            'prefixo' => '',
            'sequencia' => '',
            'telefone' => '',
            'cep' => '',
            'endereco' => '',
            'numero' => '',
            'complemento' => '',
            'bairro' => '',
            'cidade' => '',
            'uf' => '',
            'cpf_cnpj' => '',
            'banco_titular' => '',
            'banco_nome' => '',
            'banco_agencia' => '',
            'banco_conta' => '',
            'banco_conta_tipo' => '',
            'maq_retas' => '',
            'maq_overloque' => '',
            'maq_galoneira' => '',
            'maq_passadoria' => '',
            'maq_funcionarios' => '',
            'nota' => ''
        ];

        if ($id > 0) {
            $oficina = get_object_vars($this->oficinas_model->select($id));
        }


        $this->load->view($this->theme . 'producao/modal_cadastro_oficina', $oficina);
    }

    public function salvar_oficina() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'nome',
            'prefixo',
            'sequencia',
            'telefone',
            'endereco',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
            'cpf_cnpj',
            'banco_titular',
            'banco_nome',
            'banco_agencia',
            'banco_conta',
            'banco_conta_tipo',
            'maq_retas',
            'maq_overloque',
            'maq_galoneira',
            'maq_passadoria',
            'maq_funcionarios',
            'nota'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        $prefixo = $this->oficinas_model->select_where(['prefixo' => $dados['prefixo']]);

        if ($prefixo && $prefixo[0]->id != $id) {
            exit('Prefixo duplicado');
        }

        if (empty($id)) {

            $this->oficinas_model->insert($dados);

            $this->session->set_flashdata('message', 'Oficina cadastrada');

            exit('Ok');
        }

        $this->oficinas_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Oficina atualizada');

        exit('Ok');
    }

    public function oficina_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->oficinas_model->delete($id);

        exit('Ok');
    }

    public function cadastro_acabamento() {

        $bc = array(array('link' => '#', 'page' => lang('Produção')), array('link' => '#', 'page' => lang('Cadastro acabamento'))); //rota na página
        $meta = array('page_title' => lang('Cadastro acabamento'), 'bc' => $bc); //título na página
        $acabamentos = $this->acabamentos_model->lista();
        $this->data['acabamentos'] = $acabamentos;
        $this->page_construct('producao/cadastro_acabamento', $this->data, $meta); //rota
    }

    public function modal_cadastro_acabamento($id = null) {

        $acabamento = [
            'id' => '',
            'nome' => '',
            'telefone' => '',
            'endereco' => '',
            'numero' => '',
            'bairro' => '',
            'complemento' => '',
            'cidade' => '',
            'uf' => '',
            'cpf_cnpj' => '',
            'banco_titular' => '',
            'banco_nome' => '',
            'banco_agencia' => '',
            'banco_conta' => '',
            'banco_conta_tipo' => ''
        ];

        if ($id > 0) {
            $acabamento = get_object_vars($this->acabamentos_model->select($id));
        }


        $this->load->view($this->theme . 'producao/modal_cadastro_acabamento', $acabamento);
    }

    public function salvar_acabamento() {

        $post = $this->input->post();

        $id = $post['id'];

        $campos = [
            'nome',
            'telefone',
            'endereco',
            'numero',
            'bairro',
            'complemento',
            'cidade',
            'uf',
            'cpf_cnpj',
            'banco_titular',
            'banco_nome',
            'banco_agencia',
            'banco_conta',
            'banco_conta_tipo'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($id)) {

            $this->acabamentos_model->insert($dados);

            $this->session->set_flashdata('message', 'Acabamento cadastrado');

            redirect('producao/cadastro_acabamento');
        }

        $this->acabamentos_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Acabamento atualizado');

        redirect('producao/cadastro_acabamento');
    }
    
    public function acabamento_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->acabamentos_model->delete($id);

        exit('Ok');
    }

    public function resumo_custo() {

        $bc = array(array('link' => '#', 'page' => lang('Oficina produção')), array('link' => '#', 'page' => lang('Resumo custo'))); //rota na página
        $meta = array('page_title' => lang('Resumo custo'), 'bc' => $bc); //título na página

        $pilotocortes = $this->andamento_model->lista();

        foreach ($pilotocortes as $pilotocorte) {
            $this->data['pilotocortes'][$pilotocorte->piloto_corte_id] = $pilotocorte->cod_corte;
        }

        $cadastros = $this->resumocusto_model->lista();

        $this->data['cadastros'] = $cadastros;

        $this->page_construct('producao/resumo_custo', $this->data, $meta); //rota
    }

    public function modal_resumo_custo($id = null) {

        $resumo_custo = [
            'id' => '',
            'piloto_corte_id' => '',
            'tecido_preco' => '',
            'tecido_metro' => '',
            'acessorio_botao' => '',
            'acessorio_ziper' => '',
            'acessorio_intertela' => '',
            'acessorio_fivela' => '',
            'acessorio_cinto' => '',
            'acessorio_ombrera' => '',
            'acessorio_elastico' => '',
            'valor_corte' => '',
            'valor_modelagem' => ''
        ];

        if ($id > 0) {
            $resumo_custo = get_object_vars($this->resumocusto_model->select($id));
        }

        $pilotocortes = $this->andamento_model->lista_finalizado();

        foreach ($pilotocortes as $pilotocorte) {
            $resumo_custo['pilotocortes'][$pilotocorte->piloto_corte_id] = $pilotocorte->cod_corte;
        }

        $this->load->view($this->theme . 'producao/modal_resumo_custo', $resumo_custo);
    }

    public function resumo_custo_salvar() {

        $post = $this->input->post();

        if (empty($post['piloto_corte_id'])) {
            redirect('producao/resumo_custo');
        }

        $id = $post['id'];

        $campos = [
            'tecido_preco',
            'tecido_metro',
            'acessorio_botao',
            'acessorio_ziper',
            'acessorio_intertela',
            'acessorio_fivela',
            'acessorio_cinto',
            'acessorio_ombrera',
            'acessorio_elastico',
            'valor_corte',
            'valor_modelagem'
        ];

        $dados = [
            'piloto_corte_id' => $post['piloto_corte_id']
        ];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {

                $valor = str_replace(".", "", $valor);

                $valor = str_replace(",", ".", $valor);

                if (empty($valor)) {
                    $dados[$campo] = 0;
                } else {
                    $dados[$campo] = trim($valor);
                }
            }
        }

        $dados['tecido_total'] = $dados['tecido_preco'] * $dados['tecido_metro'];

        $dados['acessorio_total'] = $dados['acessorio_botao'] + $dados['acessorio_ziper'] + $dados['acessorio_intertela'] + $dados['acessorio_fivela'] +
                $dados['acessorio_cinto'] + $dados['acessorio_ombrera'] + $dados['acessorio_elastico'];

        if (empty($id)) {

            $this->resumocusto_model->insert($dados);

            $this->session->set_flashdata('message', 'Resumo cadastrado');

            redirect('producao/resumo_custo');
        }

        $this->resumocusto_model->update($id, $dados);

        $this->session->set_flashdata('message', 'Resumo atualizado');

        redirect('producao/resumo_custo');
    }

    public function resumo_custo_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $this->resumocusto_model->delete($id);

        exit('Ok');
    }

}
