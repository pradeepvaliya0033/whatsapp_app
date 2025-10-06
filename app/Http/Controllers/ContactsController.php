<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^\+?\d{10,15}$/'],
            'email' => 'nullable|email|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'attributes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Contact::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'tags' => $request->tags ? array_values(array_filter($request->tags)) : [],
            'attributes' => $request->attributes ?? [],
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact saved successfully.');
    }

    public function edit(string $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, string $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^\+?\d{10,15}$/'],
            'email' => 'nullable|email|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'attributes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $contact->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'tags' => $request->tags ? array_values(array_filter($request->tags)) : [],
            'attributes' => $request->attributes ?? [],
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(string $uuid)
    {
        $contact = Contact::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact deleted.');
    }
}


