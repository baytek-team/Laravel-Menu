@extends('Content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="world icon"></i>
        <div class="content">
            Menu Management
            <div class="sub header">Manage the menu content type.</div>
        </div>
    </h1>
@endsection

@section('page.head.menu')
    <div class="ui secondary menu">
    	<div class="right item">
	        <a class="ui labeled icon button" href="{{ route('menu.create') }}">
	            <i class="world icon"></i>Add Menu
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
		@foreach($menus as $menu)
			<tr>
				<td class="collapsing">{{ $menu->id }}</td>
				<td class="collapsing">{{ $menu->key }}</td>
				<td>{{ $menu->title }}</td>
				<td class="right aligned collapsing">
					<a href="{{ route('menu.edit', $menu) }}" class="ui labeled icon button primary"><i class="pencil icon"></i>Edit</a>
					<a href="{{ route('menu.destroy', $menu) }}" class="ui labeled icon button negative"><i class="delete icon"></i>Delete</a>
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection