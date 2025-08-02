<?php

namespace App\Exceptions\Madrasha\Exam;

use Exception;

class ExamSetupNotFoundException extends Exception
{
    protected $message = 'কোনো পরীক্ষার সেটাপ পাওয়া যায়নি।';

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->message);
    }
}
