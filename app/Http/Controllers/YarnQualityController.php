<?php

namespace App\Http\Controllers;

use App\Models\YarnQuality;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class YarnQualityController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:yarn_qualities,name',
        ]);

        $quality = YarnQuality::create($validated);

        return back()->with('createdYarnQuality', $quality->name)
            ->with('success', "Yarn quality {$quality->name} added and selected.");
    }
}
