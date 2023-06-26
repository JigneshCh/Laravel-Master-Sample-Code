<div class="form-group">

    

    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : trans('common.label.create'), array_merge(['id'=>'create', 'class'=>'btn btn-primary create'] , $attributes )) !!}
    {!! Form::submit( trans('common.label.createandclose'), array_merge(['id'=>'createandclose', 'class'=>'btn btn-dark createandclose'] , $attributes )) !!}
    {!! Form::submit( trans('common.label.createandnew'), array_merge(['id'=>'createandnew', 'class'=>'btn btn-dark createandnew'] , $attributes )) !!}
{{-- {{ Form::reset(isset($clearButtonText) ? $clearButtonText : trans('common.label.clear'), array_merge(['id'=>'clear', 'class'=>'btn btn-secondary'] , $attributes )) }} --}}
</div>