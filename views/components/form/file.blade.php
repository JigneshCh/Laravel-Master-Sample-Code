
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$name) 
    </label>
    {!! Form::file($name, array_merge(['id'=>$name] , $attributes )) !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>