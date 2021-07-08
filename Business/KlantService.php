<?php

declare(strict_types=1);

namespace Business;

use Data\KlantDAO;
use Entities\Klant;
use Data\LeverPlaatsDAO;
use Data\GebruikersAccountDAO;
use Business\GebruikersAccountService;
use Entities\GebruikersAccount;
use Exceptions\OngeldigeInputException;

class KlantService
{

    //het attribuut klant kan door de functies als tijdelijke variabele gebruikt worden, 
    //de "echte klant" zit in $_SESSION['klant'].


    private ?Klant $klant;


    public function __construct()
    {
        $this->klant = $this->klantIsSaved() ? $this->getKlant() : null;
    }


    public function gegevensInvoeren(
        string $familienaam,
        string $voornaam,
        string $straat,
        string $huisnummer,
        string $bus,
        int $plaatsId,
        string $telefoonnummer,
        string $opmerkingen = "",
        bool $promo = false,
        bool $ookAccount = false,
        string $emailadres = null,
        string $wachtwoord = null,
        string $wachtwoordOpnieuw = null
    ): Klant {
        $klantDAO = new KlantDAO;
        $leverPlaatsDAO = new LeverPlaatsDAO;
        $gebruikersAccountService = new GebruikersAccountService;

        $plaats = $leverPlaatsDAO->getByPlaatsId($plaatsId);
        $klantId = ($this->klantIsSaved() ? $this->klant->getId() : null);

        $this->klant = new Klant(
            $klantId,
            $familienaam,
            $voornaam,
            $straat,
            $huisnummer,
            $bus,
            $plaats,
            $telefoonnummer,
            $opmerkingen,
            $promo,
            null
        );
        if (!$this->klantIsSaved()) {
            $this->Saveklant($klantDAO->create($this->klant));
        } else {
            $this->Saveklant($klantDAO->update($this->klant));
        }

        if ($ookAccount && $this->getKlant()->getGebruikersAccount() === null) {
            $gebruikersAccount = $gebruikersAccountService->registreer(
                $emailadres,
                $wachtwoord,
                $wachtwoordOpnieuw
            );
            $this->linkGebruikersAccount($gebruikersAccount);
        }

        return $this->getKlant();
    }

    //WIJZIGEN

    public function gegevensWijzigen(
        string $familienaam,
        string $voornaam,
        string $straat,
        string $huisnummer,
        string $bus,
        int $plaatsId,
        string $telefoonnummer,
        string $opmerkingen,
        bool $promo = false
    ): Klant {

        $klantDAO = new KlantDAO;
        $leverPlaatsDAO = new LeverPlaatsDAO;
        $plaats = $leverPlaatsDAO->getByPlaatsId($plaatsId);

        $this->klant = new Klant(
            $this->klant->getId(),
            $familienaam,
            $voornaam,
            $straat,
            $huisnummer,
            $bus,
            $plaats,
            $telefoonnummer,
            $opmerkingen,
            $promo,
            $this->klant->getGebruikersAccount()
        );

        return $this->saveKlant($klantDAO->update($this->klant));
    }

    public function accountGegevensWijzigen(
        string $emailadres,
        string $oudWachtwoord,
        ?string $wachtwoordWijzigen,
        string $nieuwWachtwoord,
        string $nieuwWachtwoordOpnieuw
    ): Klant {
        $gebruikersAccountService = new GebruikersAccountService;
        $gebruikersAccount = $this->klant->getGebruikersAccount();

        if ($gebruikersAccount) {
            if ($wachtwoordWijzigen){
                $gebruikersAccount = $gebruikersAccountService->update(
                $gebruikersAccount->getId(),
                $emailadres,
                $oudWachtwoord,
                $nieuwWachtwoord,
                $nieuwWachtwoordOpnieuw
            );
            }else{
                $gebruikersAccount = $gebruikersAccountService->update(
                    $gebruikersAccount->getId(),
                    $emailadres,
                    $oudWachtwoord,
                    $oudWachtwoord,
                    $oudWachtwoord
                );
            }
            
        }
        return $this->linkGebruikersAccount($gebruikersAccount);
    }

    public function saveOpmerkingen(string $opmerkingen): Klant
    {
        $klantDAO = new KlantDAO;

        return $this->saveKlant($klantDAO->update($this->klant->setOpmerkingen($opmerkingen)));
    }

    public function linkGebruikersAccount(GebruikersAccount $gebruikersAccount): Klant
    {
        $klantDAO = new KlantDAO;

        return $this->saveKlant($klantDAO->update($this->klant->setGebruikersAccount($gebruikersAccount)));
    }

    public function saveKlantPromo(bool $promo): Klant
    {
        $klantDAO = new KlantDAO;

        return $this->saveKlant($klantDAO->update($this->klant->setPromo($promo)));
    }


    //LOGIN etc.

    public function login(string $emailadres, string $wachtwoord): Klant
    {
        $gebruikersAccountDAO = new GebruikersAccountDAO;
        $klantDAO = new KlantDAO;

        $gebruikersAccount = $gebruikersAccountDAO->getByEmailadres($emailadres);
        if ($gebruikersAccount !== null) {
            if (password_verify($wachtwoord, $gebruikersAccount->getWachtwoord())) {
                session_regenerate_id();
                setcookie("emailadres", $emailadres, time() + 60 * 60 * 24 * 7);

                return $this->saveKlant($klantDAO->getByGebruikersAccountId($gebruikersAccount->getId()));;
            } else {
                throw new OngeldigeInputException("", 0, null, "wachtwoord");
            }
        } else {
            throw new OngeldigeInputException("", 0, null, "emailadres");
        }
    }

    public function logout(): void
    {
        $this->klant = null;
        unset($_SESSION['klant']);
    }

    public function ingelogd(): bool
    {
        return ($this->klantIsSaved() && $this->klant->getGebruikersAccount() !== null);
    }

    public function getEmailCookie(): ?string
    {
        return $_COOKIE['emailadres'] ?? null;
    }


    //$_SESSION['klant']

    public function getKlant(): ?Klant
    {
        return unserialize($_SESSION['klant'], ['Klant']);
    }

    public function saveKlant(Klant $klant) //klant in session opslaan
    {
        $this->klant = $klant;
        $_SESSION['klant'] = serialize($this->klant);

        return $this->klant;
    }

    public function loseKlant(): void
    {
        $this->klant = null;
        unset($_SESSION['klant']);
    }

    public function klantIsSaved(): bool //is de klant in de session-variabele is opgeslagen?
    {
        return isset($_SESSION['klant']);
    }


    public function getKlantEmailadres(): ?string
    {
        if ($this->klantIsSaved() && $this->klant->getGebruikersAccount() !== null) {
            $emailadres = $this->klant->getGebruikersAccount()->getEmailadres();
        } else {
            $emailadres = null;
        }

        return $emailadres;
    }


    //$this->klant 

    public function getTempKlant(): ?Klant
    {
        return $this->klant;
    }

    public function klantIsset(): bool
    {
        return isset($this->klant);
    }

    //De volgende functies dienen voornamelijk om eerder ingevoerde waarden in een formulier terug in te kunnen vullen,
    //zonder ter plekke te moeten testen.

    public function getKlantFamilienaam(): ?string
    {
        return $this->klantIsset() ? htmlspecialchars(strip_tags($this->klant->getFamilienaam())) : null;
    }

    public function getKlantVoornaam(): ?string
    {
        return $this->klantIsset() ? htmlspecialchars(strip_tags($this->klant->getVoornaam())) : null;
    }

    public function getKlantStraat(): ?string
    {
        return $this->klantIsset() ? htmlspecialchars(strip_tags($this->klant->getStraat())) : null;
    }

    public function getKlantHuisnummer(): ?string
    {
        return $this->klantIsset() ? htmlspecialchars(strip_tags($this->klant->getHuisnummer())) : null;
    }

    public function getKlantBus(): ?string
    {
        return $this->klantIsset() ? htmlspecialchars(strip_tags($this->klant->getBus())) : null;
    }

    public function getKlantPlaatsId(): ?int
    {
        return ($this->klantIsset() && $this->klant->getPlaats() !== null) ? $this->klant->getPlaats()->getId() : null;
    }

    public function getKlantPostcode(): ?string
    {
        if ($this->klantIsset() && $this->klant->getPlaats() !== null) {
            $postcode = htmlspecialchars(strip_tags($this->klant->getPlaats()->getPostcode()));
        } else {
            $postcode = null;
        }

        return $postcode;
    }

    public function getKlantPlaatsNaam(): ?string
    {
        if ($this->klantIsset() && $this->klant->getPlaats() !== null) {
            $naam = htmlspecialchars(strip_tags($this->klant->getPlaats()->getNaam()));
        } else {
            $naam = null;
        }

        return $naam;
    }

    public function getKlantPostcodePlaats(): string
    {
        return $this->getKlantPostcode() . " " .  $this->getKlantPlaatsNaam();
    }

    public function getKlantTelefoonnummer(): ?string
    {
        return $this->klantIsset() ? htmlspecialchars(strip_tags($this->klant->getTelefoonnummer())) : null;
    }

    public function getKlantPromo(): bool
    {
        return $this->klantIsset() ? $this->klant->getPromo() : false;
    }
}
