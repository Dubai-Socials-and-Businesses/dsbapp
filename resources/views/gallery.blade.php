@extends('layouts.app')

@section('content')
    <section class="py-5 bg-teal">
        <div class="container">
            <div class="row align-center">
                <div class="col-12 col-lg-9">
                    <h2 class="fw-bold text-navy display-4">The Spark</h2>
                    <p class="text-dark">
                        Step inside a community that’s rewriting the rulebook of business networking. At DSB, we don’t chase followers — we build friendships that fuel growth. Every handshake, every introduction, every smile has a story behind it. You don’t just visit DSB. You feel it
                    </p>
                </div>
                <div class="col-12 col-lg-3">
                    <v-btn color="navy" class="fw-bold">Join The Movement</v-btn>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 bg-mint">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <h2 class="fw-bold text-navy display-4">The Circle</h2>
                    <p class="text-dark">
                        More than a community — it’s a circle of trust, talent, and transformation. Founded by <strong>Rakhee Mansukhani,</strong>  UAE’s No. 1 Resume Writer & Career Coach, DSB brings together entrepreneurs, professionals, and creators who believe in real-world collaboration
                    </p>
                    <p class="h5 text-navy">
                        From coffee-table introductions to business partnerships — every connection here begins with
                        authenticity
                    </p>
                    <ul>
                        <li>We meet.</li>
                        <li>We learn.</li>
                        <li>We grow.</li>
                    </ul>
                    <p>
                        That’s the DSB way
                    </p>
                    <p class="border-start border-dark border-4 ps-4">
                        Whether you wanna work or network — you just need us
                    </p>
                    <v-btn href="{{route('gallery')}}" color="teal" variant="outlined" class="fw-bold">Meet the Community</v-btn>
                </div>
                <div class="col-12 col-lg-6 position-relative">
                    <Box class="stack" data-aos="fade-up">
                        <img src="{{asset('/dubai1a.jpg')}}" class="polaroid" alt="People networking at DSB meetup" width="320" height="320"/>
                        <img src="{{asset('/dubai1b.jpg')}}" class="polaroid polaroid--2" alt="Vendors showcasing products" width="320" height="320"/>
                    </Box>
                </div>
            </div>
        </div>
    </section>
@endsection
