@extends('layouts.app')

@section('content')
<div class="card" style="margin: 0% 30%;">
    <div class="card-header">
        Crear Genero
    </div>
    <div class="card-body">
        <div class="col-md-8 order-md-1" style="padding-left:10%">
            <form action="{{route('genre.store')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="name" id="nombre" placeholder="Nombre">
                    </div>
                </div>
                <div class="form-group row"><br></div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary">Crear Genero</button>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{route('genre.index')}}" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection