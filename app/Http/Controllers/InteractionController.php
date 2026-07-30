<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InteractionController extends Controller
{
    public function rate(Request $request): JsonResponse
    {
        $request->validate([
            'entity_type' => 'required|in:exam,question',
            'entity_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (! Schema::hasTable('entity_ratings')) {
            return response()->json(['success' => true, 'message' => 'Thank you for your rating!']);
        }

        $userId = Auth::check() ? Auth::id() : null;

        if ($userId) {
            DB::table('entity_ratings')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'entity_type' => $request->entity_type,
                    'entity_id' => $request->entity_id,
                ],
                [
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'updated_at' => now(),
                ]
            );
        } else {
            DB::table('entity_ratings')->insert([
                'user_id' => null,
                'entity_type' => $request->entity_type,
                'entity_id' => $request->entity_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'created_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Thank you for your rating!']);
    }

    public function flag(Request $request): JsonResponse
    {
        $request->validate([
            'entity_type' => 'required|in:exam,question',
            'entity_id' => 'required|integer',
            'reason' => 'required|string|max:255',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (! Schema::hasTable('entity_flags')) {
            return response()->json(['success' => true, 'message' => 'Issue flagged successfully. Our team will review it.']);
        }

        DB::table('entity_flags')->insert([
            'user_id' => Auth::check() ? Auth::id() : null,
            'entity_type' => $request->entity_type,
            'entity_id' => $request->entity_id,
            'reason' => $request->reason,
            'comment' => $request->comment,
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Issue flagged successfully. Our team will review it.']);
    }
}
