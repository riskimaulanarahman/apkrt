@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Dashboard RT')

@section('content')
	<!-- begin panel -->
	{{-- <div class="panel panel-info">
		<div class="panel-heading">
			<h4 class="panel-title">Panel Title here</h4>
			<div class="panel-heading-btn">
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
				<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<div class="panel-body">
			Panel Content Here
		</div>
	</div> --}}
	<h2>Welcome {{ Auth::user()->nama_lengkap }} </h2>
	<!-- end panel -->
@endsection