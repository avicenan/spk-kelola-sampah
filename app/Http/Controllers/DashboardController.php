<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\TPA;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function fivedayssampah()
    {
        $sevenDaysAgo = now()->subDays(7)->startOfDay();

        // Get weights data for last 7 days
        $dailyWeights = \App\Models\HasilKeputusan::where('rank', 1)
            ->where('created_at', '>=', $sevenDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(jumlah_sampah) as total_weight')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->total_weight];
            });

        // Fill in missing dates with 0
        $dates = [];
        $totals = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $formattedDate = \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d M');
            $dates[] = $formattedDate;
            $totals[] = $dailyWeights[$date] ?? 0;
        }

        return response()->json([
            'dates' => array_reverse($dates),
            'totals' => array_reverse($totals)
        ]);
    }

    public function countKeputusan()
    {
        $result = \App\Models\HasilKeputusan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        return $result;
    }

    public function topFourJenisSampah()
    {
        // Get all active waste types
        $allActiveTypes = JenisSampah::where('is_active', true)->pluck('nama')->toArray();

        // Get top 4 by weight
        $topByWeight = \App\Models\HasilKeputusan::where('rank', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('jenis_sampah, SUM(jumlah_sampah) as total_weight')
            ->groupBy('jenis_sampah')
            ->orderByDesc('total_weight')
            ->limit(4)
            ->get();

        // If we have less than 4 types with data, fill with other active types
        if ($topByWeight->count() < 4) {
            $usedTypes = $topByWeight->pluck('jenis_sampah')->toArray();
            $remainingTypes = array_diff($allActiveTypes, $usedTypes);

            // Add remaining types with zero weight
            foreach ($remainingTypes as $type) {
                if ($topByWeight->count() >= 4) break;
                $topByWeight->push((object)[
                    'jenis_sampah' => $type,
                    'total_weight' => 0
                ]);
            }
        }

        return $topByWeight;
    }

    public function topTPA()
    {
        // Get all TPA names first
        $allTPA = \App\Models\TPA::pluck('nama');

        // Get results for rank 1
        $rank1Results = \App\Models\HasilKeputusan::where('rank', 1)
            ->selectRaw('nama, COUNT(*) as total_wins, SUM(jumlah_sampah) as total_weight')
            ->groupBy('nama')
            ->orderByDesc('total_wins')
            ->limit(12)
            ->get();

        // Create a collection with all TPAs initialized to 0
        $result = $allTPA->map(function ($nama) {
            return (object)[
                'nama' => $nama,
                'total_wins' => 0,
                'total_weight' => 0
            ];
        });

        // Update values for TPAs that have rank 1 wins
        $rank1Results->each(function ($item) use (&$result) {
            $result = $result->map(function ($row) use ($item) {
                if ($row->nama === $item->nama) {
                    $row->total_wins = $item->total_wins;
                    $row->total_weight = $item->total_weight;
                }
                return $row;
            });
        });

        // Sort by total_wins and take top 6
        $result = $result->sortByDesc('total_wins')->take(12)->values();

        return $result;
    }

    public function latestKeputusan()
    {
        $result = \App\Models\HasilKeputusan::where('rank', 1)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();
        return $result;
    }

    public function index()
    {
        $fiveDaysSampah = json_decode($this->fivedayssampah()->getContent(), true);
        $countKeputusan = $this->countKeputusan();
        $topFourJenisSampah = $this->topFourJenisSampah();
        $topTPA = $this->topTPA();
        $latestKeputusan = $this->latestKeputusan();
        $countTPA = TPA::count();
        $countJenisSampah = JenisSampah::count();
        // return dd($latestKeputusan);

        // return dd($topFourJenisSampah);
        return view('dashboard', compact('fiveDaysSampah', 'countKeputusan', 'topFourJenisSampah', 'topTPA', 'latestKeputusan', 'countTPA', 'countJenisSampah'));
    }
}
