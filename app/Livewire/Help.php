<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Help extends Component
{
    public $activeSection = 'overview';
    public $userRole;
    public $availableSections = [];

    public function mount()
    {
        $this->userRole = Auth::user()->roles->first()->name ?? 'student';
        $this->setupAvailableSections();
    }

    private function setupAvailableSections()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $this->availableSections = [
            'overview' => [
                'title' => 'System Overview',
                'icon' => 'layout-grid',
                'available_for' => ['super-admin', 'admin', 'teacher', 'student']
            ],
        ];

        // Super Admin and Admin sections
        if ($user->hasAdminAccess()) {
            $this->availableSections['concerts'] = [
                'title' => 'Managing Concerts',
                'icon' => 'musical-note',
                'available_for' => ['super-admin', 'admin']
            ];
            $this->availableSections['tickets'] = [
                'title' => 'Managing Tickets',
                'icon' => 'ticket',
                'available_for' => ['super-admin', 'admin']
            ];
            $this->availableSections['walk_in'] = [
                'title' => 'Walk-in Tickets',
                'icon' => 'user-group',
                'available_for' => ['super-admin', 'admin']
            ];
            $this->availableSections['sales_monitoring'] = [
                'title' => 'Sales Monitoring',
                'icon' => 'chart-bar',
                'available_for' => ['super-admin', 'admin']
            ];
        }

        // Super Admin specific sections
        if ($user->isSuperAdmin()) {
            $this->availableSections['user_management'] = [
                'title' => 'User Management',
                'icon' => 'users',
                'available_for' => ['super-admin']
            ];
        }

        // Teacher sections
        if ($user->isTeacher() || $user->hasAdminAccess()) {
            $this->availableSections['selling_tickets'] = [
                'title' => 'Selling Tickets to Students',
                'icon' => 'user-plus',
                'available_for' => ['teacher', 'super-admin', 'admin']
            ];
            $this->availableSections['scanning_entry'] = [
                'title' => 'Scanning for Entry',
                'icon' => 'qr-code',
                'available_for' => ['teacher', 'super-admin', 'admin']
            ];
            $this->availableSections['walk_in_sales'] = [
                'title' => 'Walk-in Sales Scanner',
                'icon' => 'currency-dollar',
                'available_for' => ['teacher', 'super-admin', 'admin']
            ];
        }

        // Student sections (available to all users)
        $this->availableSections['my_tickets'] = [
            'title' => 'Managing My Tickets',
            'icon' => 'ticket',
            'available_for' => ['student', 'super-admin', 'admin', 'teacher']
        ];

        // Add troubleshooting for all users
        $this->availableSections['troubleshooting'] = [
            'title' => 'Troubleshooting',
            'icon' => 'wrench-screwdriver',
            'available_for' => ['super-admin', 'admin', 'teacher', 'student']
        ];
    }

    public function setActiveSection($section)
    {
        if (isset($this->availableSections[$section])) {
            $this->activeSection = $section;
        }
    }

    public function render()
    {
        return view('livewire.help');
    }
} 