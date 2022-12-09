<?php

namespace App\Service;

use App\Entity\BigFootSighting;
use App\Model\BigFootSightingScore;

class SightingScorer
{
    /**
     * @var ScoringFactorInterface[]
     */
    private array $scoringFactors;

    public function __construct(array $scoringFactors)
    {
        $this->scoringFactors = $scoringFactors;
    }

    public function score(BigFootSighting $sighting): BigFootSightingScore
    {
        $score = 0;
        foreach ($this->scoringFactors as $scoringFactor) {
            $score += $scoringFactor->score($sighting);
        }

        return new BigFootSightingScore($score);
    }
}
