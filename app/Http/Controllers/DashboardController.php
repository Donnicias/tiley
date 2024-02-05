<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * @return Response
     */
    public function index(): \Inertia\Response
    {
        $layouts = Layout::query()->with(['tiles'])->get();
        return Inertia::render('Dashboard',compact('layouts'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('public/images', $imageName);

        return response()->json(['imageUri' => asset("storage/images/$imageName")]);
    }
}
