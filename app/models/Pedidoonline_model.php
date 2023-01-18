<?php

class Pedidoonline_model extends CI_Model {

    private $table = 'online_pedidos';
    private $table_items = 'online_pedido_items';
    private $table_clientes = 'online_pedido_clientes';
    private $table_entregas = 'online_pedido_entregas';

    public function __construct() {

        parent::__construct();
    }

    public function select($id) {

        $query = $this->db->get_where($this->table, ['id' => $id], 1);

        if ($query->num_rows() == 1) {
            return $query->row();
        }

        return;
    }

    public function selectExternalId($externalId, $integrationId) {

        $query = $this->db->get_where($this->table, ['externalId' => $externalId, 'integrationId' => $integrationId], 1);

        if ($query->num_rows() == 1) {
            return $query->row();
        }

        return;
    }

    public function selectItems($pedidoId) {

        $query = $this->db->get_where($this->table_items, ['pedidoId' => $pedidoId]);

        return $query->result();
    }

    public function selectCliente($pedidoId) {

        $query = $this->db->get_where($this->table_clientes, ['pedidoId' => $pedidoId], 1);

        if ($query->num_rows() == 1) {
            return $query->row();
        }

        return;
    }

    public function selectEntrega($pedidoId) {

        $query = $this->db->get_where($this->table_entregas, ['pedidoId' => $pedidoId], 1);

        if ($query->num_rows() == 1) {
            return $query->row();
        }

        return;
    }

    public function insert($pedido, $items, $cliente, $entrega) {

        if (!$this->db->insert($this->table, $pedido)) {
            return false;
        }

        $pedidoId = $this->db->insert_id();

        foreach ($items as $item) {
            $item['pedidoId'] = $pedidoId;
            $this->insertItem($item);
        }

        $cliente['pedidoId'] = $pedidoId;

        $this->insertCliente($cliente);

        $entrega['pedidoId'] = $pedidoId;

        $this->insertEntrega($entrega);
    }

    private function insertItem($item) {

        $this->db->insert($this->table_items, $item);
    }

    private function insertCliente($cliente) {

        $this->db->insert($this->table_clientes, $cliente);
    }

    private function insertEntrega($entrega) {

        $this->db->insert($this->table_entregas, $entrega);
    }

    public function update($id, $dados) {

        return $this->db->update($this->table, $dados, ['id' => $id]);
    }

    public function lista() {

        $query = $this->db->get($this->table);

        return $query->result();
    }

}
