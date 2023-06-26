<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="param_{{$name}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$name) 
    </label>
    {!! Form::text( 'param['.$name.']' , $value , array_merge(['id'=>'param_'.$name] , $attributes )  ) !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!} 
</div>