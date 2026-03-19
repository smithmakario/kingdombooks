<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaystackLogController extends Controller
{
    public function index(): View
    {
        $logPath = storage_path('logs/laravel.log');
        $entries = [];

        if (File::exists($logPath)) {
            $contents = File::get($logPath);
            $lines = preg_split('/\r\n|\r|\n/', $contents) ?: [];

            $paystackLines = array_values(array_filter($lines, function (string $line): bool {
                return Str::contains(Str::lower($line), 'paystack');
            }));

            $entries = array_slice(array_reverse($paystackLines), 0, 300);
        }

        return view('admin.paystack-logs', [
            'entries' => $entries,
        ]);
    }
}
