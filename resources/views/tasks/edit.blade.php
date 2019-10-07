@extends('layouts.app')
@section('title', 'Edit - '.$task->id)
@section('content')
	<div class="container">
		<div class="row justified-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><strong>Update task</strong></div>
					<div class="card-body">
						@if(count($errors))
						<div class="col-md-8 ml-auto alert alert-danger">
						@foreach($errors->all() as $error)
						<li>
							{{$error}}
						</li>
						@endforeach
						</div>
						@endif
						<form method="POST" action="/tasks/{{$task->id}}">
							{{csrf_field()}}
							{{method_field('PUT')}}
							<div class="form-group">
								<input type="type" name="title" class="form-control" id="title" placeholder="Enter title" value="{{$task->title}}">
							</div>
							<div class="form-group">
								<textarea class="form-control" name="description" placeholder="Provide a description" rows="8">{{$task->description}}</textarea>
							</div>
							<button class="btn btn-primary" type="submit">Update Task</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection