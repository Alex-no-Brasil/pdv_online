<?php

require __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PedidosConsumer extends CI_Controller {

    private $connection;
    private $channel;

    public function __construct() {

        if (!is_cli()) {
            exit;
        }

        parent::__construct();

        $this->load->model('pedidoonline_model');
    }

    public function init() {

        $this->connection = new AMQPStreamConnection(
                '159.65.252.70',
                5672,
                'jobs',
                '7PGtlK8ff5xuE6yw',
                '/'
        );

        $this->channel = $this->connection->channel();

        $this->channel->basic_consume('Online_Pedidos', '', false, false, false, false, [$this, 'process']);

        while ($this->channel->is_consuming()) {
            try {
                $this->channel->wait(null, false, 120);
            } catch (Exception $ex) {
                break;
            }
        }

        $this->channel->close();

        $this->connection->close();
    }

    public function process(AMQPMessage $message) {

        $order = json_decode($message->getBody());

        $status = 'Aprovado';
        
        if ($order->status === 'CANCELLED' || $order->status === 'RETURN') {
            $status = 'Cancelado';
        }
        
        $pedido = $this->pedidoonline_model->selectExternalId($order->externalId, $order->integrationId);

        if ($pedido) {

            echo "achou $order->externalId, $order->integrationId: $order->status\n";

            if ($status === 'Cancelado') {
                
                $this->pedidoonline_model->update($pedido->id, ['status' => $status]);
            }
            
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

            return;
        }
        
        
        $pedido = [
            'externalId' => $order->externalId,
            'externalCreated' => date('Y-m-d H:i:s', $order->externalCreated / 1000),
            'totalAmount' => $order->totalAmount,
            'status' => $status,
            'integrationId' => $order->integrationId,
            'marketId' => $order->marketId,
            'origem' => $this->getOrigem($order->integrationId, $order->marketId)
        ];

        if ($order->marketId === 'WBUY') {

            $pedido += [
                'sellerId' => $order->sellerId,
                'sellerName' => $order->sellerName,
                'paymentId' => $order->paymentId,
                'paymentType' => $order->paymentType
            ];

            $parts = explode("(", $order->shipping->service);

            $order->shipping->service = trim($parts[0]);
            
        } else {

            $pedido += [
                'sellerId' => $order->marketId,
                'sellerName' => $this->getVendedor($order->integrationId, $order->marketId),
                'paymentId' => $order->marketId,
                'paymentType' => $pedido['origem']
            ];

            print_r($pedido);

            $order->shipping->service = 'PAC';
        }

        $items = [];

        $totalItems = 0;

        foreach ($order->items as $item) {

            $totalItems += $item->quantity;

            $items[] = get_object_vars($item);
        }

        $pedido['totalItems'] = $totalItems;

        $cliente = get_object_vars($order->customer);

        $entrega = get_object_vars($order->shipping);

        $this->pedidoonline_model->insert($pedido, $items, $cliente, $entrega);

        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }

    private function getOrigem($integrationId, $marketId) {

        if ($marketId === 'WBUY' && $integrationId === '6162') {
            return 'Bela Plus Oficial';
        }

        if ($marketId === 'WBUY' && $integrationId === '7709') {
            return 'Sheup';
        }

        if ($marketId === 'MERCADOLIVRE') {
            return 'Mercadolivre';
        }

        if ($marketId === 'SHOPEE') {
            return 'Shopee';
        }

        if ($marketId === 'B2W') {
            return 'B2w';
        }

        if ($marketId === 'DAFITI') {
            return 'Dafiti';
        }

        return '-';
    }

    private function getVendedor($integrationId, $marketId) {

        if ($marketId === 'SHOPEE') {

            $shopee = [
                "551319889" => "Argentina",
                "653128709" => "Canada",
                "552016669" => "Chile",
                "552159487" => "Germany",
                "561404960" => "Mexico",
                "552179244" => "Panama",
                "552195744" => "Polonia",
                "561411580" => "Portugal",
                "561371961" => "Russia",
                "561416756" => "Uruguai",
            ];

            return isset($shopee[$integrationId]) ? $shopee[$integrationId] : null;
        }

        if ($marketId === 'MERCADOLIVRE') {

            $mercadolivre = [
                "790386989" => "Ami Paris",
                "1014889311" => "Portugal",
                "810333647" => "Ami Oficial",
                "1008444504" => "Polonia",
                "1011936980" => "Argentina",
                "1008586425" => "Russia",
                "1014871860" => "Canada",
                "1008430098" => "Germany",
                "1008430354" => "Panama",
                "1008611204" => "Uruguai",
                "1014884902" => "Mexico",
                "1008391876" => "Chile",
            ];

            return isset($mercadolivre[$integrationId]) ? $mercadolivre[$integrationId] : null;
        }

        return null;
    }

}
