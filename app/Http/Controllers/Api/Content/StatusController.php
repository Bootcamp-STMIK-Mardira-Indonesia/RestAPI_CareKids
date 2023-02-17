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
                'status' => 'Success',
                'message' => 'Success View All Status',
                'data' => $status
            ], 200);
        } else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        if ($request->name_status == null) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Name Status is required'
            ], 400);
        } else {
            $status = Status::where('name_status', $request->name_status)->first();
            if ($status) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Name Status is already exist'
                ], 400);
            }
            $status = Status::create([
                'name_status' => $request->name_status,
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Success Create Status',
                'data' => $status
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $status = Status::find($id);
        $status->update([
            'name_status' => $request->name_status,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Success Update Status',
            'data' => $status
        ], 200);
    }
}
