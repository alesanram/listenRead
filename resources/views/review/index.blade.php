@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="container mt-5">
                <div class="card" style="heigth:20vh">
                    <div class="card-header">
                        Buscar Por Novela
                    </div>
                    <div class="card-body">
                        <div class="col-md-8 order-md-1" style="padding-left:10%">
                            <form action="{{route('review.indexN')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label for="nombre" class="col-sm-3 col-form-label">Titulo</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="novel" id="nombre" placeholder="Nombre">
                                    </div>
                                </div>
                                <div class="form-group row"><br></div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="{{route('review.index')}}" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Puntuacion</th>
                        <th>Usuario</th>
                        <th>Eliminar</th></tr>
                    </thead>
                    <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>{{$review->title}}</td>
                            <td>{{$review->start}}</td>
                            <td>{{$review->name}}</td>
                            <td>
                                <form action="{{route("review.destroy")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$review->id}}"/>
                                    <button type="submit" class="btn btn-danger">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$reviews->links()}}
            </div>
        </div>
    </div>
@endsection
