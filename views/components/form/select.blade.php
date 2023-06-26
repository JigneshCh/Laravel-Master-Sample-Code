
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$name) 
    </label>
    {!!  Form::select($name, $list , $value , array_merge(['id'=>$name] , $attributes ), $placeholder ); !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>