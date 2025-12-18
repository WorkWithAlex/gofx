<?php
namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Mail\ContactReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactUsController extends Controller
{
    public function contact()
    {
        return view('contact');
    }

    public function submitContactForm(ContactFormRequest $request)
    {
        // If honeypot flagged (extra safety) — silent success for UX
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
        }

        $data = $request->validated();

        // sanitize
        $data['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $data['name'] = (string) Str::of($data['name'])->stripTags()->trim();
        $data['message'] = (string) Str::of($data['message'])->stripTags()->trim();

        // Capture metadata
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = substr($request->userAgent() ?? '', 0, 1000);

        // Save to DB
        $message = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
        ]);

        // Audit log (DBLog preferred; fallback to file log)
        try {
            if (app()->bound('dblog')) {
                app('dblog')->info('Contact message saved', [
                    'contact_id' => $message->id,
                    'email' => $message->email,
                    'ip' => $message->ip_address,
                ]);
            } else {
                \Log::info('Contact message saved', [
                    'contact_id' => $message->id,
                    'email' => $message->email,
                    'ip' => $message->ip_address,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('DBLog (contact save) failed: '.$e->getMessage());
        }

        // Send notification to support email (queue if configured)
        try {
            $supportEmail = config('mail.contact_address', config('mail.from.address', env('MAIL_FROM_ADDRESS')));

            // queue the mailable (ContactReceived implements ShouldQueue)
            Mail::to($supportEmail)->queue(new ContactReceived($message));
        } catch (\Throwable $e) {
            $exSummary = [
                'class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];

            try {
                if (app()->bound('dblog')) {
                    app('dblog')->withLoggedBy('system')->error('Contact mail failed', [
                        'contact_id' => $message->id,
                        'support_email' => $supportEmail,
                        'exception' => $exSummary,
                    ]);
                } else {
                    \Log::error('Contact mail failed: '.$e->getMessage(), [
                        'contact_id' => $message->id,
                        'exception' => $exSummary,
                    ]);
                }
            } catch (\Throwable $inner) {
                \Log::error('Failed to log mail failure: '.$inner->getMessage());
            }
        }

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }
}
