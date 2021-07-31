<?php
/**
 * ExperiencesLimit Helper
 */
namespace JDOUnivers\Helpers\Experiences;

class ExperienceLimit
{
    const None = 0;
    const Hour = 1;
    const Unique = 2;

    public $type;
    public $amount;

    public function __construct(int $type, int $amount)
    {
        $this->type = $type;
        $this->amount = $amount;
    }
    
}