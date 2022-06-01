@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="container mt-5">
                <a href="{{route('notificaton.create')}}" class="btn btn-success mb-2">Enviar</a>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Accion</th></tr>
                    </thead>
                    <tbody>
                    @foreach($notificatons as $notificaton)
                        @php
                            $acion=explode(',',$notifications->action);
                            $acion=explode(':',$acion[1]);
                            @endphp
                        <tr>
                            <td>{{$notificaton->title}}</td>
                            <td>{{$notificaton->description}}</td>
                            <td>
                                <form action="{{route($acion[0].'.destroy')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$acion[1]}}"/>
                                    <button type="submit" class="btn btn-danger">
                                        Borrar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$notificatons->links()}}
            </div>
        </div>
    </div>
@endsection
