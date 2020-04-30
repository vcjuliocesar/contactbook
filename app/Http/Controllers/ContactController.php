<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Contact::where('user_id', auth()->id())->get();
    }

    public function store(StoreContact $request)
    {

        if ($request->has('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('images', $fileName,'public');

            return Contact::create([
                'image' => $fileName,
                'name' => $request->name,
                'email' => $request->email,
                'user_id' => auth()->id(),
            ]);
        }

    }

    public function update()
    {
        //todo
    }

    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
    }
}
