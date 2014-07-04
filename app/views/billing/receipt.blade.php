@extends('layouts.master')

@section('content')
    Thank you for your purchase.
    <div>
        Your current subscription will automatically renew on: {{ $endDate }}
    </div>
@stop
