<!DOCTYPE html>
@php

    use Illuminate\Support\Facades\Request;
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        @include('partials.head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @PwaHead <!-- PWA meta tags directive -->
    <script src="https://cdn.jsdelivr.net/npm/dexie@3.2.2/dist/dexie.min.js"></script>
    <script>
  const db = new Dexie("FarmEntrances");
 db.version(3).stores({
  // Basic Farm Info
  farms: "farmcode,community,farmname,farmstate,inspectorid",

  //Main Form Submission
  forms: "++id,farmcode,farmname,community,crop,cropvariety,regdate,address,sync_status",

  //Volumes Sold (Section B)
  volumes: "++id,farmcode,season,volume", 

  // Agrochemical Use (Section D)
  agrochemicals: "++id,farmcode,herbicide,quantity,applier,hectare,season",

  // Other Cultivated Crops (Section E)
  otherCrops: "++id,farmcode,plotName,crop,area,location,season",

  // Report Details
  reports: "reportid,reportname,reportstate", 

  // Report Sections
  reportSections: "sectionid,reportid,sectionname,sectionseq,sectionstate",

  // Report Questions
  reportQuestions: "questionid,sectionid,reportid,questionseq,question,questiontype,questionstate",

  // Inspection Sheet Header
  inspectionSheet: "++id,farmcode,reportid,inspectorid,season",

  // Inspection Sheet Answers
  inspectionAnswers: "++id,farmcode,inspectionsheetid,questionid,answer,comment"
});
</script>
<script src="/js/offline-handler.js"></script>


    </head>

    <style>
        .c-sidebar a {
            color: #080808;
            border-color:  #FFCA28;
            text-decoration:none;
            touch-action: manipulation;

        }

        /* Bootstrap z-index goes up to 1040 (modal backdrop).
           Force the Flux sidebar above all Bootstrap layers on tablet/mobile. */
        [data-flux-sidebar] {
            z-index: 1100 !important;
        }

        [data-flux-sidebar-backdrop] {
            z-index: 1099 !important;
        }
        .c-navitem-selected {
            background: #388E3C
;

        }
                .c-sidebar a:hover {
            background: #388E3C
;

        }
    </style>
    @php
         Auth::check();
        $user = Auth::user();
    @endphp
   <body class="min-h-screen bg-white dark:bg-zinc-800" style="background: url('{{Request::root()}}/assets/images/triangles.svg') repeat 0 100%, -webkit-gradient(linear, left top, right top, from(#f2eadc), to(#F5F0E6)) 0% 0% no-repeat padding-box">
    <flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700" style="background-color: #826611 ; color: #f5f0e6">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="#"
                logo="{{Request::root()}}/assets/images/BandRlogo.png"
                logo:dark="{{Request::root()}}/assets/images/BandRlogo.png"
                name="BandR Spice IMS"
            />
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>
        <flux:sidebar.nav class="c-sidebar">
            <flux:sidebar.item class="c-sidebar mb-2" icon="chart-bar" href="{{route('dashboard')}}">Dashboard</flux:sidebar.item>
            <flux:sidebar.item class="c-sidebar mb-2" icon="users" href="{{route('index')}}">Farmers</flux:sidebar.item>
            <flux:sidebar.item class="c-sidebar mb-2" icon="document-text" href="{{route('onboarding')}}">Farm Entrance</flux:sidebar.item>
            <flux:sidebar.item class="c-sidebar mb-2" icon="magnifying-glass" href="{{route('inspection')}}">Farm Inspections</flux:sidebar.item>

            @if ($user->roles=='ADMINISTRATOR')
            <flux:sidebar.group expandable icon="clipboard-document-check" heading="Administration Functions" class="grid c-sidebar" style="color: #f5f0e6">
                <flux:sidebar.item href="{{route('viewreports')}}">Report Configuration</flux:sidebar.item>
                <flux:sidebar.item  href="{{route('user_admin')}}">User Administration</flux:sidebar.item>
                <flux:sidebar.item   href="{{route('codeadmin')}}">Code Administration</flux:sidebar.item>
                <flux:sidebar.item  href="{{route('iapproval')}}">Inspection Review</flux:sidebar.item>
            </flux:sidebar.group>
            @endif

        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <flux:sidebar.nav>
            <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
        </flux:sidebar.nav>
        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile avatar="" name="{{$user->name}}" />
            <flux:menu>
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
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="start">
            <flux:profile avatar="/img/demo/user.png" />
            <flux:menu>
                <flux:menu.separator />
                 <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                <flux:menu.item icon="arrow-right-start-on-rectangle">
                   <button type="submit">{{ __('Log Out') }}</button>
                </flux:menu.item>
                 </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>
    <flux:main>
        <flux:heading size="xl" level="1"></flux:heading>
        <flux:text class="mt-2 mb-6 text-base"></flux:text>
        <flux:separator variant="subtle" />
    </flux:main>


        {{ $slot }}

        @fluxScripts
        @RegisterServiceWorkerScript <!-- Service worker registration -->
    </body>
<script>
    const checkOnlineStatus = async () => {
        try {
            const response = await fetch('/ping', { method: 'GET' });
            return response.ok;
        } catch (err) {
            return false;
        }
    };

    window.addEventListener('online', () => {
        console.log('Browser thinks we\'re online...');
        checkOnlineStatus().then(isOnline => {
            if (isOnline) {
                console.log('Confirmed: Laravel server is reachable');
                confirm("Connectivity restored");
                
                // Trigger UI update or sync
            } else {
                console.log('Still no connectivity despite online flag');
            }
        });
    });

    window.addEventListener('offline', () => {
        console.log('Offline detected!');
        // Trigger offline UI
    });
</script> 


</html>
