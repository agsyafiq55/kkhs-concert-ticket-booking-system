<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <!-- Dashboard always visible -->
            <flux:navlist.group :heading="__('Overview')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                @can('view ticket sales')
                <flux:navlist.item icon="chart-bar" :href="route('admin.ticket-sales')" :current="request()->routeIs('admin.ticket-sales')" wire:navigate>{{ __('Monitor Sales') }}</flux:navlist.item>
                @endcan

                @can('view own tickets')
                <flux:navlist.item icon="ticket" :href="route('student.my-tickets')" :current="request()->routeIs('student.my-tickets')" wire:navigate>{{ __('My Tickets') }}</flux:navlist.item>
                @endcan
            </flux:navlist.group>

            <!-- 1. Sell Tickets -->
            @if(auth()->user()->can('assign tickets') || auth()->user()->can('sell vip tickets'))
            <flux:navlist.group :heading="__('Sell Tickets')" class="grid">
                @can('assign tickets')
                <flux:navlist.item icon="user-plus" :href="route('teacher.assign-tickets')" :current="request()->routeIs('teacher.assign-tickets')" wire:navigate>{{ __('Sell Tickets') }}</flux:navlist.item>
                @endcan
                
                @can('sell vip tickets')
                <flux:navlist.item icon="star" :href="route('admin.vip-ticket-sales')" :current="request()->routeIs('admin.vip-ticket-sales')" wire:navigate>{{ __('Sell VIP Tickets') }}</flux:navlist.item>
                @endcan
            </flux:navlist.group>
            @endif

            <!-- 2. Used for during Concert Day -->
            @if(auth()->user()->can('scan tickets') || auth()->user()->can('scan walk-in sales'))
            <flux:navlist.group :heading="__('Used for during Concert Day')" class="grid">
                @can('scan tickets')
                <flux:navlist.item icon="qr-code" :href="route('teacher.scan-tickets')" :current="request()->routeIs('teacher.scan-tickets')" wire:navigate>{{ __('Entry Scanner') }}</flux:navlist.item>
                @endcan

                @can('scan walk-in sales')
                <flux:navlist.item icon="currency-dollar" :href="route('teacher.scan-walk-in-sales')" :current="request()->routeIs('teacher.scan-walk-in-sales')" wire:navigate>{{ __('Walk-in Sales Scanner') }}</flux:navlist.item>
                @endcan
            </flux:navlist.group>
            @endif

            <!-- 3. Concert Management -->
            @can('view concerts')
            <flux:navlist.group :heading="__('Concert Management')" class="grid">
                <flux:navlist.item icon="musical-note" :href="route('admin.concerts')" :current="request()->routeIs('admin.concerts*')" wire:navigate>{{ __('Manage Concert') }}</flux:navlist.item>
            </flux:navlist.group>
            @endcan

            <!-- 4. Ticket Management -->
            @if(auth()->user()->can('view tickets') || auth()->user()->can('manage walk-in tickets'))
            <flux:navlist.group :heading="__('Ticket Management')" class="grid">
                @can('view tickets')
                <flux:navlist.item icon="ticket" :href="route('admin.tickets')" :current="request()->routeIs('admin.tickets*')" wire:navigate>{{ __('Manage Tickets') }}</flux:navlist.item>
                @endcan

                @can('manage walk-in tickets')
                <flux:navlist.item icon="user-group" :href="route('admin.walk-in-tickets')" :current="request()->routeIs('admin.walk-in-tickets')" wire:navigate>{{ __('Manage Walk-in Tickets') }}</flux:navlist.item>
                @endcan
            </flux:navlist.group>
            @endif

            <!-- 5. Admin Controls -->
            @if(auth()->user()->can('manage roles') || auth()->user()->can('bulk upload students'))
            <flux:navlist.group :heading="__('Admin Controls')" class="grid">
                @can('manage roles')
                <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
                <flux:navlist.item icon="shield-check" :href="route('admin.roles-permissions')" :current="request()->routeIs('admin.roles-permissions')" wire:navigate>{{ __('Roles and Permissions') }}</flux:navlist.item>
                @endcan

                @can('bulk upload students')
                <flux:navlist.item icon="user-plus" :href="route('admin.bulk-student-upload')" :current="request()->routeIs('admin.bulk-student-upload')" wire:navigate>{{ __('Bulk Student Registration') }}</flux:navlist.item>
                @endcan
            </flux:navlist.group>
            @endif

            <!-- Help & Support -->
            <flux:navlist.group :heading="__('Help & Support')" class="grid">
                <flux:navlist.item icon="book-open-text" :href="route('help')" :current="request()->routeIs('help')" wire:navigate>{{ __('Help & Documentation') }}</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:menu.radio.group>
            <div class="px-2 py-1.5 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                {{ __('You are logged in as') }}
            </div>
            @foreach(auth()->user()->roles as $role)
            <flux:menu.item disabled icon="shield-check">
                {{ ucfirst($role->name) }}
            </flux:menu.item>
            @endforeach
        </flux:menu.radio.group>

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <div class="px-2 py-1.5 text-xs font-medium text-zinc-500 dark:text-zinc-400">
                        {{ __('Roles') }}
                    </div>
                    @foreach(auth()->user()->roles as $role)
                    <flux:menu.item disabled icon="shield-check">
                        {{ ucfirst($role->name) }}
                    </flux:menu.item>
                    @endforeach
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>