
	<label class="form-control-label"
			 for="{{ $name }}">
			{{ $title }}
	</label>
	<input type="number"
				 class="form-control"
				 id="{{ $name }}"
				 name="{{ $name }}"
				 placeholder="@isset($placeholder) {{ $placeholder }} @endisset"
				 value="@isset($value){{ $value }}@endisset"
				 @isset($required) required @endisset
				 @isset($step) step="{{$step}}" @endisset
				 @isset($readonly) readonly @endisset
				 @isset($min) min="{{$min}}" @endisset
				 @isset($max) max="{{$max}}" @endisset
				 @isset($oninput) oninput="{{$oninput}}" @endisset>
