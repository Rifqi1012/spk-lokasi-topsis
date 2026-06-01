<?php

namespace App\Services;

use App\Models\Kriteria;

class KriteriaService
{
    /**
     * Get the current total bobot of all criteria.
     */
    public function getTotalBobot(?int $ignoreKriteriaId = null): float
    {
        $query = Kriteria::query();

        if ($ignoreKriteriaId) {
            $query->where('kriteria_id', '!=', $ignoreKriteriaId);
        }

        return (float) $query->sum('bobot');
    }

    /**
     * Check if adding/updating a criteria will exceed the 100% total limit.
     */
    public function willExceedMaxBobot(float $newBobot, ?int $ignoreKriteriaId = null): bool
    {
        $currentTotal = $this->getTotalBobot($ignoreKriteriaId);
        return ($currentTotal + $newBobot) > 100.01; // Small margin for floating point errors
    }

    /**
     * Get remaining available bobot.
     */
    public function getRemainingBobot(?int $ignoreKriteriaId = null): float
    {
        $currentTotal = $this->getTotalBobot($ignoreKriteriaId);
        return max(0, 100 - $currentTotal);
    }
}
