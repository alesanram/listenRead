@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="container mt-5">
                <a href="{{route("tag.create")}}" class="btn btn-success mb-2">Agregar</a>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nombre</th>

                        <th>Editar</th>
                        <th>Eliminar</th></tr>
                    </thead>
                    <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{$tag->name}}</td>
                            <td>
                                <form action="{{route("tag.edit")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$tag->id}}"/>
                                    <button type="submit" class="btn btn-warning">
                                        Modificar
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form action="{{route("tag.destroy")}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$tag->id}}"/>
                                    <button type="submit" class="btn btn-danger">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$tags->links()}}
            </div>
        </div>
    </div>
@endsection