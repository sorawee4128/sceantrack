<?php

namespace App\Http\Controllers;

use App\Models\SceneCasePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SceneCasePhotoController extends Controller
{
    public function destroy(Request $request, SceneCasePhoto $photo)
    {
        $sceneCase = $photo->sceneCase;
        $this->authorize('update', $sceneCase);

        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'ลบรูปเรียบร้อยแล้ว',
            ]);
        }

        return back()->with('success', 'ลบรูปเรียบร้อยแล้ว');
    }
}