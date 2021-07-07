<?php

declare(strict_types=1);

namespace Entities;

class GebruikersAccount
{
    private int $id;

    private string $emailadres;

    private string $wachtwoord;

    public function __construct(int $id, string $emailadres, string $wachtwoord)
    {
        $this->id = $id;
        $this->emailadres = $emailadres;
        $this->wachtwoord = $wachtwoord;
    }

    public function getId(): int 
    {
        return $this->id;
    }

    public function getEmailadres(): string 
    {
        return $this->emailadres;
    }

    public function getWachtwoord(): string 
    {
        return $this->wachtwoord;
    }

    public function setEmailadres(string $emailadres): GebruikersAccount
    {
        $this->emailadres = $emailadres;

        return $this;
    }

    public function setWachtwoord(string $wachtwoord): GebruikersAccount
    {
        $this->wachtwoord = $wachtwoord;

        return $this;
    }
}
