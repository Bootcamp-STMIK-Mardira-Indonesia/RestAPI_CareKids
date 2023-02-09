<?php

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    public function index()
    {
        if (Status::count() > 0) {
            $status = Status::all();
            return response()->json([
                'status' => 'success',
                'data' => $status
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $status = Status::create([
            'name_status' => $request->name_status,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $status
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $status = Status::find($id);
        $status->update([
            'name_status' => $request->name_status,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $status
        ], 200);
    }
}
