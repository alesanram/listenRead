@extends('layouts.app')

@section('content')
<div class="card" style="margin: 0% 30%;">
    <div class="card-header">
        Modificar Tag
    </div>
    <div class="card-body">
        <div class="col-md-8 order-md-1" style="padding-left:10%">
            <form action="{{route("tag.update")}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$tag->id}}"/>
                <div class="form-group row">
                    <label for="nombre" class="col-sm-3 col-form-label">Nombre</label>
                    <div class="col-sm-7">
                        <input type="text" value="{{$tag->name}}" class="form-control" name="name" id="nombre" placeholder="Nombre"/>
                    </div>
                </div>
                <div class="form-group row"><br></div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary">Modificar Tag</button>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{route('tag.index')}}" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection