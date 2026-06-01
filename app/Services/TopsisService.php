<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\TopsisCalculation;
use App\Models\TopsisCalculationResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TopsisService
{
    /**
     * Executes the TOPSIS calculation process.
     * 
     * @param string|null $description Optional description for this batch calculation
     * @return TopsisCalculation The created calculation session with results
     */
    public function calculate(?string $description = null): TopsisCalculation
    {
        $criterias = Criteria::all();
        $alternatives = Alternative::with('scores')->get();

        if ($criterias->isEmpty() || $alternatives->isEmpty()) {
            throw new \Exception("Cannot calculate TOPSIS without criteria and alternatives.");
        }

        // 1. Normalize Criteria Weights (so they sum to 1)
        $totalWeight = $criterias->sum('weight');
        if ($totalWeight == 0) {
            throw new \Exception("Total criteria weight cannot be zero.");
        }

        $normalizedWeights = [];
        foreach ($criterias as $criteria) {
            $normalizedWeights[$criteria->id] = $criteria->weight / $totalWeight;
        }

        // 2. Build Decision Matrix (x_ij)
        $matrix = [];
        $criteriaSums = []; // For denominator of normalized matrix

        // Initialize sums
        foreach ($criterias as $criteria) {
            $criteriaSums[$criteria->id] = 0;
        }

        foreach ($alternatives as $alt) {
            foreach ($criterias as $criteria) {
                // Find score or default to 0
                $scoreModel = $alt->scores->where('criteria_id', $criteria->id)->first();
                $score = $scoreModel ? $scoreModel->score : 0;
                
                $matrix[$alt->id][$criteria->id] = $score;
                $criteriaSums[$criteria->id] += pow($score, 2);
            }
        }

        // 3. Normalize Decision Matrix & Weight it (v_ij)
        $weightedMatrix = [];
        $idealPositive = [];
        $idealNegative = [];

        foreach ($criterias as $criteria) {
            $idealPositive[$criteria->id] = $criteria->type === 'benefit' ? -INF : INF;
            $idealNegative[$criteria->id] = $criteria->type === 'benefit' ? INF : -INF;
        }

        foreach ($alternatives as $alt) {
            foreach ($criterias as $criteria) {
                $score = $matrix[$alt->id][$criteria->id];
                $denominator = sqrt($criteriaSums[$criteria->id]);
                
                // If all scores for a criteria are 0, prevent division by zero
                $normalizedScore = $denominator > 0 ? ($score / $denominator) : 0;
                $weightedScore = $normalizedScore * $normalizedWeights[$criteria->id];
                
                $weightedMatrix[$alt->id][$criteria->id] = $weightedScore;

                // Determine Ideals
                if ($criteria->type === 'benefit') {
                    if ($weightedScore > $idealPositive[$criteria->id]) $idealPositive[$criteria->id] = $weightedScore;
                    if ($weightedScore < $idealNegative[$criteria->id]) $idealNegative[$criteria->id] = $weightedScore;
                } else { // cost
                    if ($weightedScore < $idealPositive[$criteria->id]) $idealPositive[$criteria->id] = $weightedScore;
                    if ($weightedScore > $idealNegative[$criteria->id]) $idealNegative[$criteria->id] = $weightedScore;
                }
            }
        }

        // 4. Calculate Distances and Preference Scores
        $results = [];
        foreach ($alternatives as $alt) {
            $distancePositive = 0;
            $distanceNegative = 0;

            foreach ($criterias as $criteria) {
                $val = $weightedMatrix[$alt->id][$criteria->id];
                $distancePositive += pow($val - $idealPositive[$criteria->id], 2);
                $distanceNegative += pow($val - $idealNegative[$criteria->id], 2);
            }

            $dPlus = sqrt($distancePositive);
            $dMinus = sqrt($distanceNegative);

            // C_i^*
            $preferenceScore = ($dPlus + $dMinus) > 0 ? ($dMinus / ($dPlus + $dMinus)) : 0;

            $results[] = [
                'alternative_id' => $alt->id,
                'preference_score' => $preferenceScore,
                // Snapshot of scores for historical record
                'snapshot_data' => [
                    'scores' => $matrix[$alt->id],
                    'weights' => $normalizedWeights,
                    'ideals' => ['positive' => $idealPositive, 'negative' => $idealNegative],
                    'weighted_scores' => $weightedMatrix[$alt->id]
                ]
            ];
        }

        // Sort by preference score descending to rank
        usort($results, fn($a, $b) => $b['preference_score'] <=> $a['preference_score']);

        // Persist to database inside a transaction
        return DB::transaction(function () use ($description, $results) {
            $calculation = TopsisCalculation::create([
                'uuid' => (string) Str::uuid(),
                'description' => $description ?? 'TOPSIS Calculation ' . now()->format('Y-m-d H:i:s'),
                'batch_date' => now(),
            ]);

            $rank = 1;
            foreach ($results as $res) {
                $calculation->results()->create([
                    'alternative_id' => $res['alternative_id'],
                    'preference_score' => $res['preference_score'],
                    'rank' => $rank++,
                    'snapshot_data' => $res['snapshot_data'],
                ]);
            }

            return $calculation;
        });
    }
}
