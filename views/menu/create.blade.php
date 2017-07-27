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
    <div class="flex-center position-ref full-height">
        <div class="content">
            <form action="{{route('menu.store')}}" method="POST" class="ui form">
                {{ csrf_field() }}

                @include('menus::menu.form')

                <div class="field actions">
    	            <a class="ui button" href="{{ route('menu.index') }}">Cancel</a>
    	            <button type="submit" class="ui right floated primary button">
    	            	Create Menu
                	</button>
                </div>
            </form>
        </div>
    </div>
@endsection