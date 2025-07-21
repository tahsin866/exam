<?php

namespace App\Helpers\madrasha\OldStudent;

use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;

class BanglaTranslatorHelper
{
    protected $translator;

    public function __construct()
    {
        $this->translator = new Translator();
    }

    public function translateFields($student)
    {
        $fields = ['Madrasha', 'Name', 'Father', 'Division', 'Class', 'Markaj', 'DateofBirth'];

        foreach ($fields as $field) {
            if (isset($student->$field)) {
                $student->$field = $this->translator->translate($student->$field);
            }
        }

        return $student;
    }
}
