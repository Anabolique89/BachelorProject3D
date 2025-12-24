<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Submit contact form
     * 
     * POST /api/contact
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'order_number' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
        ]);

        // Log the contact form submission
        Log::info('Contact form submission:', $request->all());

        // TODO: Send email notification to admin
        // Mail::to('support@epicchronicles.com')->send(new ContactFormMail($request->all()));

        // TODO: Send auto-reply to customer
        // Mail::to($request->email)->send(new ContactConfirmationMail($request->name));

        return response()->json([
            'message' => 'Thank you for contacting us! We will get back to you soon.',
        ]);
    }
}