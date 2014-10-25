@extends('layouts.master')
    @section("javascript")
        {{ HTML::script('assets/string-helper.js'); }}
    @endsection

    @section("content")
		<div class="page-header">
			<h1>
				Upload a lead file.
			</h1>
        </div>
		<div class="row">
			<ul>
				<li>You must choose a file.</li>
				<li>The file must be of type xls or xlsx.</li>
				<li>The file must be smaller than 5000 KB.</li>
			</ul>
		</div>
        <div class="row">
			<div class="col-lg-12 well">
				{{ Form::open(array('action' => 'AdministrationController@upload', 'file' => true, 'enctype' => 'multipart/form-data', 'class' => 'form')) }}
						{{ Form::token() }}
				<div style="padding:10px">{{ Form::file('file', array('accept'=>'xlsx')) }}</div>
				<div style="padding:10px">{{ Form::submit('Upload Leads', array('class'=>'btn btn-small btn-primary')) }}</div>
				{{ Form::close() }}
            </div>
        </div>
        <div>
        	<?php
        		echo $errors->first('file');
        	?>
        </div>
        <div>
        	<?php
				$message = Session::get('message');
				echo $message;
        	?>
        </div>

    @endsection
@stop
