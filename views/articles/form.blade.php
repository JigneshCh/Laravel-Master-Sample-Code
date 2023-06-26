<div class="row ">
    <div class="col-md-12">
        {{ Form::cbButtons(trans($context.'.form.create'), trans($context.'.form.clear') ) }}
    </div>
    <div class="col-md-6">
        {{ Form::cbText('title') }}
        {{ Form::cbText('slug') }}
        @php
        $categories = \App\Categories::pluck('name', 'id')->prepend('Select', 0)
        @endphp

        {{ Form::cbSelect('category_id',$categories) }}

        {{ Form::cbRadioList('status', ['1' => 'Active' , '0' => 'Pending'],  true ) }}

    </div>
    <div class="col-md-6">
        {{ Form::cbText('param[read_time]',null,['id'=>'read_time', 'class'=>'form-control']) }}

        {{ Form::cbText('views') }}

        {{ Form::cbDate('param[display_date]',null,['id'=>'display_date', 'class'=>'form-control' ]) }}
    </div>
    <div class="col-md-12">
        {{ Form::cbTextarea('tags') }}
        {{ Form::cbTextarea('desc') }}
        {{ Form::cbMediaImage('param[image_main]',null,['id'=>'param_image_main', 'class'=>'form-control']) }}

    </div>
    <div class="col-md-12">
        {{ Form::cbButtons(trans($context.'.form.create'), trans($context.'.form.clear') ) }}
    </div>
</div>