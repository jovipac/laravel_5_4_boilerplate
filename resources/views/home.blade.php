@extends('layouts.app')

@section('htmlheader_title')
	{{ trans('message.home') }}
@endsection

@section('main-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                <div class="jumbotron">
                    <h1>Bienvenido</h1>
                    <p class="lead">{{ trans('message.logged') }}</p>
                </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
