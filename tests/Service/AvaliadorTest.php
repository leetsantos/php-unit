<?php
namespace Alura\Leilao\tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;
    protected function setUp() :void
    {
        $this->leiloeiro= new Avaliador();
    }
    /**
     * @dataProvider retornaLeilao
     */
    public function testMaiorValor(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();


        self::assertEquals(2500, $maiorValor);
    }
    /**
     * @dataProvider retornaLeilao
     */
    public function testMenorValor(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();
        self::assertEquals(1700, $menorValor);
    }
    /**
     * @dataProvider retornaLeilao
     */
    public function testAvaliadorBusca3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);
        $maiores = $this->leiloeiro->getMaioresLances();
        static::assertCount(3, $maiores);
        static::assertEquals(2500, $maiores[0]->getValor());
        static::assertEquals(2000, $maiores[1]->getValor());
        static::assertEquals(1700, $maiores[2]->getValor());
    }
    /**
     * @dataProvider retornaLeilao
     */
    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possivel avaliar Leilão vazio'.PHP_EOL);
        $leilao = new Leilao('Meriva');
        $this->leiloeiro->avalia($leilao);
    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return $leilao;
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return $leilao;
    }

    public function testLeilaoFinalizado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Leilão já finalizado". PHP_EOL);
        $leilao= new Leilao('fia 147');
        $leilao->recebeLance(new Lance(new Usuario('teste'), 2000));
        $leilao->finaliza();
     
        $this->leiloeiro->avalia($leilao);
    }
    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));

        return $leilao;
    }

    public function retornaLeilao()
    {
        return [
          'Ordem Crescente'=>  [$this->leilaoEmOrdemCrescente()],
          'Ordem Decrescente'=>  [$this->leilaoEmOrdemDecrescente()],
          'Ordem Aleatoria' =>  [$this->leilaoEmOrdemAleatoria()]
        ];
    }
}