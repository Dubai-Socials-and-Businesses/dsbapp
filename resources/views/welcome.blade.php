@extends('layouts.app')

@section('content')
    <div class="position-relative mhero">
        <video class="hero__bg" autoPlay muted loop playsInline poster="{{asset('/community1.jpg')}}">
            <source src="{{asset('/dubai1.mp4')}}" type="video/mp4"/>
        </video>
        <div class="container position-relative">
            <h1 class="display-3 fw-bold text-white">
                Dubai’s Most Magnetic Networking Circle
            </h1>
            <p>
                Where strangers turn into collaborators and ideas into reality.
            </p>
            <v-btn color="teal" class="text-navy fw-bold">Step In -></v-btn>
        </div>
    </div>
    @include('layouts.thespark')
    @include('layouts.aboutdsb')
    @include('layouts.thevibe')
    @include('layouts.thepowerpass')
    @include('layouts.thepartners')
    @include('layouts.testimonialslider')
@endsection
