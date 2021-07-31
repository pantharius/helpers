<?php
/**
 * ExperiencesRewardReason Helper
 */
namespace JDOUnivers\Helpers\Experiences;

class ExperienceRewardReason
{
    public $text;
    public $reward;
    public $limit;

    public function __construct(string $text, int $reward, $limit){
        $this->text = $text;
        $this->reward = $reward;
        $this->limit = $limit;
    }
}