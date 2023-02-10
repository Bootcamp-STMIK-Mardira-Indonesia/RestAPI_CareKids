<?php

namespace App\Http\Controllers\Api\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        if (!$contacts || $contacts->count() == 0) {
            return response()->json([
                'message' => 'Contact Not Found'
            ], 404);
        }
        return response()->json([
            'message' => 'Success View All Contacs',
            'data' => $contacts,
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
            'message' => 'Success Create Contact',
            'data' => $contact,
        ], 201);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json([
                'message' => 'Contact Not Found..'
            ], 404);
        }
        $contact->delete();
        return response()->json([
            'message' => 'Success Delete Contact',
            'data' => $contact,
        ], 200);
    }
}
