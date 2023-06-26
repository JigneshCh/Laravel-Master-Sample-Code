@extends('layouts.admin.index')
@section('title',trans('issue.label.issues'))
@section('content')
<div class="main-content">
    <div class="content-wrapper">
        <section id="basic-form-layouts">
            <div class="row">
                <div class="col-sm-12"> 
                    <div class="content-header"> @lang($context.'.edit.title') </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.'.$context) }}">
                                <button type="button" class="btn btn-raised btn-success btn-min-width mr-1 mb-1">
                                    <i class="fa fa-angle-left"></i> @lang($context.'.title')
                                </button>
                            </a>
                            {!! Form::open([
                            'method'=>'DELETE',
                            'url' => [ route('admin.'.$context), $item->id],
                            'style' => 'display:inline'
                            ]) !!}
                           
                            {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i> '.trans('common.delete'), array(
                            'type' => 'submit',
                            'class' => 'btn btn-raised btn-danger btn-min-width mr-1 mb-1',
                            'title' => trans('common.delete'),
                            'onclick'=>"return confirm('".trans('common.js_msg.confirm_for_delete_data')."')"
                            ))!!}
                            {!! Form::close() !!}
                        </div>
                        <div class="card-body">
                            <div class="px-3">
                                {!! Form::model($item, [
                                'method' => 'PATCH',
                                'url' => [route('admin.'.$context.'.update',[str_singular($context)=>$item->id]), ],
                                'class' => 'form-horizontal',
                                'files' => true,
                                'autocomplete'=>'off'
                                ]) !!}

                                {!! Form::hidden('action',null,['id'=>'form_action','class'=>'form_action']) !!}	

                                @include ('admin.'.$context.'.form', ['submitButtonText' => trans('common.update')])
                                
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
</section>
@endsection

@section('js')
@parent
 
@endsection

@push('scripts')
<script>
    
    $(document).ready(function () {
        $("#create").on('click',function(){
            $('#form_action').val('create');
        });
        $("#createandclose").on('click',function(){
            $('#form_action').val('createandclose');
        });
        $("#createandnew").on('click',function(){
            $('#form_action').val('createandnew');
        });
        
    });
</script>    
@endpush
