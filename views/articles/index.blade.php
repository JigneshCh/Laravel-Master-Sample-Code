@extends('admin.system.index')
@section('fields')
@php

$fields = [
    'title' => [
        'data'=> '"title"',
        'name'=> '"title"',
        'searchable'=> 'true',
        'orderable'=> 'true'
        ],
    ];
@endphp
@endsection