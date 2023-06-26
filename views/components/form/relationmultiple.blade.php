
@php
if(@$item){
    $value = $item->technologies()->select('*')->pluck('tech_id');
}else{
    $value = null;
}      
@endphp

<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$name) 
    </label>
    <div>
    {!!  Form::select($name, $list , $value , [ 'multiple'=>'multiple', 'name' => 'relation['.$relation.']['.$name.'][]' ] ); !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
    </div>
</div>