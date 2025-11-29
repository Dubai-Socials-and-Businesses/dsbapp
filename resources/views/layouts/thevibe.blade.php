<section class="bg-navy text-white py-5">
    <div class="container text-center">
        <h2 data-aos="fade-up" class="display-4 fw-bold">The Vibe</h2>
        <h3 data-aos="fade-up" class="h1 fw-bold">We don’t host events — we curate experiences</h3>
        <p data-aos="fade-up">
            Think less ‘conference’, more energy
            room. Every month, DSB’s meetups bring together business owners, professionals, and
            brands under one roof — to exchange ideas, celebrate wins, and discover collaborations
            waiting to happen. No stage. No ego. Just pure exchange.
        </p>
        <div class="row g-5 justify-center">
            <div class="col-md-3" data-aos="zoom-in-up">
                <v-card class="text-white rounded-xl" style="background:rgba(255,255,255,0.1)">
                    <v-card-text class="position-relative" style="aspect-ratio: 1">
                        <h2 class="h5">Mind Match</h2>
                        <v-img src="{{asset('/mind1a.jpg')}}" cover style="aspect-ratio: 1"/>
                    </v-card-text>
                    <h3 class="h5">Quiz Winners</h3>
                </v-card>
            </div>
            <div class="col-md-3" data-aos="zoom-in-up">
                <v-card class="text-white rounded-xl" style="background:rgba(255,255,255,0.1)">
                    <v-card-text class="position-relative" style="aspect-ratio: 1">
                        <h2 class="h5">Introductions</h2>
                        <v-img src="{{asset('/mind1b.jpg')}}" cover style="aspect-ratio: 1"/>
                    </v-card-text>
                    <h3 class="h5">That Inspire</h3>
                </v-card>
            </div>
            <div class="col-md-3" data-aos="zoom-in-up">
                <v-card class="text-white rounded-xl" elevation="12" style="background:rgba(255,255,255,0.1)">
                    <v-card-text class="position-relative" style="aspect-ratio: 1">
                        <h2 class="h5">Moments</h2>
                        <v-img src="{{asset('/mind1c.jpg')}}" style="aspect-ratio: 1"/>
                    </v-card-text>
                    <h3 class="h5">That Matters</h3>
                </v-card>
            </div>
        </div>
        <v-btn class="mt-8" href="{{route('events')}}" color="teal" variant="outlined" data-aos="fade-up">Join the Next Meetup</v-btn>
    </div>
</section>
