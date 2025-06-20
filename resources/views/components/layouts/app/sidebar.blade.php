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
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                @role('student')
                <flux:navlist.item icon="ticket" :href="route('student.my-tickets')" :current="request()->routeIs('student.my-tickets')" wire:navigate>{{ __('My Tickets') }}</flux:navlist.item>
                @endrole

                @role('teacher')
                <flux:navlist.item icon="user-plus" :href="route('teacher.assign-tickets')" :current="request()->routeIs('teacher.assign-tickets')" wire:navigate>{{ __('Assign Tickets') }}</flux:navlist.item>
                <flux:navlist.item icon="qr-code" :href="route('teacher.scan-tickets')" :current="request()->routeIs('teacher.scan-tickets')" wire:navigate>{{ __('Scan Tickets') }}</flux:navlist.item>
                @endrole
            </flux:navlist.group>

            @role('admin')
            <flux:navlist.group :heading="__('Manage Concerts & Tickets')" class="grid">
                <flux:navlist.item icon="chart-bar" :href="route('admin.ticket-sales')" :current="request()->routeIs('admin.ticket-sales')" wire:navigate>{{ __('Sales') }}</flux:navlist.item>
                <flux:navlist.item icon="musical-note" :href="route('admin.concerts')" :current="request()->routeIs('admin.concerts*')" wire:navigate>{{ __('Concerts') }}</flux:navlist.item>
                <flux:navlist.item icon="ticket" :href="route('admin.tickets')" :current="request()->routeIs('admin.tickets*')" wire:navigate>{{ __('Tickets') }}</flux:navlist.item>
            </flux:navlist.group>
            <flux:navlist.group :heading="__('Administration')" class="grid">
                <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>{{ __('User & Roles') }}</flux:navlist.item>
            </flux:navlist.group>
            @endrole
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