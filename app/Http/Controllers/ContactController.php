<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::latest()->get();
        return $contact;
    }

    public function store(StoreContact $request)
    {
        Contact::create([
            'photo'=>$request->photo,
            'name'=>$request->name,
            'email'=>$request->email,
            'user_id'=>auth()->id(),
        ]);
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
