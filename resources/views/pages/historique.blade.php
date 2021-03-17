@extends('app')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Historiques</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                <li class="breadcrumb-item active">Historiques</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      @if (Auth::user()->role == 'HOTLINE')
        <liste-historique></liste-historique> 
      @endif         
      @if (Auth::user()->role == 'ADMIN')
        <liste-hist-admin></liste-hist-admin>  
      @endif
      @if (Auth::user()->role == 'TECHNICIEN')
        <liste-hist-tech></liste-hist-tech>
      @endif
  </div>
@endsection