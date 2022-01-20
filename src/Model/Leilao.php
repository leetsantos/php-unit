<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {

        if (!empty($this->lances) && $this->ehOUltimo($lance)){
            throw new \DomainException("Usuário não pode propor dois lances seguidos". PHP_EOL);
         }
        $usuario=$lance->getUsuario();

        $totalDeLancesUsuario = $this->numeroLancesUsuario($usuario);
        if($totalDeLancesUsuario>=5){
            throw new \DomainException("Usuário não pode dar mais que 5 lances no mesmo leilão". PHP_EOL);
        }
        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    /**
     * @param Lance $lance
     * @return bool
     */
    private function ehOUltimo(Lance $lance): bool
    {
        $ultimoLance = $this->lances[count($this->lances) - 1]->getUsuario();
        return $lance->getUsuario() == $ultimoLance;
    }

    /**
     * @param Usuario $usuario
     * @return int
     */
    private function numeroLancesUsuario(Usuario $usuario): int
    {
        $totalDeLancesUsuario = array_reduce($this->lances, function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
            if ($lanceAtual->getUsuario() == $usuario) {
                return $totalAcumulado + 1;
            }
            return $totalAcumulado;
        }, 0);
        return $totalDeLancesUsuario;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estarFinalizado(): bool
    {
        return $this->finalizado;
    }
}
