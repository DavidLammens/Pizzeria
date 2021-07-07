<?php

declare(strict_types=1);

namespace Entities;

use Entities\Plaats;
use Entities\GebruikersAccount;

class Klant
{
    private ?int $id;

    private string $familienaam;

    private string $voornaam;

    private string $straat;

    private string $huisnummer;

    private string $bus;

    private ?Plaats $plaats;

    private string $telefoonnummer;

    private string $opmerkingen;

    private bool $promo;

    private ?GebruikersAccount $gebruikersAccount;

    public function __construct(
        ?int $id,
        string $familienaam,
        string $voornaam,
        string $straat,
        string $huisnummer,
        string $bus,
        ?Plaats $plaats,
        string $telefoonnummer,
        string $opmerkingen,
        bool $promo = false,
        GebruikersAccount $gebruikersAccount = null
    ) {
        $this->id = $id;
        $this->familienaam = $familienaam;
        $this->voornaam = $voornaam;
        $this->straat = $straat;
        $this->huisnummer = $huisnummer;
        $this->bus = $bus;
        $this->plaats = $plaats;
        $this->telefoonnummer = $telefoonnummer;
        $this->opmerkingen = $opmerkingen;
        $this->promo = $promo;
        $this->gebruikersAccount = $gebruikersAccount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFamilienaam(): string
    {
        return $this->familienaam;
    }

    public function getVoornaam(): string
    {
        return $this->voornaam;
    }

    public function getStraat(): string
    {
        return $this->straat;
    }

    public function getHuisnummer(): string
    {
        return $this->huisnummer;
    }

    public function getBus(): string
    {
        return $this->bus;
    }

    public function getPlaats(): ?Plaats
    {
        return $this->plaats;
    }

    public function getTelefoonnummer(): string
    {
        return $this->telefoonnummer;
    }

    public function getOpmerkingen(): string
    {
        return $this->opmerkingen;
    }

    public function getPromo(): bool
    {
        return $this->promo;
    }

    public function getGebruikersAccount(): ?GebruikersAccount
    {
        return $this->gebruikersAccount;
    }


    public function setFamilienaam(string $familienaam): Klant
    {
        $this->familienaam = $familienaam;

        return $this;
    }

    public function setVoornaam(string $voornaam): Klant
    {
        $this->voornaam = $voornaam;

        return $this;
    }

    public function setStraat(string $straat): Klant
    {
        $this->straat = $straat;

        return $this;
    }

    public function setHuisnummer(string $huisnummer): Klant
    {
        $this->huisnummer = $huisnummer;

        return $this;
    }

    public function setBus(string $bus): Klant
    {
        $this->bus = $bus;

        return $this;
    }

    public function setPlaats(Plaats $plaats): Klant
    {
        $this->plaats = $plaats;

        return $this;
    }

    public function setTelefoonnummer(string $telefoonnummer): Klant
    {
        $this->telefoonnummer = $telefoonnummer;

        return $this;
    }

    public function setOpmerkingen(string $opmerkingen): Klant
    {
        $this->opmerkingen = $opmerkingen;

        return $this;
    }

    public function setPromo(bool $promo): Klant
    {
        $this->promo = $promo;

        return $this;
    }

    public function setGebruikersAccount(GebruikersAccount $gebruikersAccount)
    {
        $this->gebruikersAccount = $gebruikersAccount;

        return $this;
    }
}
