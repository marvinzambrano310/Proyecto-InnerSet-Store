@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Inner Set Store</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <br>
                    Ingrese a la App e Inicie Sesión. Se han confirmado todos los cambios !!!
                    <br>
                    Saludos, InnerSet
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
