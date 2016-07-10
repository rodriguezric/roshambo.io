@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Rooms</div>

                <div class="panel-body">
                    There currently no rooms.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var createRoom = function (name) {
        xhttp = new XMLHttpRequest();
        xhttp.open("POST", "/api/room", true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.onload = function () {
            console.log(this.responseText);
        };
        xhttp.send('name='+name);
    };

    createRoom("testRoom");
</script>
@endsection
