<?php

//CODIGO DA LOJA
$codigo = 'ONLINE';
$tokenApi = '909baa531e21414de404a0ba6481c95c';

//NAO ALTERAR
$online = true;
$token = '909baa531e21414_MESTRE_de404a0ba6481c95c';
$urlApi = 'https://pdv.belaplusoficial.com.br/api';

$local = __DIR__ . '/config-local.php';

if (file_exists($local)) {
    require $local;
}