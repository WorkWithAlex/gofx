@extends('layouts.app')

@section('title', 'About — GOFX | Gold & Bitcoin Forex')

@section('content')

{{-- ABOUT HERO --}}
@include('partials.about.hero')

{{-- FOUNDER STORY --}}
@include('partials.about.founder')

{{-- MISSION + VISION --}}
@include('partials.about.mission')

{{-- WHY GOFX --}}
@include('partials.about.why-gofx')

{{-- CTA SECTION --}}
@include('partials.about.final-cta')

@endsection
