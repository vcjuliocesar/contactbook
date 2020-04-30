<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\StoreContact;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Contact::where('user_id',auth()->id())->get();
    }

    public function store(StoreContact $request)
    {
        $exploded = explode(',', $request->photo);

        $decoded = base64_decode($exploded[1]);

        $extension = Str::contains($exploded[0], 'jpeg') ? 'jpg' : 'png';

        $fileName = time() . '.' . $extension;

        $path = public_path() . '/' . $fileName;

        file_put_contents($path, $decoded);

        return Contact::create([
            'photo' => $fileName,
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => auth()->id(),
        ]);
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
