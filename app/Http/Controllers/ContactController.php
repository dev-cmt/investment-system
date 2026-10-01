<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): View
    {
        $query = Contact::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = Contact::where('status', 'unread')->count();

        return view('backend.contacts.index', compact('contacts', 'unreadCount'));
    }

    /**
     * Display the specified contact message.
     */
    public function show(Contact $contact): View
    {
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return view('backend.contacts.show', compact('contact'));
    }

    /**
     * Update contact status or notes.
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied',
            'notes'  => 'nullable|string|max:2000',
        ]);

        $contact->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Contact message updated successfully.');
    }

    /**
     * Remove the specified contact message from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact message deleted successfully.');
    }
}
