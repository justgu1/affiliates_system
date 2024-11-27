<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Commission;
use Illuminate\Http\Request;

class AffiliateController extends AuthController
{
    public function index(Request $request)
    {
        $affiliates = Affiliate::paginate(10);
        return response()->json($affiliates);
    }

    public function show($id)
    {
        $affiliate = Affiliate::with('commissions')->findOrFail($id);

        return response()->json([
            'affiliate' => $affiliate,
        ]);
    }

    public function updateStatus($id, Request $request)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->status = $request->input('status', 1);
        $affiliate->save();

        return response()->json(['message' => 'Status atualizado com sucesso.']);
    }

    public function update($id, Request $request)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->update($request->all());

        return response()->json(['message' => 'Afiliado atualizado com sucesso.', 'affiliate' => $affiliate]);
    }

    public function addCommission($id, Request $request)
    {
        $affiliate = Affiliate::findOrFail($id);

        $commission = new Commission([
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
        ]);

        $affiliate->commissions()->save($commission);

        return response()->json(['message' => 'Comissão adicionada com sucesso.', 'commission' => $commission]);
    }
}
