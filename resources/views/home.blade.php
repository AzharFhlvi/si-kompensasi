@php
$container = 'container-fluid';
$containerNav = 'container-fluid';
@endphp

@extends('layouts/app')

@section('title', 'Fluid - Layouts')

@section('content')
<!-- Layout Demo -->
<div class="layout-demo-wrapper">
  <div class="layout-demo-placeholder">
    HOME
  </div>
  <div class="layout-demo-info">
    <h4>Layout fluid</h4>
    <p>Fluid layout sets a <code>100% width</code> at each responsive breakpoint.</p>
  </div>
</div>
<!--/ Layout Demo -->
@endsection
