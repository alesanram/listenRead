@extends('layouts.app')

@section('content')
<div class="card" style="margin: 0% 30%;">
    <div class="card-header">
        Crear Genero
    </div>
    <div class="card-body">
        <div class="col-md-8 order-md-1" style="padding-left:10%">
            <form action="{{route('notification.send')}}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="title" class="col-sm-3 col-form-label">Titulo</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Titulo">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-3 col-form-label">Descripcion</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="description" id="description" placeholder="Descripcion">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="message" class="col-sm-3 col-form-label">Mensaje</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="message" id="message" placeholder="Mensaje">
                    </div>
                </div>
                <div class="form-group row"><br></div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary">Enviar Notificacion</button>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{route('notification.reportNovel')}}" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
