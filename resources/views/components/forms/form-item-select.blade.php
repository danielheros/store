
<label class="form-control-label"
			 for="{{ $name }}">
			{{ $title }}
</label>

<select class="form-control" name='{{ $name }}'
  @isset($readonly) readonly @endisset
  @isset($required) required @endisset>
    
	<option value=''> {{ __('messages.select_list')}} </option>

	@foreach ($items as $item )

     <option value='{{ $item->id }}'
     	{{ isset($selected) && $item->id == $selected ? "selected='selected'" : "" }}'>

			{{ $item->name }}

     </option>
	@endforeach

</select>
