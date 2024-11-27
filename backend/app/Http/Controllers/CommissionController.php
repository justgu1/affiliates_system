<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    public function index()
    {
        $commissions = Commission::with('affiliate')->get();
        return response()->json($commissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'affiliate_id' => 'required|exists:affiliates,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $commission = Commission::create([
            'affiliate_id' => $validated['affiliate_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json($commission, 201);
    }

    public function update(Request $request, Commission $commission)
    {
        $validator = Validator::make($request->all(), [
            'affiliate_id' => 'required|exists:affiliates,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $commission->update([
            'affiliate_id' => $request->affiliate_id,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);

        return response()->json($commission);
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();
        return response()->json(null, 204);
    }
}
