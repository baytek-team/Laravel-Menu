@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="sitemap icon"></i>
        <div class="content">
            Menu Management
            <div class="sub header">Manage the menu content type.</div>
        </div>
    </h1>
@endsection

@section('page.head.menu')
    <div class="ui secondary menu">
    	<div class="right item">
	        <a class="ui labeled item" href="{{ route('menu.create') }}">
	            <i class="sitemap icon"></i>Add Menu
	        </a>
	    </div>
    </div>
@endsection

@section('content')
<table class="ui selectable table">
	<thead>
		<tr>
			<th class="center aligned collapsing">ID</th>
			<th class="center aligned collapsing">Key</th>
			<th>Title</th>
			<th class="center aligned collapsing">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($children as $menu)
			<tr>
				<td class="collapsing">{{ $menu->id }}</td>
				<td class="collapsing"><a href="{{ route('menu.show', $menu) }}">{{$menu->key}}</a></td>
				<td>{{ $menu->title }}</td>
				<td class="right aligned collapsing">
					<div class="ui compact text menu">
						<a href="{{ route('menu.item.create', $menu) }}" class="item"><i class="plus icon"></i>Edit</a>
						<a href="{{ route('menu.edit', $menu) }}" class="item"><i class="pencil icon"></i>Edit</a>
						<a href="{{ route('menu.destroy', $menu) }}" class="item"><i class="delete icon"></i>Delete</a>
					</div>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection