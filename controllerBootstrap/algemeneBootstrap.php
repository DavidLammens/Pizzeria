<?php

declare(strict_types=1);

use Business\CookieService;
use Business\GebruikersAccountService;
use Business\KlantService;
use Business\MeldingService;
use Business\WinkelmandService;
use Exceptions\DBException;

spl_autoload_register();

session_start();

$klantService = new KlantService;
$winkelmandService = new WinkelmandService($klantService->getKlantPromo());
$cookieService = new CookieService;
$meldingService = new MeldingService($cookieService->getMelding());