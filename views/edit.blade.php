@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="sitemap icon"></i>
        <div class="content">
            Menu Management
            <div class="sub header">Create a menu.</div>
        </div>
    </h1>
@endsection

@section('content')

<div id="registration" class="ui container">
    <div class="ui hidden divider"></div>
    <form action="{{ route('menu.update', $menu) }}" method="POST" class="ui form">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @include('menu::form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('menu.index') }}">Cancel</a>

            <button type="submit" class="ui right floated primary button">
                Update Content
            </button>
        </div>
    </form>
</div>

@endsection