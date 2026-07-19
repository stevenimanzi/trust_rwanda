<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdInquiry;
use App\Models\ActiveAd;
use App\Models\PushNotification;
use App\Models\AdAnalytic;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display Lead Pipeline & inquiries
     */
    public function index()
    {
        $inquiries = AdInquiry::orderBy('created_at', 'desc')->get();
        return view('admin.ads', compact('inquiries'));
    }

    /**
     * Deploy campaign from pipeline
     */
    public function deployCampaign(Request $request)
    {
        $request->validate([
            'ad_title' => 'required|string|max:255',
            'ad_link' => 'required|url',
            'ad_type' => 'required|in:banner,popup',
        ]);

        ActiveAd::create([
            'title' => strip_tags($request->input('ad_title')),
            'target_url' => $request->input('ad_link'),
            'content_type' => 'text',
            'placement' => $request->input('ad_type') === 'popup' ? 'modal_popup' : 'banner_top',
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Campaign Live! Ad is now being pushed to screens.");
    }

    /**
     * Update inquiry lead status
     */
    public function updateInquiryStatus(Request $request)
    {
        $inquiry = AdInquiry::findOrFail($request->input('inquiry_id'));
        $inquiry->update(['status' => $request->input('status')]);

        return redirect()->back()->with('success', "Lead status successfully updated.");
    }

    /**
     * Active campaigns manager tab screen
     */
    public function manage()
    {
        $ads = ActiveAd::select('active_ads.*')
            ->selectSub(function ($q) {
                $q->selectRaw('count(*)')
                    ->from('ad_analytics')
                    ->whereColumn('ad_analytics.ad_id', 'active_ads.id')
                    ->whereRaw('date(logged_at) = date("now")'); // SQLite compatibility for curdate
            }, 'today_views')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications = PushNotification::orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.ads_manage', compact('ads', 'notifications'));
    }

    /**
     * Create promotional advertisement
     */
    public function createAd(Request $request)
    {
        $request->validate([
            'ad_title' => 'required|string|max:255',
            'ad_description' => 'nullable|string',
            'content_type' => 'required|in:image,video,html,text',
            'placement' => 'required|in:banner_top,banner_middle,banner_bottom,sidebar,modal_popup',
            'target_url' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'priority' => 'required|integer|min:0|max:100',
            'content_file' => 'nullable|file|max:10240',
            'html_content' => 'nullable|string',
        ]);

        $contentUrl = '';
        $htmlContent = '';

        if (in_array($request->input('content_type'), ['image', 'video'])) {
            if ($request->hasFile('content_file')) {
                $file = $request->file('content_file');
                $filename = 'ad_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/uploads/ads'), $filename);
                $contentUrl = 'assets/uploads/ads/' . $filename;
            }
        } elseif ($request->input('content_type') === 'html') {
            $htmlContent = $request->input('html_content');
        }

        ActiveAd::create([
            'title' => trim($request->input('ad_title')),
            'description' => trim($request->input('ad_description')),
            'content_type' => $request->input('content_type'),
            'content_url' => $contentUrl,
            'html_content' => $htmlContent,
            'placement' => $request->input('placement'),
            'target_url' => $request->input('target_url'),
            'start_date' => $request->input('start_date') . ' 00:00:00',
            'end_date' => $request->input('end_date') . ' 23:59:59',
            'status' => 'scheduled',
            'priority' => (int)$request->input('priority'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "✅ Advertisement created successfully and scheduled for " . $request->input('start_date'));
    }

    /**
     * Update ad status (active, inactive, scheduled)
     */
    public function updateAdStatus(Request $request)
    {
        $ad = ActiveAd::findOrFail($request->input('ad_id'));
        $ad->update(['status' => $request->input('status')]);

        return redirect()->back()->with('success', "✅ Ad status updated to " . strtoupper($request->input('status')));
    }

    /**
     * Delete ad campaign
     */
    public function deleteAd(Request $request)
    {
        $ad = ActiveAd::findOrFail($request->input('ad_id'));

        if ($ad->content_url) {
            $filePath = public_path($ad->content_url);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $ad->delete();
        return redirect()->back()->with('success', "✅ Advertisement deleted successfully");
    }

    /**
     * Push notifications to targets
     */
    public function pushNotification(Request $request)
    {
        $request->validate([
            'notif_title' => 'required|string|max:255',
            'notif_message' => 'required|string',
            'target_users' => 'required|in:all,vendors,customers,guests',
            'action_url' => 'nullable|url',
            'schedule_at' => 'nullable|date',
        ]);

        $scheduleAt = $request->input('schedule_at');

        $notif = PushNotification::create([
            'title' => trim($request->input('notif_title')),
            'message' => trim($request->input('notif_message')),
            'action_url' => $request->input('action_url'),
            'target_users' => $request->input('target_users'),
            'status' => $scheduleAt ? 'scheduled' : 'sent',
            'scheduled_at' => $scheduleAt ? $scheduleAt . ':00' : null,
            'sent_at' => !$scheduleAt ? now() : null,
            'created_by' => auth()->id(),
        ]);

        $msg = "✅ Notification " . ($scheduleAt ? "scheduled" : "sent") . " successfully!";

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => $msg]);
        }

        return redirect()->back()->with('success', $msg);
    }
}
