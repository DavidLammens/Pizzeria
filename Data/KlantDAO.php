<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Exception;
use Data\DBConfig;
use Entities\Klant;
use Entities\Plaats;
use Exceptions\DBException;
use Data\GebruikersAccountDAO;
use Exceptions\LegeInputException;
use Exceptions\TeVeelInputException;
use Exceptions\OngeldigeInputException;

class KlantDAO
{
    public function create(Klant $klant): Klant
    {
        if ($klant->getFamilienaam() === "") {
            throw new LegeInputException("", 0, null, "familienaam");
        }
        if ($klant->getVoornaam() === "") {
            throw new LegeInputException("", 0, null, "voornaam");
        }
        if ($klant->getStraat() === "") {
            throw new LegeInputException("", 0, null, "straat");
        }
        if ($klant->getHuisnummer() === "") {
            throw new LegeInputException("", 0, null, "huisnummer");
        }
        if ($klant->getTelefoonnummer() === "") {
            throw new LegeInputException("", 0, null, "telefoonnummer");
        }

        if (strlen($klant->getFamilienaam()) > 45) {
            throw new TeVeelInputException("", 0, null, "familienaam", 45);
        }
        if (strlen($klant->getVoornaam()) > 45) {
            throw new TeVeelInputException("", 0, null, "voornaam", 45);
        }
        if (strlen($klant->getStraat()) > 45) {
            throw new TeVeelInputException("", 0, null, "straat", 45);
        }
        if (strlen($klant->getHuisnummer()) > 10) {
            throw new TeVeelInputException("", 0, null, "huisnummer", 10);
        }
        if (strlen($klant->getBus()) > 10) {
            throw new TeVeelInputException("", 0, null, "bus", 10);
        }
        if (strlen($klant->getTelefoonnummer()) > 20) {
            throw new TeVeelInputException("", 0, null, "telefoonnummer", 20);
        }
        if (strlen($klant->getOpmerkingen()) > 160) {
            throw new TeVeelInputException("", 0, null, "opmerkingen", 160);
        }

        if ($klant->getFamilienaam() !== htmlspecialchars(strip_tags($klant->getFamilienaam()))) {
            throw new OngeldigeInputException("", 0, null, "familienaam");
        }
        if ($klant->getVoornaam() !== htmlspecialchars(strip_tags($klant->getVoornaam()))) {
            throw new OngeldigeInputException("", 0, null, "voornaam");
        }
        if ($klant->getStraat() !== htmlspecialchars(strip_tags($klant->getStraat()))) {
            throw new OngeldigeInputException("", 0, null, "straat");
        }
        if ($klant->getHuisnummer() !== htmlspecialchars(strip_tags($klant->getHuisnummer()))) {
            throw new OngeldigeInputException("", 0, null, "huisnummer");
        }
        if ($klant->getBus() !== htmlspecialchars(strip_tags($klant->getBus()))) {
            throw new OngeldigeInputException("", 0, null, "bus");
        }
        if ($klant->getOpmerkingen() !== htmlspecialchars(strip_tags($klant->getOpmerkingen()))) {
            throw new OngeldigeInputException("", 0, null, "opmerkingen");
        }


        if (is_null($klant->getPlaats())) {
            throw new OngeldigeInputException("", 0, null, "plaats");
        }
        if (
            !preg_match("/^[0-9\s]*$/", $klant->getTelefoonnummer()) ||
            strlen($klant->getTelefoonnummer()) < 7
        ) {
            throw new OngeldigeInputException("", 0, null, "telefoonnummer");
        }


        $gebruikersAccountId = ($klant->getGebruikersAccount() !== null ?
            $klant->getGebruikersAccount()->getId() :
            null);

        try {
            $sql = "INSERT INTO klanten 
                    (familienaam, voornaam, straat, huisnummer, bus, plaatsId, 
                    telefoonnummer, opmerkingen, promo, gebruikersAccountId) values 
                    (:familienaam, :voornaam, :straat, :huisnummer, :bus, :plaatsId, 
                    :telefoonnummer, :opmerkingen, :promo, :gebruikersAccountId)";
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
                ':familienaam' => $klant->getFamilienaam(),
                ':voornaam' => $klant->getVoornaam(),
                ':straat' => $klant->getStraat(),
                ':huisnummer' => $klant->getHuisnummer(),
                ':bus' => $klant->getBus(),
                ':plaatsId' => $klant->getPlaats()->getId(),
                ':telefoonnummer' => $klant->getTelefoonnummer(),
                ':opmerkingen' => $klant->getOpmerkingen(),
                ':promo' => $klant->getPromo(),
                ':gebruikersAccountId' => $gebruikersAccountId
            ));
            $klantId = (int) $dbh->lastInsertId();
            $dbh = null;

            $klant = $this->getById($klantId);

            return $klant;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }


    public function getById(int $id): ?Klant
    {
        $gebruikersAccountDAO = new GebruikersAccountDAO;
        try {
            $sql = "SELECT klanten.id as klantId, familienaam, voornaam,
                    straat, huisnummer, bus, telefoonnummer,
                    opmerkingen, promo, gebruikersAccountId,
                    plaatsen.id as plaatsId, postcode, plaatsen.naam as plaatsnaam
                    FROM klanten INNER JOIN plaatsen
                    ON klanten.plaatsId = plaatsen.id
                    WHERE klanten.id = :id";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':id' => $id));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $plaats = new Plaats(
                    (int) $rij['plaatsId'],
                    $rij['postcode'],
                    $rij['plaatsnaam']
                );
                $gebruikersAccount = $gebruikersAccountDAO->getById((int) $rij['gebruikersAccountId']);
                $klant = new Klant(
                    (int) $rij['klantId'],
                    $rij['familienaam'],
                    $rij['voornaam'],
                    $rij['straat'],
                    $rij['huisnummer'],
                    $rij['bus'],
                    $plaats,
                    $rij['telefoonnummer'],
                    $rij['opmerkingen'],
                    (bool) $rij['promo'],
                    $gebruikersAccount
                );
            } else {
                $klant = null;
            }

            $dbh = null;
            return $klant;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }

    public function getByGebruikersAccountId(int $gebruikersAccountId): ?Klant
    {
        $gebruikersAccountDAO = new GebruikersAccountDAO;
        $gebruikersAccount = $gebruikersAccountDAO->getById($gebruikersAccountId);
        try {
            $sql = "SELECT klanten.id as klantId, familienaam, voornaam,
                    straat, huisnummer, bus, telefoonnummer,
                    opmerkingen, promo, 
                    plaatsen.id as plaatsId, postcode, plaatsen.naam as plaatsnaam
                    FROM klanten INNER JOIN plaatsen
                    ON klanten.plaatsId = plaatsen.id
                    WHERE klanten.gebruikersAccountId = :gebruikersAccountId";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':gebruikersAccountId' => $gebruikersAccountId));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $plaats = new Plaats(
                    (int) $rij['plaatsId'],
                    $rij['postcode'],
                    $rij['plaatsnaam']
                );
                $klant = new Klant(
                    (int) $rij['klantId'],
                    $rij['familienaam'],
                    $rij['voornaam'],
                    $rij['straat'],
                    $rij['huisnummer'],
                    $rij['bus'],
                    $plaats,
                    $rij['telefoonnummer'],
                    $rij['opmerkingen'],
                    (bool) $rij['promo'],
                    $gebruikersAccount
                );
            } else {
                $klant = null;
            }

            $dbh = null;
            return $klant;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }


    public function update(Klant $klant): Klant
    {
        if ($klant->getFamilienaam() === "") {
            throw new LegeInputException("", 0, null, "familienaam");
        }
        if ($klant->getVoornaam() === "") {
            throw new LegeInputException("", 0, null, "voornaam");
        }
        if ($klant->getStraat() === "") {
            throw new LegeInputException("", 0, null, "straat");
        }
        if ($klant->getHuisnummer() === "") {
            throw new LegeInputException("", 0, null, "huisnummer");
        }
        if ($klant->getTelefoonnummer() === "") {
            throw new LegeInputException("", 0, null, "telefoonnummer");
        }

        if (strlen($klant->getFamilienaam()) > 45) {
            throw new TeVeelInputException("", 0, null, "familienaam", 45);
        }
        if (strlen($klant->getVoornaam()) > 45) {
            throw new TeVeelInputException("", 0, null, "voornaam", 45);
        }
        if (strlen($klant->getStraat()) > 45) {
            throw new TeVeelInputException("", 0, null, "straat", 45);
        }
        if (strlen($klant->getHuisnummer()) > 10) {
            throw new TeVeelInputException("", 0, null, "huisnummer", 10);
        }
        if (strlen($klant->getBus()) > 10) {
            throw new TeVeelInputException("", 0, null, "bus", 10);
        }
        if (strlen($klant->getTelefoonnummer()) > 20) {
            throw new TeVeelInputException("", 0, null, "telefoonnummer", 20);
        }
        if (strlen($klant->getOpmerkingen()) > 160) {
            throw new TeVeelInputException("", 0, null, "opmerkingen", 160);
        }

        if ($klant->getFamilienaam() !== htmlspecialchars(strip_tags($klant->getFamilienaam()))) {
            throw new OngeldigeInputException("", 0, null, "familienaam");
        }
        if ($klant->getVoornaam() !== htmlspecialchars(strip_tags($klant->getVoornaam()))) {
            throw new OngeldigeInputException("", 0, null, "voornaam");
        }
        if ($klant->getStraat() !== htmlspecialchars(strip_tags($klant->getStraat()))) {
            throw new OngeldigeInputException("", 0, null, "straat");
        }
        if ($klant->getHuisnummer() !== htmlspecialchars(strip_tags($klant->getHuisnummer()))) {
            throw new OngeldigeInputException("", 0, null, "huisnummer");
        }
        if ($klant->getBus() !== htmlspecialchars(strip_tags($klant->getBus()))) {
            throw new OngeldigeInputException("", 0, null, "bus");
        }
        if ($klant->getOpmerkingen() !== htmlspecialchars(strip_tags($klant->getOpmerkingen()))) {
            throw new OngeldigeInputException("", 0, null, "opmerkingen");
        }

        if (is_null($klant->getPlaats())) {
            throw new OngeldigeInputException("", 0, null, "plaats");
        }
        if (
            !preg_match("/^[0-9\s]*$/", $klant->getTelefoonnummer()) ||
            strlen($klant->getTelefoonnummer()) < 7
        ) {
            throw new OngeldigeInputException("", 0, null, "telefoonnummer");
        }
        if (is_null($this->getById($klant->getId()))) {
            throw new OngeldigeInputException("", 0, null, "klantId");
        }


        $gebruikersAccountId = ($klant->getGebruikersAccount() !== null ?
            $klant->getGebruikersAccount()->getId() :
            null);

        try {

            $sql = "UPDATE klanten set 
                    familienaam = :familienaam,
                    voornaam = :voornaam,
                    straat = :straat,
                    huisnummer = :huisnummer,
                    bus = :bus,
                    plaatsId = :plaatsId,
                    telefoonnummer = :telefoonnummer,
                    opmerkingen = :opmerkingen,
                    promo = :promo,
                    gebruikersAccountId = :gebruikersAccountId
                    where id = :id";
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
                ':familienaam' => $klant->getFamilienaam(),
                ':voornaam' => $klant->getVoornaam(),
                ':straat' => $klant->getStraat(),
                ':huisnummer' => $klant->getHuisnummer(),
                ':bus' => $klant->getBus(),
                ':plaatsId' => $klant->getPlaats()->getId(),
                ':telefoonnummer' => $klant->getTelefoonnummer(),
                ':opmerkingen' => $klant->getOpmerkingen(),
                ':promo' => $klant->getPromo(),
                ':id' => $klant->getId(),
                ':gebruikersAccountId' => $gebruikersAccountId
            ));
            $dbh = null;

            $klant = $this->getById($klant->getId());
            return $klant;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}
