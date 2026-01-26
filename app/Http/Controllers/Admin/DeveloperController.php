<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function index()
    {
        $developer = [
            'name' => 'Muhammad Iftikhar',
            'role' => 'Full Stack Developer',
            'email' => 'developer@example.com',
            'phone' => '+92 300 1234567',
            'whatsapp' => '+92 300 1234567',
            'company' => 'Alisha Platform',
            'website' => 'https://alisha.com',
            'github' => 'https://github.com/developer',
            'linkedin' => 'https://linkedin.com/in/developer',
            'bio' => 'Experienced Full Stack Developer specializing in Laravel, Vue.js, and modern web technologies. Creator of the Alisha e-commerce platform.',
            'avatar' => asset('images/developer-avatar.png'),
            'skills' => [
                'Backend' => ['PHP', 'Laravel', 'MySQL', 'PostgreSQL', 'Redis', 'REST APIs'],
                'Frontend' => ['JavaScript', 'Vue.js', 'Alpine.js', 'Tailwind CSS', 'Bootstrap'],
                'DevOps' => ['Linux', 'Apache', 'Nginx', 'Docker', 'Git', 'CI/CD'],
                'Tools' => ['VS Code', 'PHPStorm', 'Postman', 'Figma', 'Jira'],
            ],
            'projects' => [
                [
                    'name' => 'Alisha E-commerce Platform',
                    'description' => 'A multi-vendor e-commerce platform for women entrepreneurs',
                    'technologies' => ['Laravel', 'Alpine.js', 'Tailwind CSS', 'MySQL'],
                    'status' => 'Active',
                ],
            ],
            'support' => [
                'email' => 'support@alisha.com',
                'response_time' => '24-48 hours',
                'working_hours' => 'Mon-Fri: 9:00 AM - 6:00 PM (PKT)',
            ],
        ];

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
        ];

        return view('admin.developer.index', compact('developer', 'systemInfo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'bio' => 'nullable|string',
        ]);

        // Store in settings or config
        foreach ($validated as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'developer_' . $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Developer information updated successfully!');
    }
}
