<?php

namespace App\Scoring;

use App\Entity\BigFootSighting;

interface ScoringFactorInterface
{
    /**
     * Return the score that should be added to the overall score.
     *
     * This method should not throw an exception for any normal reason.
     */
    public function score(BigFootSighting $sighting): int;

    public function adjustScore(int $finalScore, BigFootSighting $sighting): int;

}
