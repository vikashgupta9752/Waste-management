<?php

namespace App\Services;

use App\Models\WasteRequest;
use Illuminate\Support\Facades\DB;

class HeatmapService
{
    /**
     * Get grid-based heatmap data.
     * Grid size is 0.005 degrees (approx 500m).
     */
    public function getGridData($filters = [])
    {
        $query = WasteRequest::query();

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['category_id'])) {
            $query->where('waste_category_id', $filters['category_id']);
        }

        $requests = $query->get(['latitude', 'longitude']);

        $grid = [];
        $step = 0.005; // Grid cell size in degrees

        foreach ($requests as $req) {
            if (!$req->latitude || !$req->longitude) continue;

            $latGrid = floor($req->latitude / $step) * $step;
            $lngGrid = floor($req->longitude / $step) * $step;

            $key = $latGrid . ',' . $lngGrid;

            if (!isset($grid[$key])) {
                $grid[$key] = [
                    'lat' => $latGrid,
                    'lng' => $lngGrid,
                    'count' => 0,
                    'bounds' => [
                        'north' => $latGrid + $step,
                        'south' => $latGrid,
                        'east' => $lngGrid + $step,
                        'west' => $lngGrid,
                    ]
                ];
            }
            $grid[$key]['count']++;
        }

        // Determine color based on density
        $maxCount = count($grid) > 0 ? max(array_column($grid, 'count')) : 1;

        foreach ($grid as &$cell) {
            $ratio = $cell['count'] / $maxCount;
            if ($ratio > 0.7) {
                $cell['color'] = '#FF0000'; // Red
                $cell['level'] = 'High';
            } elseif ($ratio > 0.3) {
                $cell['color'] = '#FFFF00'; // Yellow
                $cell['level'] = 'Medium';
            } else {
                $cell['color'] = '#00FF00'; // Green
                $cell['level'] = 'Low';
            }
        }

        return array_values($grid);
    }
}
