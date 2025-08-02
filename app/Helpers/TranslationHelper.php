<?php

namespace App\Helpers;

use MirazMac\BanglaString\Translator\BijoyToAvro\Translator;

class TranslationHelper
{
    protected $translator;

    public function __construct()
    {
        $this->translator = new Translator();
    }

    /**
     * Translate student fields from Bijoy to Avro
     *
     * @param \App\Models\Student $student
     * @return void
     */
    public function translateStudentFields($student): void
    {
        $fieldsToTranslate = [
            'Madrasha',
            'Name',
            'Father',
            'Division',
            'Class',
            'Markaj',
            'DateofBirth'
        ];

        foreach ($fieldsToTranslate as $field) {
            if (isset($student->$field)) {
                $student->$field = $this->translator->translate($student->$field);
            }
        }
    }
}
