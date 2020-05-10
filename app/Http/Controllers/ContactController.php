<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function index()
    {
        return Contact::whereUserId(auth()->id())->get();
    }

    public function store(ContactRequest $request)
    {
        $fileName = 'usuario.jpg';

        if ($request->has('image') && $request->image != "") {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('images', $fileName, 'public');
        }

        return Contact::create([
            'image' => $fileName,
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => auth()->id(),
        ]);
    }

    public function update(ContactRequest $request, $id)
    {
        $contact = Contact::findOrFail($id);

        if ($request->flagImage === "true" && $request->has('image') && $request->image != "") {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('images', $fileName, 'public');
            $contact->image = $fileName;
        }

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->save();

        return $contact;
    }

    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
    }
}
