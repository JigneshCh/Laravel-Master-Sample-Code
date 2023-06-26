
<div class="form-group {{ $errors->has($name) ? 'has-error' : ''}}">
    <label for="{{$name}}" class="">
        <span class="field_compulsory">*</span>@lang($context.'.label.'.$name) 
    </label>
    {!! Form::textarea($name, $value, array_merge(['id'=>$name] , $attributes ) ) !!}
    {!! $errors->first($name, '<p class="help-block text-danger">:message</p>') !!}
</div>

 

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script> 
<script>
  var options = {
    filebrowserImageBrowseUrl: '/media-manager?type=Images',
    filebrowserImageUploadUrl: '/media-manager/upload?type=Images&_token=',
    filebrowserBrowseUrl: '/media-manager?type=Files',
    filebrowserUploadUrl: '/media-manager/upload?type=Files&_token=',
    toolbarCollapse: true,
    allowedContent: true,
    contentsCss: '/assets/css/site.css',
  };
CKEDITOR.replace('{{$name}}', options);
</script>
@endpush
