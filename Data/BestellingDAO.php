<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Exception;
use Data\DBConfig;
use DateTime;
use Entities\Bestelling;
use Entities\BestellingsStatus;
use Exceptions\DBException;
use Exceptions\LegeInputException;
use Exceptions\TeVeelInputException;
use Exceptions\OngeldigeInputException;

class BestellingDAO
{
    //ik heb hier voor een transactie gekozen, 
    //waarbij er bij het aanmaken van een bestelling ook meteen alle bestellijnen worden aangemaakt,
    //om te voorkomen dat er onvolledige bestellingen geregistreerd kunnen worden in geval van problemen met de DB

    public function create(
        int $klantId,
        string $opmerkingen,
        array $bestellijnen
    ): Bestelling {
        if (strlen($opmerkingen) > 160) {
            throw new TeVeelInputException("", 0, null, "opmerkingen", 160);
        }
        if ($opmerkingen !== htmlspecialchars(strip_tags($opmerkingen))) {
            throw new OngeldigeInputException("", 0, null, "opmerkingen");
        }
        foreach ($bestellijnen as $bestellijn) {
            if ($bestellijn->getAantal() < 1) {
                throw new LegeInputException("", 0, null, "aantal pizza " . $bestellijn->getPizzaId());
            }
        }
        try {
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $dbh->beginTransaction();

            $sql = "INSERT INTO bestellingen (klantId, opmerkingen) 
                    values (:klantId, :opmerkingen)";

            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
                ':klantId' => $klantId,
                ':opmerkingen' => $opmerkingen
            ));
            $bestellingId = (int) $dbh->lastInsertId();

            foreach ($bestellijnen as $bestellijn) {
                $sql = "INSERT INTO bestellijnen 
                    (bestellingId, pizzaId, aantal, bestelPrijsPerStuk) 
                    values 
                    (:bestellingId, :pizzaId, :aantal, :bestelPrijsPerStuk)";

                $stmt = $dbh->prepare($sql);
                $stmt->execute(array(
                    ':bestellingId' => $bestellingId,
                    ':pizzaId' => $bestellijn->getPizza()->getId(),
                    ':aantal' => $bestellijn->getAantal(),
                    ':bestelPrijsPerStuk' => $bestellijn->getPrijsPerStuk()
                ));
            }
            $dbh->commit();

            $bestelling = $this->getById($bestellingId);
            return $bestelling;
        } catch (Exception $exception) {
            $dbh->rollBack();
            $dbh = null;
            throw new DBException($exception->getMessage());
        }
    }

    public function getById(int $id): ?Bestelling
    {
        $klantDAO = new KlantDAO;
        $bestellijnDAO = new BestellijnDAO;
        $bestellijnen = $bestellijnDAO->getByBestellingId($id);
        try {
            $sql = "SELECT bestellingen.id as bestellingId, klantId, tijdstip, opmerkingen, bestellingsStatusId,
                    bestellingsstatussen.naam as bestellingsStatusNaam
                    FROM bestellingen INNER JOIN bestellingsstatussen
                    on bestellingen.bestellingsStatusId = bestellingsstatussen.id
                    WHERE bestellingen.id = :id";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':id' => $id));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $klant = $klantDAO->getById((int)$rij['klantId']);

                $tijdstip = new DateTime($rij['tijdstip']);

                $bestellingsStatus = new BestellingsStatus(
                    (int) $rij['bestellingsStatusId'],
                    $rij['bestellingsStatusNaam']
                );
                $bestelling = new Bestelling(
                    (int) $rij['bestellingId'],
                    $klant,
                    $tijdstip,
                    $bestellijnen,
                    $rij['opmerkingen'],
                    $bestellingsStatus
                );
            } else {
                $bestelling = null;
            }

            $dbh = null;
            return $bestelling;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }

    public function getByKlantId_TijdDesc(int $klantId): array
    {
        $klantDAO = new KlantDAO;
        $bestellijnDAO = new BestellijnDAO;

        try {
            $sql = "SELECT bestellingen.id as bestellingId, klantId, tijdstip, opmerkingen, bestellingsStatusId,
                    bestellingsstatussen.naam as bestellingsStatusNaam
                    FROM bestellingen INNER JOIN bestellingsstatussen
                    on bestellingen.bestellingsStatusId = bestellingsstatussen.id
                    WHERE bestellingen.klantId = :klantId
                    order by tijdstip desc";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':klantId' => $klantId));
            $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $bestellingen = [];

            if ($resultSet) {
                foreach ($resultSet as $rij) {

                    $klant = $klantDAO->getById((int)$rij['klantId']);
                    $tijdstip = new DateTime($rij['tijdstip']);
                    $bestellijnen = $bestellijnDAO->getByBestellingId((int) $rij['bestellingId']);
                    $bestellingsStatus = new BestellingsStatus(
                        (int) $rij['bestellingsStatusId'],
                        $rij['bestellingsStatusNaam']
                    );

                    $bestelling = new Bestelling(
                        (int) $rij['bestellingId'],
                        $klant,
                        $tijdstip,
                        $bestellijnen,
                        $rij['opmerkingen'],
                        $bestellingsStatus
                    );

                    $bestellingen[] = $bestelling;
                }
            } 

            $dbh = null;

            return $bestellingen;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}
