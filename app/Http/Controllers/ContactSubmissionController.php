<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    /**
     * Persist a public contact form submission and redirect back with a flash message.
     *
     * Routes through Laravel's CSRF middleware and validates server-side. The
     * submission lands in the `contact_submissions` table where it appears in
     * the admin inbox at /admin/contact-submissions.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:191'],
            'email'   => ['required', 'email', 'max:191'],
            'phone'   => ['nullable', 'string', 'max:64'],
            'subject' => ['nullable', 'string', 'max:191'],
            'service' => ['nullable', 'string', 'max:64'],
            'message' => ['required', 'string', 'max:5000'],
            // Honeypot. Bots fill this; humans do not.
            '_honey'  => ['nullable', 'string', 'max:0'],
        ]);

        // Compose subject: explicit subject wins, else fall back to service.
        $subject = $data['subject'] ?? null;
        if (! $subject && ! empty($data['service'])) {
            $subject = 'Service Inquiry: ' . $data['service'];
        }

        ContactSubmission::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone']   ?? null,
            'subject'    => $subject,
            'message'    => $data['message'],
            'ip'         => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 65535),
            'status'     => 'new',
        ]);

        return back()->with('contact_status', 'sent');
    }
}
