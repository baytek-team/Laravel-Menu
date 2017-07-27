<div class="two fields">
	<div class="four wide field">
		<label for="title">Type</label>
		<select name="type" class="ui dropdown">
			<option value="route">Named Route</option>
			<option value="action">Model Action</option>
			<option value="url">URL</option>
		</select>
	</div>
	<div class="twelve wide field{{ $errors->has('content') ? ' error' : '' }}">
		<label for="content">Link</label>
		<input type="text" id="content" name="content" placeholder="Enter the desired menu item location" value="{{ old('content', $item->content) }}">
	</div>
</div>

<div class="field{{ $errors->has('title') ? ' error' : '' }}">
	<label for="title">Link Title</label>
	<input type="text" id="title" name="title" placeholder="Title" value="{{ old('title', $item->title) }}">
</div>

<div class="three fields">
	<div class="field{{ $errors->has('class') ? ' error' : '' }}">
		<label for="class">Link CSS Class</label>
		<input type="text" id="class" name="class" placeholder="CSS Class" value="{{ old('class', $item->class) }}">
	</div>
	<div class="field{{ $errors->has('prefix') ? ' error' : '' }}">
		<label for="prefix">Link Prefix Content</label>
		<input type="text" id="prefix" name="prefix" placeholder="Enter content that will be prefixed to link" value="{{ old('prefix', $item->prefix) }}">
	</div>
	<div class="field{{ $errors->has('suffix') ? ' error' : '' }}">
		<label for="suffix">Link Suffix Content</label>
		<input type="text" id="suffix" name="suffix" placeholder="Enter content that will be suffixed to link" value="{{ old('suffix', $item->suffix) }}">
	</div>
</div>
