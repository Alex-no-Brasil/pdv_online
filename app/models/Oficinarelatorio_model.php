<?php

class Oficinarelatorio_model extends CI_Model {

    public function __construct() {

        parent::__construct();
    }

    public function producao_modelista($mes) {
        
        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT md.nome, DATE_FORMAT(pc.data_cad,  '%d') AS dia "
                . "FROM tec_oficina_piloto_cortes pc, tec_oficina_modelistas md "
                . "WHERE pc.data_cad >= '$data_inicio' AND pc.data_cad <= '$data_fim' AND md.id = pc.usuario_cad";
        
        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function producao_piloteira($mes) {
        
        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT pl.nome, DATE_FORMAT(pc.data_piloto,  '%d') AS dia "
                . "FROM tec_oficina_piloto_cortes pc, tec_oficina_piloteira pl "
                . "WHERE pc.data_piloto >= '$data_inicio' AND pc.data_piloto <= '$data_fim' AND pl.id = pc.resp_piloto";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function producao_ampliador($mes) {
        
        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT ap.nome, DATE_FORMAT(pc.data_ampliado,  '%d') AS dia "
                . "FROM tec_oficina_piloto_cortes pc, tec_oficina_ampliador ap "
                . "WHERE pc.data_ampliado >= '$data_inicio' AND pc.data_ampliado <= '$data_fim' AND ap.id = pc.usuario_ampliador";
        
        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function pecas_diaria($mes) {

        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT of.nome, DATE_FORMAT(ad.data_acabamento_chegada,  '%d') AS dia, 
            ad.qtd_boa, ad.qtd_defeito 
            FROM tec_oficina_andamento ad, tec_oficinas of 
            WHERE ad.data_acabamento_chegada >= '$data_inicio' AND ad.data_acabamento_chegada <= '$data_fim' AND of.id = ad.oficina_id";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function cortes_diario($mes) {

        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT of.nome, DATE_FORMAT(ad.data_acabamento_chegada,  '%d') AS dia
            FROM tec_oficina_andamento ad, tec_oficinas of 
            WHERE ad.data_acabamento_chegada >= '$data_inicio' AND ad.data_acabamento_chegada <= '$data_fim' AND of.id = ad.oficina_id";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function pagamento_diario($mes) {

        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT of.nome, DATE_FORMAT(ad.data_acabamento_chegada,  '%d') AS dia,
            ad.paga_oficina, ad.data_paga_oficina 
            FROM tec_oficina_andamento ad, tec_oficinas of 
            WHERE ad.data_acabamento_chegada >= '$data_inicio' AND ad.data_acabamento_chegada <= '$data_fim' AND of.id = ad.oficina_id";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function pagamento_acabamento($mes) {
        
        $data_inicio = "$mes-01";
        
        $data_fim = "$mes-31";
        
        $sql = "SELECT ac.nome, DATE_FORMAT(ad.data_acabamento_chegada,  '%d') AS dia,
            ad.paga_acabamento, ad.data_paga_acabamento 
            FROM tec_oficina_andamento ad, tec_oficina_acabamentos ac 
            WHERE ad.data_acabamento_chegada >= '$data_inicio' AND ad.data_acabamento_chegada <= '$data_fim' AND ac.id = ad.acabamento_id";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function grafico_pecas() {
        
        $sql = "SELECT of.nome, SUM(ad.qtd_boa) AS qtd_boa
            FROM tec_oficina_andamento ad, tec_oficinas of 
            WHERE of.id = ad.oficina_id AND ad.data_acabamento_chegada IS NOT NULL 
            GROUP BY of.id ORDER BY qtd_boa DESC";

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    public function grafico_cortes() {
        
        $sql = "SELECT of.nome, COUNT(1) AS cortes
            FROM tec_oficina_andamento ad, tec_oficinas of 
            WHERE of.id = ad.oficina_id AND ad.data_acabamento_chegada IS NOT NULL 
            GROUP BY of.id ORDER BY cortes DESC";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function grafico_receita() {
        
        $sql = "SELECT of.nome, SUM(ad.paga_oficina) AS receita
            FROM tec_oficina_andamento ad, tec_oficinas of 
            WHERE of.id = ad.oficina_id AND ad.data_acabamento_chegada IS NOT NULL 
            GROUP BY of.id ORDER BY receita DESC";

        $query = $this->db->query($sql);

        return $query->result();
    }
}
