@extends('layouts.app')
@section('title', 'Todo Index')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
				<div class="col-4">
					<h2>All Tasks</h2>
				</div>
				<div class="col-w">
					<a href="/tasks/create"><button class="btn btn-info float-right"><strong>+</strong> Add task</button></a>
				</div>
		</div>
		@if(count($tasks) == 0)
		<div class="row"></div>
			<div class="col-8 col-lg-3 alert alert-info mt-5 text-center mx-auto">
				<p>There are no tasks, add one!</p>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-md-8">
				@endif
				@foreach($tasks as $task)
				<div class="card mt-3">
					<a href="/tasks/{{$task->id}}"><div class="card-header">{{$task->title}}</div></a>
					<div class="card-body">{{$task->description}}</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection