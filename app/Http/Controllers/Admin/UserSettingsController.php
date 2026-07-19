<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserSettingsController extends Controller
{
    /**
     * Display configuration screen
     */
    public function index()
    {
        $userId = auth()->id();
        $admin = User::findOrFail($userId);

        $isMaintActive = SystemSetting::where('setting_key', 'maintenance_mode')->value('setting_value') === '1';

        $settingsRows = SystemSetting::all();
        $systemSettings = [];
        foreach ($settingsRows as $row) {
            $systemSettings[$row->setting_key] = $row->setting_value;
        }

        return view('admin.settings', compact('admin', 'isMaintActive', 'systemSettings'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $userId = auth()->id();
        $admin = User::findOrFail($userId);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
        ]);

        $admin->update([
            'full_name' => trim($request->input('full_name')),
            'email' => trim($request->input('email')),
        ]);

        return redirect()->back()->with('success', "Administrator profile successfully updated.");
    }

    /**
     * Change admin password
     */
    public function changePassword(Request $request)
    {
        $userId = auth()->id();
        $admin = User::findOrFail($userId);

        $request->validate([
            'current_pass' => 'required|string',
            'new_pass' => 'required|string|min:6',
            'confirm_pass' => 'required|same:new_pass',
        ]);

        if (!Hash::check($request->input('current_pass'), $admin->password)) {
            return redirect()->back()->with('error', "Incorrect current password.");
        }

        $admin->update([
            'password' => Hash::make($request->input('new_pass')),
        ]);

        return redirect()->back()->with('success', "Security credentials updated.");
    }

    /**
     * Save branding logos
     */
    public function saveBranding(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|mimes:ico,png,jpg|max:512',
        ]);

        $didUpload = false;

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = 'site_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/logos'), $filename);

            SystemSetting::updateOrCreate(
                ['setting_key' => 'site_logo'],
                ['setting_value' => $filename]
            );
            $didUpload = true;
        }

        if ($request->hasFile('site_favicon')) {
            $file = $request->file('site_favicon');
            $filename = 'site_favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/logos'), $filename);

            SystemSetting::updateOrCreate(
                ['setting_key' => 'site_favicon'],
                ['setting_value' => $filename]
            );
            $didUpload = true;
        }

        if ($didUpload) {
            return redirect()->back()->with('success', "Branding assets successfully updated.");
        }

        return redirect()->back()->with('error', "No assets uploaded.");
    }

    /**
     * Save general business settings
     */
    public function saveBusiness(Request $request)
    {
        $settings = $request->only([
            'site_name', 'support_email', 'support_phone',
            'currency_code', 'commission_percent', 'min_order_amount',
            'meta_description'
        ]);

        $settings['vendor_auto_approval'] = $request->has('vendor_auto_approval') ? '1' : '0';

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => (string)($value ?? '')]
            );
        }

        return redirect()->back()->with('success', "Business settings updated successfully.");
    }

    /**
     * Toggle Maintenance mode via AJAX
     */
    public function toggleMaintenance(Request $request)
    {
        $status = $request->input('maint_status') === 'true' ? '1' : '0';

        SystemSetting::updateOrCreate(
            ['setting_key' => 'maintenance_mode'],
            ['setting_value' => $status]
        );

        return response()->json([
            'status' => 'success',
            'mode' => $status
        ]);
    }

    /**
     * Database Backup download
     */
    public function backupDatabase()
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if (file_exists($dbPath)) {
                return response()->download($dbPath, 'trust_rwanda_sqlite_backup_' . date('Y-m-d_H-i-s') . '.sqlite');
            }
            return redirect()->back()->with('error', "SQLite database file not found.");
        }

        // Fallback for MySQL/MariaDB export
        try {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            $sqlScript = "-- Trust Rwanda Database Backup\n-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

            foreach ($tables as $table) {
                // Get CREATE TABLE statement
                $createObj = DB::select("SHOW CREATE TABLE `{$table}`");
                if (!empty($createObj)) {
                    $createArray = (array)$createObj[0];
                    $createTableSql = $createArray['Create Table'] ?? '';
                    $sqlScript .= "DROP TABLE IF EXISTS `{$table}`;\n";
                    $sqlScript .= $createTableSql . ";\n\n";
                }

                // Get rows
                $rows = DB::table($table)->get();
                foreach ($rows as $row) {
                    $rowArray = (array)$row;
                    $keys = array_keys($rowArray);
                    $values = array_map(function ($val) {
                        if ($val === null) return "NULL";
                        return DB::getPdo()->quote($val);
                    }, array_values($rowArray));
                    
                    $sqlScript .= "INSERT INTO `{$table}` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $values) . ");\n";
                }
                $sqlScript .= "\n";
            }

            $filename = 'trust_rwanda_backup_' . date('Y-m-d_H-i-s') . '.sql';
            return response($sqlScript)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Transfer-Encoding', 'Binary')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Database backup failed: " . $e->getMessage());
        }
    }
}
