<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    /**
     * Display segment control screen
     */
    public function index()
    {
        $allUsersCount = User::select('email')->union(NewsletterSubscriber::select('email'))->count();
        
        $userStats = [
            'all' => $allUsersCount,
            'customer' => User::where('role', 'customer')->count(),
            'vendor' => User::where('role', 'vendor')->count(),
            'subs' => NewsletterSubscriber::count()
        ];

        return view('admin.email_blast', compact('userStats'));
    }

    /**
     * Send email blast to chosen segment using Symfony StreamedResponse to support live progress bar
     */
    public function sendBlast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'email_body' => 'required|string',
            'target_audience' => 'required|in:all,customer,vendor,subscribers',
        ]);

        $subject = trim($request->input('subject'));
        $content = $request->input('email_body');
        $target = $request->input('target_audience');

        // Fetch recipients
        if ($target === 'customer') {
            $recipients = User::select('email', 'full_name')->where('role', 'customer')->get();
        } elseif ($target === 'vendor') {
            $recipients = User::select('email', 'full_name')->where('role', 'vendor')->get();
        } elseif ($target === 'subscribers') {
            $recipients = NewsletterSubscriber::select('email', \DB::raw('"Valued Subscriber" as full_name'))->get();
        } else {
            // all
            $recipients = User::select('email', 'full_name')
                ->union(NewsletterSubscriber::select('email', \DB::raw('"Valued Subscriber" as full_name')))
                ->get();
        }

        $total = $recipients->count();
        
        $response = new StreamedResponse(function () use ($recipients, $total, $subject, $content) {
            // Re-render the HTML skeleton with progress bar initialized
            echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f8fafc; font-family: sans-serif; padding: 40px; }
        .progress-container { background: white; padding: 30px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: 50px auto; border: 1px solid #e2e8f0; }
        .progress { height: 12px; border-radius: 50px; background: #e2e8f0; }
        .progress-bar { background: linear-gradient(90deg, #6366f1, #a855f7); transition: width 0.3s ease; }
    </style>
</head>
<body>
    <div class="progress-container">
        <h5 class="fw-bold mb-3">DEPLOYING EMAIL CAMPAIGN...</h5>
        <div class="d-flex justify-content-between mb-2">
            <span id="progressStatus" class="small text-muted">Initializing SMTP handshake...</span>
            <span id="progressText" class="fw-bold text-primary">0%</span>
        </div>
        <div class="progress mb-4">
            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
        </div>
        <a href="' . route('admin.email_blast.index') . '" id="backBtn" class="btn btn-outline-secondary w-100 rounded-pill d-none">Go Back to Blast Center</a>
    </div>
    <script>
        function update(percent, status) {
            document.getElementById("progressBar").style.width = percent + "%";
            document.getElementById("progressText").innerText = percent + "%";
            document.getElementById("progressStatus").innerText = status;
        }
        function done(msg) {
            document.getElementById("progressBar").classList.remove("progress-bar-striped", "progress-bar-animated");
            document.getElementById("progressStatus").innerText = msg;
            document.getElementById("backBtn").classList.remove("d-none");
        }
    </script>
';
            echo str_repeat(' ', 1024 * 64);
            ob_flush(); flush();

            $successCount = 0;

            if ($total > 0) {
                foreach ($recipients as $index => $user) {
                    $personalizedBody = str_replace('{name}', $user->full_name, $content);
                    $emailHtml = "<html><body style='font-family:sans-serif; background:#f8fafc; padding:20px;'><div style='max-width:600px; margin:0 auto; background:white; border-radius:24px; overflow:hidden; border:1px solid #e2e8f0;'><div style='background:#6366f1; padding:40px; text-align:center;'><h1 style='color:white; margin:0;'>KURA PRO</h1></div><div style='padding:40px; line-height:1.7; color:#1e293b;'>$personalizedBody</div></div></body></html>";

                    try {
                        Mail::html($emailHtml, function ($message) use ($user, $subject) {
                            $message->to($user->email)
                                ->subject($subject);
                        });
                        $successCount++;
                    } catch (\Exception $e) {
                        // ignore and continue
                    }

                    $percent = round((($index + 1) / $total) * 100);
                    echo "<script>update({$percent}, 'Sending to " . addslashes($user->email) . " (" . ($index + 1) . "/{$total})');</script>";
                    echo str_repeat(' ', 1024 * 64);
                    ob_flush(); flush();

                    usleep(150000); // 0.15s delay
                }
                echo "<script>done('Completed: {$successCount} of {$total} emails processed successfully.');</script>";
            } else {
                echo "<script>done('No recipients found for segment.');</script>";
            }

            echo '</body></html>';
            ob_flush(); flush();
        });

        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }
}
