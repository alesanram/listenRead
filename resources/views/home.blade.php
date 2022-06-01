@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('L&R') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div style="margin-left:35%;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="210" height="125" version="1.1">
                            <g id="Layer_1">
                                <title>Layer 1</title>
                                <text font-style="italic" font-weight="bold" transform="matrix(1 0 0 1 0 0)" xml:space="preserve" text-anchor="start" font-family="Noto Sans JP" font-size="60" id="svg_3" y="82.76666" x="42.80651" stroke-width="0" stroke="#000" fill="#0C372C">L&amp;R</text>
                            </g>
                        </svg>
                    </div>
                    {{ __(' This is the administration web of L&R, if you no are the Administrator you not logged for this part of the web') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
