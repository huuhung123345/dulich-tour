<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderByDesc('contact_id')->paginate(15);

        return view('admin.contacts.index', compact('contacts'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return back()->with('ok', 'Đã xóa liên hệ.');
    }
}
