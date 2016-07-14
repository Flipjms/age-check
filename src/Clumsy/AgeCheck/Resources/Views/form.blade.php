@extends('clumsy-age-check::master')

<div class="cell age col-sm-12">
    @section('day')
        @include('clumsy-age-check::partials.day')
    @stop
    @section('month')
        @include('clumsy-age-check::partials.month')
    @stop
    @section('year')
        @include('clumsy-age-check::partials.year')
    @stop
</div>

<div class="cell contry col-sm-12">
    @section('country')
        @include('clumsy-age-check::partials.country')
    @stop
</div>

@section('remeber_me')
    <div class="cell remeber col-sm-24">
        @include('clumsy-age-check::partials.remember')
    </div>
@stop

@section('submit')
    @include('clumsy-age-check::partials.submit')
@stop
