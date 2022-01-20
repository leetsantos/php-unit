<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require_once 'vendor/autoload.php';

$leilao= new Leilao('Fiat 147');

$maria= new Usuario('Maria');
$joao= new Usuario('João');

$leilao->recebeLance(new Lance($maria, 2500));
$leilao->recebeLance(new Lance($joao, 2000));


$leiloeiro= new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor= $leiloeiro->getMaiorValor();
echo $maiorValor;