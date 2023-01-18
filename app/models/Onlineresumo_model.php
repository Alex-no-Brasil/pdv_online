<?php

class Onlineresumo_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function resumo_vendendor($date_start, $date_end) {

        $sql = "SELECT sellerName AS nome, SUM(totalItems) AS pecas, COUNT(1) AS vendas, SUM(totalAmount) total "
                . "FROM tec_online_pedidos WHERE "
                . "externalCreated >='$date_start 00:00:00' AND "
                . "externalCreated <='$date_end 23:59:59' AND "
                . "marketId='WBUY' AND sellerId IS NOT NULL GROUP BY sellerId";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function resumo_pagamento($date_start, $date_end) {

        $sql = "SELECT paymentType AS nome, SUM(totalAmount) total "
                . "FROM tec_online_pedidos WHERE "
                . "externalCreated >='$date_start 00:00:00' AND "
                . "externalCreated <='$date_end 23:59:59' AND "
                . "paymentId IS NOT NULL GROUP BY paymentId";

        $query = $this->db->query($sql);

        return $query->result();
    }

}
