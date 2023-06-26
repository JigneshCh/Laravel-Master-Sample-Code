<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}">  @lang($context.'.label.'.$name)  </label>
    <div class="">
        @php 
             $file_type = $context.($multiple ? '_multiple_'.$name : '_single_'.$name );
        @endphp
       
        @if(isset($item) && $item->manyfile->where('file_type',$file_type)->count())
            @include("layouts.admin.shared.files",['refefile'=>$item->manyfile->where('file_type',$file_type)])
        @endif
        {!! Form::file($name.($multiple ? '[]':''),  ['id'=>$name,'class' => 'form-control','multiple'=>$multiple]) !!}
        {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
        {!! Form::hidden($name.'_type', $file_type  ) !!}

        {{-- @dd($item->manyfile); --}}
    </div>
</div>  