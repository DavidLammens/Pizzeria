<?php

declare(strict_types=1);

namespace Business;

use Data\GebruikersAccountDAO;
use Entities\GebruikersAccount;
use Exceptions\OngeldigeInputException;

class GebruikersAccountService
{
    public function registreer(
        string $emailadres,
        string $wachtwoord,
        string $wachtwoordOpnieuw
    ): GebruikersAccount {
        if ($wachtwoord !== $wachtwoordOpnieuw) {
            throw new OngeldigeInputException("", 0, null, "wachtwoordOpnieuw");
        }
        $gebruikersAccountDAO = new GebruikersAccountDAO;

        $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        return $gebruikersAccountDAO->create(
            $emailadres,
            $wachtwoord
        );
    }

    public function update(
        int $id,
        string $emailadres,
        string $oudWachtwoord,
        string $nieuwWachtwoord,
        string $nieuwWachtwoordOpnieuw
    ): GebruikersAccount {
        $gebruikersAccountDAO = new GebruikersAccountDAO;

        $gebruikersAccount = $gebruikersAccountDAO->getById($id);

        if (!password_verify($oudWachtwoord, $gebruikersAccount->getWachtwoord())) {
            throw new OngeldigeInputException("", 0, null, "oudWachtwoord");
        }
        if ($nieuwWachtwoord !== $nieuwWachtwoordOpnieuw) {
            throw new OngeldigeInputException("", 0, null, "nieuwWachtwoordOpnieuw");
        }
        $nieuwWachtwoord = password_hash($nieuwWachtwoord, PASSWORD_DEFAULT);
        $gebruikersAccount = $gebruikersAccount->setEmailadres($emailadres);
        $gebruikersAccount = $gebruikersAccount->setWachtwoord($nieuwWachtwoord);

        return $gebruikersAccountDAO->update($gebruikersAccount);
    }
}
