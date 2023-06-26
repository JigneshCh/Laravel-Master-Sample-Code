
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>
        @lang($context.'.label.'.$name) 
    </label>
    <div class="clear"></div>
    <div class="custom-control custom-checkbox">
        {!! Form::checkbox($name, $value, $selected, array_merge(['id'=>$name] , $attributes )  ) !!}
        {{-- <input type="checkbox" class="custom-control-input" id="customCheck1"> --}}
        {{-- <label class="custom-control-label" for="customCheck1">Check this custom checkbox</label> --}}
        <label for="{{$name}}" class="custom-control-label">
            <span class="field_compulsory">*</span>
            @lang($context.'.label.'.$name) 
        </label>
      </div> 
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>