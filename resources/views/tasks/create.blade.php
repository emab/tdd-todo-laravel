@extends('layouts.app')
@section('title', '- Create Todo')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><strong>Add a new task</strong></strong></div>
					<div class="card-body">
						<div class="col-lg-6 mx-auto">
						@if(count($errors))
							<div class="alert alert-danger">
							@foreach($errors->all() as $error)
								<li>{{$error}}</li>
							@endforeach
							</div>
						@endif
						</div>
						<form method="POST" action="/tasks/create">
							{{csrf_field()}}
							<div class="form-group">
								<input type="text" name="title" class="form-control" id="title" placeholder="Enter task title">
							</div>
							<div class="form-group">
								<textarea class="form-control" name="description" placeholder="Task description" rows="8"></textarea>
							</div>
							<button class="btn btn-primary" type="submit">Add Task</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection