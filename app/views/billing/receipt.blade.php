@extends('layouts.master')

@section('content')
    <b>Thank you for your purchase.</b><br>
    <div>
       <p>
       Your current subscription will automatically renew on: {{ $endDate }}
       </p>
    </div>

    {{ link_to_route('billing.index', 'Continue', null, ["class" => "btn btn-primary"]) }}
@stop
