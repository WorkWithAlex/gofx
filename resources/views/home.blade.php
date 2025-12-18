@extends('layouts.app')

@section('title', 'GOFX — Master Forex, Gold & Bitcoin Trading')

@section('content')

    {{-- Homepage Sections (Loaded from partials/home-page/) --}}
    @include('partials.home-page.hero')
    @include('partials.home-page.instructor')
    @include('partials.home-page.what-you-learn')
    @include('partials.home-page.highlights')
    @include('partials.home-page.featured-courses')
    @include('partials.home-page.cta')
    @include('partials.home-page.faq')

@endsection