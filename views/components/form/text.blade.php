

@php
    $attributes = array_merge(['id'=>$name] , $attributes );
@endphp
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$attributes['id']}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$attributes['id']) 
    </label>
    {!! Form::text($name, $value , $attributes  ) !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>