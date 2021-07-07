<?php

declare(strict_types=1);

namespace Business;

class CookieService{
    private ?string $melding;

    public function __construct()
    {
        if (!isset($_COOKIE['melding'])){
            setcookie("melding", "ok", time()+60*60*24*30);
            $this->melding = "Wij maken op deze website enkel gebruik van functionele cookies om de gebruikservaring te verbeteren.";
        } else {
            $this->melding = null;
        }
    }
    

    public function getMelding(): ?string
    {
        return $this->melding;
    }
}