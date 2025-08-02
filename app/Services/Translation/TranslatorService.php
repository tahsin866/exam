<?php

namespace App\Services\Translation;

use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;

class TranslatorService
{
    protected $translator;

    public function __construct()
    {
        $this->translator = new Translator();
    }

    public function translate(string $text): string
    {
        return $this->translator->translate($text);
    }
}
