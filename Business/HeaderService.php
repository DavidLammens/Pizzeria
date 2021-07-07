<?php

declare(strict_types=1);

namespace Business;


//Via deze service kan ik een variabele locatie voor de header() functie instellen:
//Als er via een (hidden) input met de naam 'header' een pagina-string 
//om naar verder te gaan of naar terug te kunnen keren werd meegegeven, 
//vangt deze service die op, controleert ze en slaat ze op als de controle ok is.
//In alle andere gevallen wordt de verplichtte parameter '$backupLocation' gebruikt.
//Als de parameter '$onlyBackupLocation' 'true' is, wordt de '$backupLocation' sowieso gebruikt.
//Op die manier kan ik bvb gemakkelijk terug keren naar een vorige (variabele) pagina na een geslaagde login of registratie.

class HeaderService
{
    private string $location;

    public function __construct(
        string $backupLocation,
        bool $onlyBackupLocation = false,
        array $allowedLocations = [
            "index.php",
            "bestellen.php",
            "registratie.php"
        ]
    ) {
        $this->location = $backupLocation;
        if (!$onlyBackupLocation) {
            $location = (isset($_POST['header']) ? $_POST['header'] : null);
            foreach ($allowedLocations as $allowedLocation) {
                if ($location === $allowedLocation) {
                    $this->location = $location;
                }
            }
        }
    }


    public function getLocation(): string
    {
        return $this->location;
    }

    public function header($string = null): void
    {
        $headerString = "Location: ";
        $headerString .= (isset($string) ? $string : $this->location);
        header($headerString);
        exit(0);
    }
}
