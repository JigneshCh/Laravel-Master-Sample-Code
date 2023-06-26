 
@php
$attributes = array_merge(['id'=>$name] , $attributes );
 
@endphp

<div class="form-group{{ $errors->has($attributes['id']) ? ' has-error' : ''}}">
    <label for="description" >
        @lang($context.'.label.'.$attributes['id']) 
    </label>
    <div >
        <div class="input-group">
            <span class="input-group-btn">
                <a id="lfm_{{$attributes['id']}}" data-input="{{$attributes['id']}}" data-preview="holder_{{$attributes['id']}}" class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> Choose @lang($context.'.label.'.$attributes['id']) 
                </a>
            </span>
            {!! Form::text($name, $value , array_merge(['id'=>$attributes['id']] , $attributes )  ) !!}
        </div>
        <img id="holder_{{$attributes['id']}}" src="{{asset(@$value)}}"  style="margin-top:15px;max-height:100px;">
    </div>
</div> 


@push('scripts')
<script src="/vendor/laravel-filemanager/js/lfm.js"></script> 
<script>
var domain = "{{ URL::to('/media-manager') }}";
$('#lfm_{{$attributes['id']}}').filemanager('image', {prefix: domain});
</script>
 
@endpush