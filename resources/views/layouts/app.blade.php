<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteName = "Dubai Socials and Businesses";
        $title = "Dubai Socials and Businesses";
        $description = "UAE's #1 Networking Platform Where genuine connections spark real growth. Business. Referrals. Community. Diversify | Strategize | Bond #uae";
        $imageUrl = "https://dummyimage.com/1200x500/1B2A41/00BFA6&text=.$siteName";
    @endphp

    <title>DSB | {{ $title }}</title>
    <meta name="application-name" content="{{$siteName}}">
    <meta name="description" content="{{$description}}"/>
    <meta name="robots" content="index, follow">
    <link rel="icon" href="{{asset('/favicon.ico')}}" sizes="48x48" type="image/x-icon"/>
    <link rel="icon" href="{{asset('/favicon-32x32.png')}}" sizes="32x32" type="image/png"/>
    <link rel="canonical" href="{{url()->current()}}">
    <meta property="og:site_name" content="{{$siteName}}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="DSB | {{$title}}">
    <meta property="og:description" content="DSB | {{$description}}">
    <meta property="og:url" content="DSB | {{$description}}">
    <meta property="og:image" content="{{$imageUrl}}">
    <meta property="og:image:secure_url" content="{{$imageUrl}}">
    <meta property="og:image:alt" content="Dubai Socials and Businesses">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="DSB | {{$title}}">
    <meta name="twitter:description" content="DSB | {{$description}}">
    <meta property="twitter:image" content="{{$imageUrl}}">

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "DSB | {{ $title }}",
    "description": "DSB | {{ $description }}",
    "url": "{{ url()->current() }}",
    "logo": "{{ asset('/logo.png') }}",
    "sameAs": [
        "http://facebook.com/groups/dubaisocialsandbusinesses",
        "https://www.instagram.com/dubaisocialsandbusinesses?igsh=czQydzhmczgwcWpv",
        "https://chat.whatsapp.com/G4xxoilGeFSChVFWFlwsyd",
        "https://www.linkedin.com/company/dubaisocialsandbusinesses",
        "https://youtube.com/@dubaisocialsandbusinesses?si=F_O7vhMhO2Qf1Z-B"
    ],
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+971555563900",
        "contactType": "Customer Support",
        "areaServed": "AE",
        "availableLanguage": ["English"]
    }
}
    </script>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Scripts -->

    @vite(['resources/sass/app.scss'])
</head>
<body>
<v-app id="app" v-cloak>
    <nav class="navbar navbar-expand-md navbar-dark bg-navy shadow-sm py-0">
        <div class="container">
            <a class="navbar-brand d-flex gap-2 align-center" href="{{ url('/') }}">
                <img src="{{asset('/logo.png')}}" width="60" height="60" alt="logo">
                <span class="logo">
                        <span class="brand">Dubai</span>
                        <span class="subbrand">Socials & Businesses</span>
                    </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mx-auto fw-semibold">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('homepage') }}">{{ __('The Spark') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('about') }}">{{ __('The Circle') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('gallery') }}">{{ __('Gallery') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('events') }}">{{ __('The Vibe') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('blogs') }}">{{ __('Blogs') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('contact') }}">{{ __('The Gateway') }}</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer class="bg-navy pt-5">
        <div class="container">
            <h2 class="fw-bold display-4 text-center">The Gateway</h2>
            <p class="text-center">
                Step Into the DSB Ecosystem. Ready to be seen, heard, and connected? Join a
                movement built for doers, dreamers, and decision-makers
            </p>
            <div class="d-flex justify-center gap-3 mt-4 flex-wrap">
                <v-btn href="https://chat.whatsapp.com/G4xxoilGeFSChVFWFlwsyd" target="_blank" color="green">Join
                    WhatsApp Group
                </v-btn>
                <v-btn href="https://youtube.com/@dubaisocialsandbusinesses?si=F_O7vhMhO2Qf1Z-B" target="_blank"
                       color="red">Join You Tube
                </v-btn>
                <v-btn href="http://facebook.com/groups/dubaisocialsandbusinesses" target="_blank" variant="elevated"
                       color="#1864ab">Follow on Facebook
                </v-btn>
                <v-btn href="https://www.instagram.com/dubaisocialsandbusinesses?igsh=czQydzhmczgwcWpv" target="_blank"
                       variant="elevated" color="#5f3dc4">Follow on Instagram
                </v-btn>
                <v-btn color="teal" variant="outlined">Become a Partner</v-btn>
                {{--                    <RegisterModal button_text="Become a Partner" variant="outline"></RegisterModal>--}}
            </div>
        </div>
        <div class="container mt-3">
            <p class="text-center text-teal small mb-0">Copyright © 2025 Dubai Socials and Businesses - All Rights
                Reserved. | Operated under <a
                    class="link-light" href="https://www.rakheemansukhani.com/" target="_blank">Rakhee Mansukhani</a>
                Portal | DED License No. 1069326</p>
        </div>
    </footer>
    <div class="ticker">
        <div class="ticker__track" aria-hidden="true">Network • Collaborate • Grow • Repeat —
            #DubaiSocialsAndBusinesses •
        </div>
        <div class="ticker__track" aria-hidden="true">Network • Collaborate • Grow • Repeat —
            #DubaiSocialsAndBusinesses •
        </div>
    </div>
</v-app>
<script src=" https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js "></script>
<link href=" https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css " rel="stylesheet">
@vite(['resources/js/app.js'])
</body>
</html>
