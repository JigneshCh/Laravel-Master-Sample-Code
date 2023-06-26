
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>
        @lang($context.'.label.'.$name) 
    </label>
    <div class="clear"></div>
    @foreach($list as $k=>$v)
    <div class=" custom-radio  custom-control{{ $inline ? '-inline' : '' }} {{ $inline ? 'ml-3' : '' }}  ">
        {!! Form::radio($name, $k, ($k == $selected) , array_merge(['id'=>$name.\Str::slug($k)] , $attributes )  ) !!}
        <label for="{{$name.\Str::slug($k)}}" class="custom-control-label">
            <span class="field_compulsory">*</span>
            @lang($context.'.label.'.$v) 
        </label>
      </div> 
    @endforeach
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>