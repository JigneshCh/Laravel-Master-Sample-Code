@extends('layouts.admin.index')

@section('body_class',' pace-done')

@section('title',trans('survey.label.survey'))

@section('content')


@yield('fields')
@php
$idFound = false;
$actionFound = false;
@endphp

@foreach ($fields as $key=>$item)

@php
if($key == 'id'){
$idFound = true;
}
if($key == 'action'){
$actionFound = true;
}
@endphp
@endforeach

<div class="main-content">
    <div class="content-wrapper">
        {{-- <div class="row">
            <div class="col-sm-12">
                <div class="content-header"> @lang($context.'.title') </div>
            </div> 
        </div> --}}
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body collapse show">
                            <div class="card-block card-dashboard">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered table-striped datatable responsive">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                @if(!$idFound)
                                                <th>@lang('common.id')</th>
                                                @endif
                                                @foreach ($fields as $key=>$item)
                                                <th>@lang('common.'.$key)</th>
                                                @endforeach
                                                {{-- <th>@lang('common.order')</th>    --}}
                                                @if(!$actionFound)
                                                <th>@lang('common.action')</th>
                                                @endif
                                            </tr>
                                        </thead>
                                    </table>
                                    <input type="hidden" name="trash" id="trash" value="0">
                                </div>
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
<script src="{{ getasset('/app-assets/vendors/js/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ getasset('/app-assets/vendors/js/sweetalert2.min.js') }}" type="text/javascript"></script>
{{-- <script src="{{ getasset('/app-assets/vendors/js/datatable/dataTables.select.min.js') }}" type="text/javascript"></script>
<script src="{{ getasset('/app-assets/vendors/js/datatable/dataTables.buttons.min.js') }}" type="text/javascript"></script>
<script src="{{ getasset('/app-assets/vendors/js/datatable/buttons.html5.min.js') }}" type="text/javascript"></script>
<script src="{{ getasset('/app-assets/vendors/js/datatable/buttons.print.min.js') }}" type="text/javascript"></script> --}}
@endsection


@section('css')
@parent
<link rel="stylesheet" type="text/css" href="{{ getasset('/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{ getasset('/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ getasset('/app-assets/vendors/css/tables/datatable/buttons.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ getasset('/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{ getasset('/app-assets/vendors/css/tables/datatable/select.dataTables.min.css')}}"> --}}
@endsection


@push('scripts')
<script>
    var url = "{{ route('admin.'.$context) }}";
    var edit_url = "{{ route('admin.'.$context) }}";
    var auth_check = "{{ \Auth::check() }}";
    var auth_uid = {{ \Auth::user()-> id }};

    $(document).ready(function() {

        var table = $('#datatable').DataTable({
            pageLength: 25,
            lengthChange: true,
            dom: "<' row buttons'<'col-sm-12'B>>" +
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                'selectAll',
                'selectNone',
            ],
            select: true,
            columnDefs: [{
                orderable: true,
                className: 'select-checkbox',
                targets: 0
            }],
            pagingType: "full_numbers",
            language: {
                emptyTable: "@lang('common.datatable.emptyTable')",
                infoEmpty: "@lang('common.datatable.infoEmpty')",
                search: "@lang('common.datatable.search')",
                sLengthMenu: "@lang('common.datatable.show') _MENU_ @lang('common.datatable.entries')",
                sInfo: "@lang('common.datatable.showing') _START_ @lang('common.datatable.to') _END_ @lang('common.datatable.of') _TOTAL_ @lang('common.datatable.small_entries')",
                paginate: {
                    next: '@lang('
                    common.datatable.paginate.next ')',
                    previous: '@lang('
                    common.datatable.paginate.previous ')',
                    first: '@lang('
                    common.datatable.paginate.first ')',
                    last: '@lang('
                    common.datatable.paginate.last ')',
                },
                buttons: {
                    selectAll: "Select All",
                    selectNone: "Select None",
                },
            },
            processing: true,
            serverSide: true,
            autoWidth: true,
            stateSave: true,
            order: [1, "asc"],
            columns: [{
                    "data": null,
                    "searchable": false,
                    "orderable": false,
                    "render": function(o) {
                        return null;
                    }
                },

                @if(!$idFound) {
                    "data": null,
                    "name": 'id',
                    "searchable": false,
                    "orderable": false,
                    "render": function(o) {
                        e = "<a href='" + edit_url + "/" + o.id + "/edit'    >" + o.id + "</a>";
                        return e;
                    }
                },
                @endif

                @foreach($fields as $key => $item) {

                    {
                        !!@$item['data'] ? 'data:'.$item['data'].
                        ',': ''!!
                    } {
                        !!@$item['name'] ? 'name:'.$item['name'].
                        ',': ''!!
                    } {
                        !!@$item['searchable'] ? 'searchable:'.$item['searchable'].
                        ',': ''!!
                    } {
                        !!@$item['orderable'] ? 'orderable:'.$item['orderable'].
                        ',': ''!!
                    }


                    {
                        !!@$item['render'] ? 'render:'.$item['render'].
                        ',': ''!!
                    }
                },
                @endforeach
                @if(!$actionFound) {
                    "data": null,
                    "searchable": false,
                    "orderable": false,
                    "width": 150,
                    "render": function(o) {
                        var e = '';
                        var v = '';
                        var d = '';
                        v = '<a href=\'' + edit_url + '/' + o.id + '\' value=\'' + o.id + '\' data-id=\'' + o.id + '\' ><button class=\'btn btn-info btn-sm\' title=\'@lang("common.tooltip.icon.eye")\' ><i class=\'fa fa-eye\' ></i></button></a>';
                        e = "<a href='" + edit_url + "/" + o.id + "/edit' value=" + o.id + " data-id=" + o.id + " title='@lang('common.tooltip.icon.edit')' class='btn btn-warning btn-sm'><i class='fa fa-pencil'></i></a>&nbsp;";
                        d = '<a href=\'javascript:void(0);\' class=\'btn btn-danger btn-sm del-log\' title=\'@lang("common.tooltip.icon.delete")\'  data-id=\'' + o.id + '\' ><i class=\'fa fa-trash\' aria-hidden=\'true\'></i></a>';
                        if (o.deleted_at && o.deleted_at != "") {
                            return '<a href=\'javascript:void(0);\' class=\'recover-item btn btn-info btn-sm\' moduel=\'survey\' data-id=\'' + o.id + '\' title=\'@lang("common.label.recover")\'><i class=\'fa fa-repeat\'></i></a>' + d;
                        } else {
                            return v + e + d;
                        }
                    }
                }
                @endif


            ],
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                $('td', nRow).attr('nowrap', 'nowrap');
                return nRow;
            },
            ajax: {
                url: "{{ route('admin.'.$context.'.datatable') }}",
                type: "post",
                data: function(d) {

                    d.enable_deleted = $('#trash').val();

                    d._token = '{{ csrf_token() }}'
                }
            },
            scrollX: true,
            scrollY: 580,
            responsive: false
        });

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-group';
        new $.fn.dataTable.Buttons(table, {
            name: 'aps',
            buttons: [{
                    text: '@lang('
                    common.add_new ')',
                    action: function(e, dt, node, config) {
                        window.location = "{{ route('admin.'.$context.'.create') }}";
                    },
                    className: 'btn btn-dark ',
                },
                {
                    text: '@lang('
                    common.permissions ')',
                    action: function(e, dt, node, config) {
                        window.location = "{{ route('admin.rollpermissions',['module'=>$context])  }}";
                    },
                    className: 'btn btn-dark',
                },
                {
                    text: '@lang('
                    common.settings ')',
                    action: function(e, dt, node, config) {
                        window.location = "{{ route('admin.settings',['module'=>$context])  }}";
                    },
                    className: 'btn btn-dark',
                }
            ]
        });

        table.buttons(1, null).container().appendTo(
            $(table.table().container()).find('.buttons .col-sm-12')
        );
        new $.fn.dataTable.Buttons(table, {
            name: 'cep',
            buttons: [
                'colvis',
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ],
                    className: 'btn btn-dark',
                }
            ]
        });

        table.buttons(2, null).container().appendTo(
            $(table.table().container()).find('.buttons .col-sm-12')
        );

        new $.fn.dataTable.Buttons(table, {
            name: 'trashmanager',
            buttons: [{
                    name: 'viewtrash',
                    text: 'View Trash',
                    action: function(e, dt, node, config) {
                        var trash = $('#trash').val();
                        if (trash == '0') {
                            $(node).addClass('active');
                            $('#trash').val('1');
                            table.buttons('restoreall:name').enable();
                            table.buttons('deleteall:name').text('Delete All');
                        } else {
                            $(node).removeClass('active');
                            $('#trash').val('0');
                            table.buttons('restoreall:name').disable();
                            table.buttons('deleteall:name').text('Trash All');
                        }
                        table.draw();
                    },
                    className: 'btn btn-dark',
                },
                {
                    name: 'deleteall',
                    text: 'Trash All',
                    action: function(e, dt, node, config) {
                        var rows = table.rows({
                            selected: true
                        });
                        var ids = rows.data().pluck('id');

                        swal({
                            title: 'Are you sure?',
                            text: "@lang('common.js_msg.confirm_for_delete_data')",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#0CC27E',
                            cancelButtonColor: '#FF586B',
                            confirmButtonText: 'Yes',
                            cancelButtonText: "No, cancel"
                        }).then(function(isConfirm) {
                            if (isConfirm) {

                                ids.each(function(id) {
                                    $.ajax({
                                        type: "DELETE",
                                        url: "{{ route('admin.'.$context) }}" + "/" + id,
                                        headers: {
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        },
                                        success: function(data) {}
                                    });
                                });
                                table.draw();

                                swal({
                                    type: 'success',
                                    title: "Success",
                                    text: "@lang('common.js_msg.action_success')",
                                    timer: 2000,
                                    showConfirmButton: false
                                });


                            }
                        }).catch(swal.noop);
                    },
                    className: 'btn btn-danger',
                },
                {
                    name: 'restoreall',
                    text: 'Restore All',
                    action: function(e, dt, node, config) {
                        var rows = table.rows({
                            selected: true
                        });
                        var ids = rows.data().pluck('id');
                        swal({
                            title: 'Are you sure?',
                            text: "@lang('common.js_msg.confirm_for_delete_data')",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#0CC27E',
                            cancelButtonColor: '#FF586B',
                            confirmButtonText: 'Yes',
                            cancelButtonText: "No, cancel"
                        }).then(function(isConfirm) {
                            if (isConfirm) {

                                ids.each(function(id) {
                                    $.ajax({
                                        type: "POST",
                                        url: "{{ route('admin.'.$context.'.recover') }}",
                                        data: {
                                            id: id
                                        },
                                        headers: {
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        },
                                        success: function(data) {}
                                    });
                                });
                                table.draw();

                                swal({
                                    type: 'success',
                                    title: "Success",
                                    text: "@lang('common.js_msg.action_success')",
                                    timer: 2000,
                                    showConfirmButton: false
                                });


                            }
                        }).catch(swal.noop);

                    },
                    className: 'btn btn-dark',
                    enabled: false
                },
            ]
        });

        table.buttons(3, null).container().appendTo(
            $(table.table().container()).find('.buttons .col-sm-12')
        );

        $('.filter').change(function() {
            table.draw();
        });
        $(document).on('click', '.del-log', function(e) {
            var id = $(this).attr('data-id');
            swal({
                title: 'Are you sure?',
                text: "@lang('common.js_msg.confirm_for_delete_data')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0CC27E',
                cancelButtonColor: '#FF586B',
                confirmButtonText: 'Yes',
                cancelButtonText: "No, cancel"
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('admin.'.$context) }}" + "/" + id,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            table.draw();
                            swal({
                                type: 'success',
                                title: "Success",
                                text: "@lang('common.js_msg.action_success')",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr, status, error) {
                            swal({
                                type: 'error',
                                title: "Error",
                                text: "@lang('common.js_msg.action_not_procede')",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            }).catch(swal.noop);
        });

        $(document).on('click', '.recover-item', function(e) {
            var id = $(this).attr('data-id');

            swal({
                title: 'Are you sure?',
                text: "@lang('common.js_msg.confirm_for_delete_data')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0CC27E',
                cancelButtonColor: '#FF586B',
                confirmButtonText: 'Yes',
                cancelButtonText: "No, cancel"
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.'.$context.'.recover') }}",
                        data: {
                            id: id
                        },
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            table.draw();
                            swal({
                                type: 'success',
                                title: "Success",
                                text: "@lang('common.js_msg.action_success')",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr, status, error) {
                            var erro = ajaxError(xhr, status, error);
                            swal({
                                type: 'error',
                                title: "Error",
                                text: "@lang('common.js_msg.action_not_procede')",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            }).catch(swal.noop);
        });

        $(document).on('change', '.row_ordering', function(e) {
            var id = $(this).attr('data-id');
            var value = $(this).val();
            console.log(id);
            $.ajax({
                type: "POST",
                url: "{{ route('admin.ordering') }}",
                data: {
                    id: id,
                    ordering: value,
                    context: '{{$context}}',
                },
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(data) {
                    table.draw();
                    toastr.success('Action Success!', data.message)
                },
                error: function(xhr, status, error) {
                    var erro = ajaxError(xhr, status, error);
                    toastr.error('Action Not Procede!', erro)
                }
            });

        });

    });
</script>
@endpush