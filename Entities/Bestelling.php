<?php

declare(strict_types=1);

namespace Entities;

use DateTime;
use Entities\Klant;
use Entities\BestellingsStatus;

class Bestelling
{
    private int $id;

    private Klant $klant;

    private DateTime $tijdstip;

    private array $bestellijnen;

    private string $opmerkingen;

    private BestellingsStatus $status;

    public function __construct(
        int $id,
        Klant $klant,
        DateTime $tijdstip,
        array $bestellijnen,
        string $opmerkingen,
        BestellingsStatus $status
    ) {
        $this->id = $id;
        $this->klant = $klant;
        $this->tijdstip = $tijdstip;
        $this->bestellijnen = $bestellijnen;
        $this->opmerkingen = $opmerkingen;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKlant(): Klant
    {
        return $this->klant;
    }

    public function getTijdstip(): DateTime
    {
        return $this->tijdstip;
    }

    public function getPrintTijdstip(): string
    {
        return $this->getTijdstip()->format("d/m/Y - H:i");
    }

    public function getBestellijnen(): array
    {
        return $this->bestellijnen;
    }

    public function getOpmerkingen(): string
    {
        return ($this->opmerkingen === "" ? "geen opmerkingen" : $this->opmerkingen);
    }

    public function getPrintTotaal(): string
    {
        $totaal = 0;
        foreach ($this->getBestellijnen() as $bestellijn) {
            $totaal += $bestellijn->getTotaal();
        }

        return number_format($totaal, 2, ",", ".");
    }

    public function getStatus(): BestellingsStatus
    {
        return $this->status;
    }

    public function setBestellijnen(array $bestellijnen): Bestelling
    {
        $this->bestellijnen = $bestellijnen;

        return $this;
    }

    public function setOpmerkingen(string $opmerkingen): Bestelling
    {
        $this->opmerkingen = $opmerkingen;

        return $this;
    }

    public function setStatus(BestellingsStatus $status): Bestelling
    {
        $this->status = $status;

        return $this;
    }
}
