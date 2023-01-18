<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Oficina extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }

        $this->load->model('pilotocorte_model');

        $this->load->model('categories_model');

        $this->load->model('oficinas_model');

        $this->load->model('modelista_model');

        $this->load->model('piloteira_model');

        $this->load->model('cortador_model');

        $this->load->model('andamento_model');

        $this->load->model('acabamentos_model');
        
        $this->load->model('ampliador_model');
        
        $this->load->model('oficinarelatorio_model');
    }

    public function piloto_e_corte() {

        $bc = array(array('link' => '#', 'page' => lang('Oficina')), array('link' => '#', 'page' => lang('Piloto e corte'))); //rota na página
        $meta = array('page_title' => lang('Piloto e corte'), 'bc' => $bc); //título na página

        $inicio = strtotime("-30 days");
        
        $fim = time();
        
        if ($this->input->get('inicio')) {
            $inicio = $this->input->get('inicio') / 1000;
            $fim = $this->input->get('fim') / 1000;
        }
        
        $this->data['date_start'] = $inicio * 1000;
        $this->data['date_end'] = $fim * 1000;
        
        $filtro_inicio = date('Y-m-d', $inicio);
        
        $filtro_fim = date('Y-m-d', $fim);
                
        $cadastros = $this->pilotocorte_model->lista($filtro_inicio, $filtro_fim);

        $this->data['cadastros'] = $cadastros;

        $categorias = $this->categories_model->findAll();

        foreach ($categorias as $categoria) {
            $this->data['categorias'][$categoria->id] = $categoria->name;
        }

        $modelistas = $this->modelista_model->lista();

        foreach ($modelistas as $modelista) {
            $this->data['modelistas'][$modelista->id] = $modelista->nome;
        }

        $piloteiras = $this->piloteira_model->lista();
        foreach ($piloteiras as $piloteira) {
            $this->data['piloteiras'][$piloteira->id] = $piloteira->nome;
        }

        $cortadores = $this->cortador_model->lista();
        foreach ($cortadores as $cortador) {
            $this->data['cortadores'][$cortador->id] = $cortador->nome;
        }
        
        $ampliadores = $this->ampliador_model->lista();
        foreach ($ampliadores as $ampliador) {
            $this->data['ampliadores'][$ampliador->id] = $ampliador->nome;
        }

        $this->page_construct('oficina/piloto_e_corte', $this->data, $meta); //rota
    }

    public function piloto_corte_cadastro() {

        $post = $this->input->post();

        if (empty($post['cod_corte'])) {
            exit;
        }
        
        if (empty($post['data_pedido'])) {
            exit;
        }
        
        if (empty($post['categoria_id'])) {
            exit;
        }

        $dados = [
            'cod_corte' => $post['cod_corte'],
            'data_pedido' => $post['data_pedido'],
            'categoria_id' => $post['categoria_id']
        ];
        
        if (!empty($post['arq_mostruario'])) {
            $dados['arq_mostruario'] = $post['arq_mostruario'];
        }

        if (empty($post['id'])) {
            $this->pilotocorte_model->insert($dados);
        } else {
            $this->pilotocorte_model->update($post['id'], $dados);
        }
    }

    public function piloto_corte_upload_mostruario() {

        $this->load->library('upload');

        $uploads = 'uploads/oficina/';

        if (!file_exists($uploads)) {
            mkdir($uploads);
        }

        $config['upload_path'] = $uploads;
        $config['allowed_types'] = array('gif', 'png', 'jpg', 'jpeg');
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            echo $this->upload->display_errors();
            exit;
        }

        echo site_url() . $uploads . $this->upload->file_name;
    }

    public function piloto_corte_upload_cad() {

        $this->load->library('upload');

        $uploads = 'uploads/oficina/';

        if (!file_exists($uploads)) {
            mkdir($uploads);
        }

        $config['upload_path'] = $uploads;
        $config['allowed_types'] = array('adsx', 'pdf');
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            echo $this->upload->display_errors();
            exit;
        }

        echo site_url() . $uploads . $this->upload->file_name;
    }

    public function piloto_corte_atualiza() {

        $post = $this->input->post();

        if (empty($post['id'])) {
            exit;
        }

        $campos = [
            'arq_mostruario',
            'data_cad',
            'arq_cad',
            'arq_cad2',
            'usuario_cad',
            'obs_cad',
            'usuario_cad2',
            'obs_cad2',
            'data_piloto',
            'resp_piloto',
            'obs_piloto',
            'resp_piloto2',
            'obs_piloto2',
            'usuario_ampliador',
            'data_ampliado',
            'arq_ampliado',
            'data_corte',
            'resp_corte'
            
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (isset($post['provado'])) {
            $dados['provado'] = strtotime($post['provado']);
        }

        if (isset($post['confirmado']) && $post['confirmado'] == "0") {
            $dados['confirmado'] = -1;
        }

        if (isset($post['confirmado']) && $post['confirmado'] == "1") {
            $dados['confirmado'] = time();
        }

        if (empty($dados)) {
            exit;
        }

        $this->pilotocorte_model->update($post['id'], $dados);

        if (!empty($dados['resp_corte'])) {
            $this->media_corte($post['id']);
        }

        if (!empty($dados['usuario_ampliador'])) {
            $this->media_ampliado($post['id']);
        }
    }

    public function piloto_corte_excluir() {

        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }

        $corte_andamento = $this->andamento_model->corte_andamento($id);

        if ($corte_andamento) {
            exit("Corte está em andamento!");
        }
        
        $this->pilotocorte_model->delete($id);
        
        exit('Ok');
    }

    private function media_ampliado($id) {

        $cadastro = $this->pilotocorte_model->select($id);

        if (!$cadastro) {
            return;
        }

        $data_pedido = strtotime($cadastro->data_pedido);

        $data_ampliado = strtotime($cadastro->data_ampliado);

        $media_ampliado = intval(($data_ampliado - $data_pedido) / 86400);

        $this->pilotocorte_model->update($id, ['media_ampliado' => $media_ampliado]);
    }

    private function media_corte($id) {

        $cadastro = $this->pilotocorte_model->select($id);

        if (!$cadastro) {
            return;
        }

        $data_pedido = strtotime($cadastro->data_pedido);
                
        $data_ampliado = strtotime($cadastro->data_ampliado);

        $data_corte = strtotime($cadastro->data_corte);

        $media_corte = intval(($data_corte - $data_ampliado) / 86400);

        $media_total = intval(($data_corte - $data_pedido) / 86400);

        $this->pilotocorte_model->update($id, ['media_corte' => $media_corte, 'media_total' => $media_total]);
    }

    //ANDAMENTO
    function andamento() {

        $bc = array(array('link' => '#', 'page' => lang('Oficina produção')), array('link' => '#', 'page' => lang('Andamento'))); //rota na página
        $meta = array('page_title' => lang('Andamento'), 'bc' => $bc); //título na página

        $inicio = strtotime("-30 days");
        
        $fim = time();
        
        if ($this->input->get('inicio')) {
            $inicio = $this->input->get('inicio') / 1000;
            $fim = $this->input->get('fim') / 1000;
        }
        
        $this->data['date_start'] = $inicio * 1000;
        $this->data['date_end'] = $fim * 1000;
        
        $filtro_inicio = date('Y-m-d', $inicio);
        
        $filtro_fim = date('Y-m-d', $fim);
        
        $cadastros = $this->andamento_model->lista_pendente($filtro_inicio, $filtro_fim);
        
        $this->data['cadastros'] = $cadastros;

        $pilotocortes = $this->pilotocorte_model->lista_cortado();
        foreach ($pilotocortes as $pilotocorte) {
            $this->data['pilotocortes'][$pilotocorte->id] = $pilotocorte->cod_corte;
        }

        $oficinas = $this->oficinas_model->lista();
        foreach ($oficinas as $oficina) {
            $this->data['oficinas'][$oficina->id] = $oficina->nome;
        }

        $acabamentos = $this->acabamentos_model->lista();
        foreach ($acabamentos as $acabamento) {
            $this->data['acabamentos'][$acabamento->id] = $acabamento->nome;
        }

        $this->page_construct('oficina/andamento', $this->data, $meta); //rota
    }

    public function andamento_salvar() {

        $this->load->model('products_model');

        $post = $this->input->post();

        $campos = [
            'oficina_id',
            'piloto_corte_id',
            'cod_produto',
            'qtd_pecas',
            'preco_unit'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        $preco_unit = str_replace(",", ".", $dados['preco_unit']);

        $dados['preco_unit'] = $preco_unit;

        $dados['qtd_cortes'] = $this->get_oficina_cortes($dados['oficina_id']);

        if (empty($dados['cod_produto'])) {
            $dados['cod_produto'] = $this->get_cod_produto($dados['oficina_id']);
        }

        /*$produto = $this->products_model->getByCode($dados['cod_produto']);

        if ($produto) {
            $this->session->set_flashdata('error', 'Código do produto duplicado: ' . $dados['cod_produto']);
            redirect('oficina/andamento');
        } 
         */

        if (!empty($post['id'])) {
            
            $this->andamento_model->update($post['id'], $dados);
            
            $this->session->set_flashdata('message', 'Andamento atualizado');

            redirect('oficina/andamento');
        }
        
        $this->andamento_model->insert($dados);

        $this->atualiza_oficina_cortes($dados['oficina_id']);

        $this->session->set_flashdata('message', 'Andamento cadastrado');

        redirect('oficina/andamento');
    }

    public function andamento_atualiza() {

        $post = $this->input->post();

        if (empty($post['id'])) {
            exit;
        }

        $campos = [
            'data_envio',
            'data_amostra',
            'data_recebimento',
            'data_chegada',
            'data_acabamento_envio',
            'data_acabamento_chegada',
            'qtd_boa',
            'qtd_defeito',
            'data_paga_oficina',
            'data_paga_acabamento'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (!empty($dados['data_chegada'])) {
            $dados['data_acabamento_envio'] = $dados['data_chegada'];
        }
        
        if (!empty($dados['data_paga_oficina'])) {

            $dados['data_paga_oficina'] = date('Y-m-d H:i:s');
        }

        if (!empty($dados['data_paga_acabamento'])) {

            $dados['data_paga_acabamento'] = date('Y-m-d H:i:s');
        }

        if (empty($dados)) {
            exit;
        }

        $this->andamento_model->update($post['id'], $dados);

        if (!empty($dados['data_chegada'])) {

            $this->media_oficina($post['id']);

            $this->oficinas_model->entrega_oficina($post['id']);
        }

        if (!empty($dados['data_acabamento_chegada'])) {

            $this->media_acabamento($post['id']);

            $this->acabamentos_model->entrega_acabamento($post['id']);
        }
    }
    
    public function andamento_excluir() {
        
        $id = $this->input->post('id');

        if (empty($id)) {
            return;
        }
        
        $this->andamento_model->delete($id);
    }

    public function acabamento_salvar() {

        $post = $this->input->post();

        if (empty($post['id'])) {
            redirect('oficina/andamento');
        }

        $campos = [
            'acabamento_id',
            'qtd_acabamento',
            'valor_acabamento',
            'cod_barra'
        ];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        $valor_acabamento = str_replace(".", "", $dados['valor_acabamento']);

        $valor_acabamento = str_replace(",", ".", $valor_acabamento);

        $dados['valor_acabamento'] = $valor_acabamento;

        $this->andamento_model->update($post['id'], $dados);

        $this->atualiza_acabamento_cortes($dados['acabamento_id']);

        redirect('oficina/andamento');
    }

    private function get_cod_produto($oficina_id) {

        $oficina = $this->oficinas_model->select($oficina_id);

        if (!$oficina) {
            return '';
        }

        $sequencia = $oficina->sequencia;

        if (!($sequencia > 1)) {
            $oficina->sequencia = 1;
            $sequencia = 1;
        }

        if ($sequencia < 10) {
            $sequencia = "0$sequencia";
        }

        $this->oficinas_model->update($oficina_id, ['sequencia' => $oficina->sequencia + 1]);

        return "$oficina->prefixo$sequencia";
    }

    private function get_oficina_cortes($oficina_id) {

        $oficina = $this->oficinas_model->select($oficina_id);

        if (!$oficina) {
            return 0;
        }

        return $oficina->qtd_cortes + 1;
    }

    private function atualiza_oficina_cortes($oficina_id) {

        $oficina = $this->oficinas_model->select($oficina_id);

        if (!$oficina) {
            return '';
        }

        $this->oficinas_model->update($oficina_id, ['qtd_cortes' => $oficina->qtd_cortes + 1]);
    }

    private function media_oficina($id) {

        $cadastro = $this->andamento_model->select($id);

        if (!$cadastro) {
            return;
        }

        $data_amostra = strtotime($cadastro->data_amostra);

        $data_recebimento = strtotime($cadastro->data_recebimento);

        $data_chegada = strtotime($cadastro->data_chegada);

        $atraso = intval(($data_chegada - $data_recebimento) / 86400);

        if ($atraso < 0) {
            $atraso = 0;
        }

        $media = intval(($data_chegada - $data_amostra) / 86400);

        $update = [
            'atraso' => $atraso,
            'media' => $media,
            'nivel' => nivel_oficina($media)
        ];

        $this->andamento_model->update($id, $update);
    }

    private function media_acabamento($id) {

        $cadastro = $this->andamento_model->select($id);

        if (!$cadastro) {
            return;
        }

        $acabamento_envio = strtotime($cadastro->data_acabamento_envio);

        $acabamento_chegada = strtotime($cadastro->data_acabamento_chegada);

        $media = intval(($acabamento_chegada - $acabamento_envio) / 86400);

        $update = [
            'media_acabamento' => $media,
            'nivel_acabamento' => nivel_acabamento($media),
            'paga_oficina' => $cadastro->qtd_boa * $cadastro->preco_unit,
            'paga_acabamento' => $cadastro->qtd_boa * $cadastro->valor_acabamento,
        ];

        $this->andamento_model->update($id, $update);
    }

    private function atualiza_acabamento_cortes($acabamento_id) {

        $acabamento = $this->acabamentos_model->select($acabamento_id);

        if (!$acabamento) {
            return '';
        }

        $this->acabamentos_model->update($acabamento_id, ['qtd_cortes' => $acabamento->qtd_cortes + 1]);
    }
    
    
    public function relatorio_producao() {

        $bc = array(array('link' => '#', 'page' => lang('Oficina')), array('link' => '#', 'page' => lang('Relatório produção'))); //rota na página
        
        $meta = array('page_title' => lang('Relatório produção'), 'bc' => $bc); //título na página
        
        $this->load->helper('daterange');

        $meses = [];

        $range = daterange(date('Y-m-01', strtotime("-12 month")), date('Y-m-d'));

        foreach ($range as $day) {
            list($y, $m, $d) = explode("-", $day);
            $meses["$y-$m"] = "$m/$y";
        }

        krsort($meses);

        $this->data['meses'] = $meses;

        $mes = $this->input->get('mes');

        if (!$mes) {
            $mes = date('Y-m');
        }
            
        $this->data['mes'] = $mes;
        
        $dias = [];

        for ($d = 1; $d <= 31; $d++) {

            if ($d < 10) {
                $d = "0$d";
            }

            $dias[$d] = 0;
        }
        
        $this->load->model('oficinarelatorio_model');
        
        $modelistas = $this->oficinarelatorio_model->producao_modelista($mes);
        
        $this->data['modelistas'] = [];
        
        foreach ($modelistas as $modelista) {
            
            if (!isset($this->data['modelistas'][$modelista->nome])) {
                $this->data['modelistas'][$modelista->nome] = $dias;
            }
            
            $this->data['modelistas'][$modelista->nome][$modelista->dia]++;
        }
        
        $ampliadores = $this->oficinarelatorio_model->producao_ampliador($mes);
        
        $this->data['ampliadores'] = [];
        
        foreach ($ampliadores as $ampliador) {
            
            if (!isset($this->data['ampliadores'][$ampliador->nome])) {
                $this->data['ampliadores'][$ampliador->nome] = $dias;
            }
            
            $this->data['ampliadores'][$ampliador->nome][$ampliador->dia]++;
        }
        
        $piloteiras = $this->oficinarelatorio_model->producao_piloteira($mes);
        
        $this->data['piloteiras'] = [];
        
        foreach ($piloteiras as $piloteira) {
            
            if (!isset($this->data['piloteiras'][$piloteira->nome])) {
                $this->data['piloteiras'][$piloteira->nome] = $dias;
            }
            
            $this->data['piloteiras'][$piloteira->nome][$piloteira->dia]++;
        }
                
        $this->page_construct('oficina/relatorio_producao', $this->data, $meta); //rota
    }
    
    //Corte pronto, vindo andamento
    function corte_pronto() {

        $bc = array(array('link' => '#', 'page' => lang('Oficina produção')), array('link' => '#', 'page' => lang('Corte pronto'))); //rota na página
        $meta = array('page_title' => lang('Corte pronto'), 'bc' => $bc); //título na página

        $inicio = strtotime("-30 days");
        
        $fim = time();
        
        if ($this->input->get('inicio')) {
            $inicio = $this->input->get('inicio') / 1000;
            $fim = $this->input->get('fim') / 1000;
        }
        
        $this->data['date_start'] = $inicio * 1000;
        $this->data['date_end'] = $fim * 1000;
        
        $filtro_inicio = date('Y-m-d', $inicio);
        
        $filtro_fim = date('Y-m-d', $fim);
        
        $cadastros = $this->andamento_model->lista_corte_pronto($filtro_inicio, $filtro_fim);

        $this->data['cadastros'] = $cadastros;

        $pilotocortes = $this->pilotocorte_model->lista_cortado();
        foreach ($pilotocortes as $pilotocorte) {
            $this->data['pilotocortes'][$pilotocorte->id] = $pilotocorte->cod_corte;
        }

        $oficinas = $this->oficinas_model->lista();
        foreach ($oficinas as $oficina) {
            $this->data['oficinas'][$oficina->id] = $oficina->nome;
        }

        $acabamentos = $this->acabamentos_model->lista();
        foreach ($acabamentos as $acabamento) {
            $this->data['acabamentos'][$acabamento->id] = $acabamento->nome;
        }

        $this->page_construct('oficina/corte_pronto', $this->data, $meta); //rota
    }
    
    public function cortePronto_salvar() {

        $this->load->model('products_model');

        $post = $this->input->post();

        $campos = [
            'piloto_corte_id',
            'cod_produto'
        ];

        $dados = [];

        foreach ($post as $campo => $valor) {

            if (in_array($campo, $campos)) {
                $dados[$campo] = trim($valor);
            }
        }

        if (empty($dados['cod_produto'])) {
            $dados['cod_produto'] = $this->get_cod_produto($dados['oficina_id']);
        }
        
        if (!empty($post['id'])) {
            
            $this->andamento_model->update($post['id'], $dados);
            
            $this->session->set_flashdata('message', 'Código atualizado');

            redirect('oficina/corte_pronto');
        }
        
        /*$this->andamento_model->insert($dados);

        $this->atualiza_oficina_cortes($dados['oficina_id']);

        $this->session->set_flashdata('message', 'Andamento cadastrado');

        redirect('oficina/andamento');*/
    }
    

}
