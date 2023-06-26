@extends('layouts.admin.index')
@section('title',trans('issue.label.issues'))
@section('content')

@push('css')

<link href="{!! asset('apex/vendors/css/dragula.min.css') !!}" media="all" rel="stylesheet" type="text/css"/>

@endpush
 <div class="main-content">
    <div class="content-wrapper">
    <section id="basic-form-layouts">
	<div class="row">
		<div class="col-sm-12">
			<div class="content-header"> @lang($context.'.create.title') </div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="actions pull-right">	
					</div>
				</div>
				<div class="card-body">
					<div class="px-3">
						{!! Form::open(['url' => route('admin.'.$context), 'class' => 'form-horizontal group-border-dashed','id' => 'module_form','autocomplete'=>'off','files'=>true]) !!}
							{!! Form::hidden('action',null,['id'=>'form_action','class'=>'form_action']) !!}	
							@include ('admin.'.$context.'.form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
	</section>
	</div>
</div>   
@endsection 
@section('js')

@parent
@endsection
 
@push('scripts')
<script>
    
    $(document).ready(function () {
        $(".create").on('click',function(){
            $('.form_action').val('create');
        });
        $(".createandclose").on('click',function(){
            $('.form_action').val('createandclose');
        });
        $(".createandnew").on('click',function(){
            $('.form_action').val('createandnew');
        });
        
    });
</script>    
@endpush
