@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="container mt-5">
                <a href="{{route("genre.create")}}" class="btn btn-success mb-2">Agregar</a>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nombre</th>

                        <th>Editar</th>
                        <th>Eliminar</th></tr>
                    </thead>
                    <tbody>
                    @foreach($genres as $genre)
                        <tr>
                            <td>{{$genre->name}}</td>
                            <td>
                                <form action="{{route("genre.edit")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$genre->id}}"/>
                                    <button type="submit" class="btn btn-warning">
                                        Modificar
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{route("genre.destroy")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$genre->id}}"/>
                                    <button type="submit" class="btn btn-danger">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$genres->links()}}
            </div>
        </div>
    </div>
@endsection