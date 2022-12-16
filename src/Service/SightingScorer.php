<?php

namespace App\Service;

use App\Entity\BigFootSighting;
use App\Model\BigFootSightingScore;

class SightingScorer
{
    /**
     * @var ScoringFactorInterface[]
     */
    private iterable $scoringFactors;

    /**
     * @var ScoreAdjusterInterface[]
     */
    private $scoreAdjusters;

    public function __construct(iterable $scoringFactors, iterable $scoreAdjusters)
    {
        $this->scoringFactors = $scoringFactors;
        $this->scoreAdjusters = $scoreAdjusters;
    }

    public function score(BigFootSighting $sighting): BigFootSightingScore
    {
        $score = 0;
        foreach ($this->scoringFactors as $scoringFactor) {
            $score += $scoringFactor->score($sighting);
        }

        foreach ($this->scoreAdjusters as $scoreAdjuster) {
            $score = $scoreAdjuster->adjustScore($score, $sighting);
        }

        return new BigFootSightingScore($score);
    }
}
