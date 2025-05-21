<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\TPA;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function fivedayssampah()
    {
        $fiveDaysAgo = now()->subDays(5)->startOfDay();

        // Get weights data for last 5 days
        $dailyWeights = \App\Models\HasilKeputusan::where('rank', 1)
            ->where('created_at', '>=', $fiveDaysAgo)
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
        for ($i = 0; $i < 5; $i++) {
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
        $result = \App\Models\HasilKeputusan::where('rank', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('jenis_sampah, SUM(jumlah_sampah) as total_weight')
            ->groupBy('jenis_sampah')
            ->orderByDesc('total_weight')
            ->limit(4)
            ->get();

        return $result;
    }

    public function topTPA()
    {
        $result = \App\Models\HasilKeputusan::where('rank', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('nama, COUNT(*) as total_wins, SUM(jumlah_sampah) as total_weight')
            ->groupBy('nama')
            ->orderByDesc('total_wins')
            ->limit(6)
            ->get();

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
