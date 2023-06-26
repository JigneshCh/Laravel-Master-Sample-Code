
@php
if(@$item){
    $value = $item->$relation()->select('*')->pluck($name)->toArray();
}else{
    $value = [];
}   
   
@endphp
{{-- 
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$name) 
    </label>
    <div>
    {!!  Form::select($name, $list , $value , [ 'multiple'=>'multiple', 'name' => 'relation['.$relation.']['.$name.'][]' ] ); !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
    </div>
</div>

 --}}
 
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span> 
        @lang($context.'.label.'.$name) 
    </label>
    <div class="clear"></div>
    @foreach($list as $k=>$v)
    <div class=" custom-{{ $switch ? 'switch' :  'checkbox' }}  custom-control{{ $inline ? '-inline' : '' }} {{ $inline && !$switch ? 'ml-3' : '' }}  ">
        {!! Form::checkbox($name.'[]', $k, in_array($k,$value) , array_merge(['id'=>$name.\Str::slug($k), 'name' => 'relation['.$relation.']['.$name.'][]'] , $attributes )  ) !!}
        <label for="{{$name.\Str::slug($k)}}" class="custom-control-label">
             
            {{ $v }}
        </label>
      </div> 
    @endforeach
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>