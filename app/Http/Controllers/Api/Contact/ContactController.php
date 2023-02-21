<?php

namespace App\Http\Controllers\Api\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Resources\ContactResource;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        if (!$contacts || $contacts->count() == 0) {
            return response()->json([
                'message' => 'Message Not Found'
            ], 404);
        }
        return response()->json([
            'message' => 'Success View All Message',
            'data' => $contacts,
        ], 200);
    }

    public function show($id)
    {
        $contacts = Contact::where('id', $id)->get();
        if (!$contacts || $contacts->count() == 0) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Message Not Found'
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success View All Message',
            'data' => ContactResource::collection($contacts),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/',
            'pesan' => 'required|string',
        ]);

        $contact = Contact::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'phone' => $request->phone,
            'pesan' => $request->pesan
        ]);
        return response()->json([
            'message' => 'Success Message Send',
            'data' => $contact,
        ], 201);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json([
                'message' => 'Message Not Found..'
            ], 404);
        }
        $contact->delete();
        return response()->json([
            'message' => 'Success Delete message',
            'data' => $contact,
        ], 200);
    }
}
