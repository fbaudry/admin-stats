<?php

namespace App\Entity;

class TeamRanking
{
    private $name;

    private $nbPoints;

    public function __construct(string $name, int $nbPoints)
    {
        $this->name = $name;
        $this->nbPoints = $nbPoints;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNbPoints(): int
    {
        return $this->nbPoints;
    }
}
