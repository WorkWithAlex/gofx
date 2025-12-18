@extends('layouts.app')

@section('title', 'Terms & Conditions — GOFX')

@section('content')

{{-- HERO / TITLE --}}
@include('partials.terms.hero')

{{-- MAIN TERMS CONTENT --}}
@include('partials.terms.content')

@endsection
