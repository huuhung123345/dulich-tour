<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'email'     => 'required|email|max:100',
            'phone'     => 'nullable|string|max:15',
            'subject'   => 'required|string|max:200',
            'message'   => 'required|string|max:2000',
        ]);

        Contact::create($data + ['created_at' => now()]);

        return redirect()->route('contact')->with('ok', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ lại sớm nhất.');
    }
}
