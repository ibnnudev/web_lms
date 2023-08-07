<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    {{-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
    <link href="https://fonts.cdnfonts.com/css/rubik" rel="stylesheet">


    <!-- Datatable -->
    <link rel="stylesheet" href="https://nightly.datatables.net/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://nightly.datatables.net/responsive/css/responsive.dataTables.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Alert -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    <!-- Jquery UI -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    @stack('css-internal')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="mx-auto">
        <!-- Navbar -->
        <nav class="flex justify-between items-center px-4 py-2 bg-white border-b">
            <!-- Icon Panah untuk Kembali -->
            <a href="#" onclick="window.history.back();" class="text-gray-600 hover:text-gray-800">
                <ion-icon name="arrow-back-outline" class="text-2xl"></ion-icon>
            </a>

            <!-- Judul -->
            <h1 class="text-base font-normal "> Judul Course </h1>
            <!-- Placeholder untuk bagian kanan, misalnya tombol logout, notifikasi, dll. -->
            <div>
                @auth
                    {{-- make dropdown  --}}
                    <div class="inline-flex items-center list-none lg:ml-auto">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex flex-row items-center w-full px-4 py-2 mt-2 text-xs 2xl:text-sm text-left text-black md:w-auto md:inline md:mt-0 hover:text-red-600 focus:outline-none focus:shadow-outline">
                                    <span>
                                        {{ ucwords(Auth::user()->fullname) }}
                                    </span>
                                    <svg fill="currentColor" viewBox="0 0 20 20"
                                        :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                        class="inline w-4 h-4 mt-1 ml-1 transition-transform duration-200 transform md:-mt-1 rotate-0">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link
                                    href="{{ // check if auth user is admin route to dashboard, if role = 4 route to user-dashboarsd
                                        Auth::user()->role == 4 ? route('user.dashboard') : (auth()->user()->role == 1 ? route('admin.dashboard') : '#') }}">
                                    <div class="flex items-center gap-x-2">
                                        <ion-icon class="text-gray-300" name="log-in-outline"></ion-icon>
                                        <span>Dashboard</span>
                                    </div>
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('user.cart')">
                                    <div class="flex items-center gap-x-2">
                                        <ion-icon class="text-gray-300" name="cart-outline"></ion-icon>
                                        <span>Keranjang</span>
                                    </div>
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        <div class="flex items-center gap-x-2">
                                            <ion-icon class="text-gray-300" name="log-out-outline"></ion-icon>
                                            <span>Keluar</span>
                                        </div>
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 list-none lg:ml-auto">
                        <button onclick="window.location.href='{{ route('login') }}'"
                            class="block px-4 py-2 mt-2 text-md text-gray-500 md:mt-0 hover:text-red-600 focus:outline-none focus:shadow-outline">
                            Masuk
                        </button>
                        <button onclick="window.location.href='{{ route('register') }}'"
                            class="inline-flex items-center justify-center px-4 py-2 text-md font-semibold text-white bg-dark rounded-md group focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 hover:bg-slate-700 active:bg-slate-800 active:text-white focus-visible:outline-black">
                            Daftar
                        </button>
                    </div>
                @endauth
            </div>
        </nav>

        <div class="flex h-screen">
            <!-- Sidebar (Materials List) -->
            <div class="md:flex md:flex-shrink-0 ">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col w-64">
                        <div class="flex flex-col flex-grow pt-5 overflow-y-auto bg-white border-r"
                            :class="['hidden', 'md:flex', 'md:flex-shrink-0', { 'hidden': !open }]"
                            id="sidebar-container">
                            <div class="flex flex-col flex-grow px-4">
                                <nav class="flex-1 space-y-1 bg-white">
                                    <div class="border-b mb-6 pb-8 px-4">

                                        <p class="pt-4 text-xl font-semibold text-primary">
                                            List Materi
                                        </p>

                                        <div class="h-1 w-full bg-neutral-200 dark:bg-neutral-600 mt-6">
                                            <div class="h-1 bg-primary" style="width: 45%"></div>
                                        </div>
                                        <p>
                                            <span class="text-sm text-gray-500">Progres</span>
                                            <span class="text-sm text-gray-500 float-right">6%</span>
                                        </p>
                                    </div>
                                    {{-- progres --}}
                                    <ul>
                                        @for ($i = 0; $i < 10; $i++)
                                            <li class="mb-4">

                                                <div
                                                    class="focus:outline-none inline-flex
                                                    items-center w-full px-4 py-2 text-base text-gray-500
                                                    transition duration-200 ease-in-out transform rounded-lg
                                                    focus:shadow-outline hover:bg-gray-100 hover:scale-95
                                                    hover:text-primary text-center justify-center">
                                                    <p> Materi {{ $i + 1 }}
                                                    </p>
                                                </div>
                                            </li>
                                        @endfor
                                    </ul>
                                </nav>
                            </div>
                            <div
                                class="flex
                                                    flex-shrink-0 p-4 px-4 bg-gray-50">
                                <div @click.away="open = false" class="relative inline-flex items-center w-full"
                                    x-data="{ open: false }">
                                    <button @click="open = !open"
                                        class="inline-flex items-center justify-between w-full px-4 py-3 text-lg font-medium text-center text-white transition duration-500 ease-in-out transform rounded-xl hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <span>
                                            <span class="flex-shrink-0 block group">
                                                <div class="flex items-center">
                                                    <div>
                                                        <img class="inline-block object-cover rounded-full h-9 w-9"
                                                            src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=2070&amp;q=80"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-3 text-left">
                                                        <p
                                                            class="text-sm font-medium text-gray-500 group-hover:text-blue-500">
                                                            {{ ucwords(Auth::user()->fullname) }}
                                                        </p>

                                                    </div>
                                                </div>
                                            </span>
                                        </span>
                                        <svg :class="{ 'rotate-180': open, 'rotate-0': !open }"
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="inline w-5 h-5 ml-4 text-black transition-transform duration-200 transform rotate-0"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute bottom-0 z-50 w-full mx-auto mt-2 origin-bottom-right bg-white rounded-xl"
                                        style="display: none">
                                        <div
                                            class="px-2 py-2 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                            <ul>
                                                <li>
                                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-gray-500 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 hover:scale-95 hover:text-blue-500"
                                                        href="{{ route('dashboard') }}">
                                                        <ion-icon class="w-4 h-4 md hydrated" name="body-outline"
                                                            role="img" aria-label="body outline"></ion-icon>
                                                        <span class="ml-4">
                                                            Account
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="inline-flex items-center w-full px-4 py-2 mt-1 text-sm text-gray-500 transition duration-200 ease-in-out transform rounded-lg focus:shadow-outline hover:bg-gray-100 hover:scale-95 hover:text-blue-500"
                                                        href="#">
                                                        <ion-icon class="w-4 h-4 md hydrated"
                                                            name="person-circle-outline" role="img"
                                                            aria-label="person circle outline">
                                                        </ion-icon>
                                                        <span class="ml-4">
                                                            Profile
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col flex-auto w-0 overflow-hidden p-2">
                <div class="flex items-center ">
                    <button id="toggleButton" @click="toggleSidebar()"
                        class="toggle-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <main
                    class="relative flex-1 focus:outline-none lg:w-1/2 md:w-full justify-center mx-auto bg-white rounded-lg overflow-y-auto">
                    <div class="py-6">
                        <div class="px-4 mx-auto 2xl:max-w-7xl sm:px-6 md:px-8">
                            <!-- === Remove and replace with your own content... === -->
                            <div>

                                {{-- show video with iframe --}}
                                <div class="embed-responsive embed-responsive-16by9 relative w-full overflow-hidden rounded-lg shadow-lg"
                                    style="padding-top: 56.25%">
                                    <iframe
                                        class="embed-responsive-item absolute top-0 right-0 bottom-0 left-0 h-full w-full"
                                        src="https://www.youtube.com/embed/rs48iVajzWc" allowfullscreen="true"
                                        data-gtm-yt-inspected-2340190_699="true" id="240632615"></iframe>
                                </div>

                                <div class="h-screen border border-gray-200 border-dashed rounded-lg py-6">
                                    <iframe src="{{ asset('assets/Profile.pdf') }}" frameborder="0" width="100%"
                                        id="pdf-viewer" height="100%"></iframe>
                                </div>
                            </div>
                            <!-- === End ===  -->
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Ion Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Datatable -->
    <script src="https://nightly.datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="https://nightly.datatables.net/responsive/js/dataTables.responsive.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- Ckeditor -->
    <script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>

    <!-- Datepicker -->
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.2.7/pdfobject.min.js"
        integrity="sha512-g16L6hyoieygYYZrtuzScNFXrrbJo/lj9+1AYsw+0CYYYZ6lx5J3x9Yyzsm+D37/7jMIGh0fDqdvyYkNWbuYuA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('select').select2();

        var options = {
            // remove toolbar
            toolbar: {
                show: false
            },
        }

        PDFObject.embed("{{ asset('assets/Profile.pdf') }}", "#pdf-viewer", options);
    </script>

    <script>
        const sidebarContainer = document.getElementById('sidebar-container');
        const toggleButton = document.getElementById('toggleButton');

        toggleButton.addEventListener('click', () => {
            sidebarContainer.classList.toggle('hidden');

        });
    </script>

    @stack('js-internal')
</body>

</html>
