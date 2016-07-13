@extends('master')

<div class="cell age col-sm-12">
    @section('day')
        @include('partials.day')
    @stop
    @section('month')
        @include('partials.month')
    @stop
    @section('year')
        @include('partials.year')
    @stop
</div>

<div class="cell contry col-sm-12">
    @section('country')
        @include('partials.contry')
    @stop
</div>

@section('remeber_me')
    <div class="cell remeber col-sm-24">
        @include('partials.remeber')
    </div>
@stop

@section('submit')
    @include('partials.submit')
@stop