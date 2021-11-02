<?php

declare(strict_types=1);

namespace Data;

use PDO;
use Exception;
use Data\DBConfig;
use Exceptions\DBException;
use Entities\GebruikersAccount;
use Exceptions\LegeInputException;
use Exceptions\OngeldigeInputException;
use Exceptions\EmailadresBestaatException;

class GebruikersAccountDAO
{
    public function create(
        string $emailadres,
        string $wachtwoord
    ): GebruikersAccount {

        if ($emailadres === "") {
            throw new LegeInputException("", 0, null, "emailadres");
        }
        if ($wachtwoord === "") {
            throw new LegeInputException("", 0, null, "wachtwoord");
        }
        if (!filter_var($emailadres, FILTER_VALIDATE_EMAIL)) {
            throw new OngeldigeInputException("", 0, null, "emailadres");
        }
        if ($this->getByEmailadres($emailadres)) {
            throw new EmailadresBestaatException();
        }
        if (strlen($wachtwoord) < 4) {
            throw new OngeldigeInputException("", 0, null, "wachtwoord");
        }

        try {
            $sql = "INSERT INTO gebruikersaccounts (emailadres, wachtwoord) 
                    values (:emailadres, :wachtwoord)";
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
                ':emailadres' => $emailadres,
                ':wachtwoord' => $wachtwoord
            ));
            $dbh = null;

            return $this->getByEmailadres($emailadres);
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }

    public function getByEmailadres(string $emailadres): ?GebruikersAccount
    {
        try {
            $sql = "SELECT id, emailadres, wachtwoord
                    FROM gebruikersaccounts
                    WHERE emailadres = :emailadres";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':emailadres' => $emailadres));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $gebruikersAccount = new GebruikersAccount(
                    (int) $rij['id'],
                    $rij['emailadres'],
                    $rij['wachtwoord']
                );
            } else {
                $gebruikersAccount = null;
            }

            $dbh = null;

            return $gebruikersAccount;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }

    public function getById(int $id): ?GebruikersAccount
    {
        try {
            $sql = "SELECT id, emailadres, wachtwoord
                    FROM gebruikersaccounts
                    WHERE id = :id";

            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':id' => $id));
            $rij = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($rij) {
                $gebruikersAccount = new GebruikersAccount(
                    (int) $rij['id'],
                    $rij['emailadres'],
                    $rij['wachtwoord']
                );
            } else {
                $gebruikersAccount = null;
            }

            $dbh = null;

            return $gebruikersAccount;
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }

    public function update(GebruikersAccount $gebruikersAccount): GebruikersAccount
    {
        $gebruikersAccountDAO = new GebruikersAccountDAO;
        $bestaandeGebruikersAccount = $gebruikersAccountDAO->getByEmailadres($gebruikersAccount->getEmailadres());

        if (
            isset($bestaandeGebruikersAccount) &&
            $bestaandeGebruikersAccount->getId() !== $gebruikersAccount->getId()
        ) {
            throw new EmailadresBestaatException();
        }
        try {

            $sql = "UPDATE gebruikersaccounts set
                    emailadres = :emailadres,
                    wachtwoord = :wachtwoord
                    where id = :id";
            $dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(
                ':emailadres' => $gebruikersAccount->getEmailadres(),
                ':wachtwoord' => $gebruikersAccount->getWachtwoord(),
                ':id' => $gebruikersAccount->getId()
            ));
            $dbh = null;

            return $this->getById($gebruikersAccount->getId());
        } catch (Exception $exception) {

            throw new DBException($exception->getMessage());
        }
    }
}
