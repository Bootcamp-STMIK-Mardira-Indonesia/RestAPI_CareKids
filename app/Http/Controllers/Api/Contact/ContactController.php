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
                'message' => 'Comment Not Found'
            ], 404);
        }
        return response()->json([
            'message' => 'Success View All Comments',
            'pesan' => $contacts,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|string',
            'pesan' => 'required|string',
        ]);
        
        $contact = Contact::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan
        ]);
        return response()->json([
            'message' => 'Success Create Comment',
            'data' => $contact,
        ], 201);
    }
    
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json([
                'message' => 'Comment Not Found'
            ], 404);
        }
        $contact->delete();
        return response()->json([
            'message' => 'Success Delete Comment',
            'data' => $contact,
        ], 200);
    }
    
}

