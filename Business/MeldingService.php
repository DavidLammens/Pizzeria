<?php

declare(strict_types=1);

namespace Business;

class MeldingService
{
    public function __construct(?string $melding=null){
        $this->setMelding($melding);
    }
    

    public function setMelding(?string $melding): ?string
    {
        if (isset($melding)){
            $_SESSION['melding'] = $melding;
        }
        
        return $melding;
    }

    public function takeMelding(): ?string
    {
        if ($this->meldingIsset()) {
            $melding = $_SESSION['melding'];
            unset($_SESSION['melding']);
        } else {
            $melding = null;
        }
        return $melding;
    }

    public function meldingIsset(): bool
    {
        return isset($_SESSION['melding']) ? true : false;
    }

    public function hidden(): ?string
    {
        return $this->meldingIsset() ? null : "hidden";
    }
}
