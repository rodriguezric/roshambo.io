@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Rooms</div>
                <div class="panel-body">
                    @if (count($rooms) === 0)
                        There currently no rooms.
                    @else
                        <ul>
                        @foreach ($rooms as $room)
                            <li>{{ $room }}</li> 
                        @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var post = function (url, data, callback) {
        xhttp = new XMLHttpRequest();
        xhttp.open("POST", url, true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.onload = callback;
        xhttp.send(data);
    }

    var createRoom = function (name) {
        post("/api/room", 'name='+name, function (){
            console.log(this.responseText);
        });
    };

    createRoom("myOwn");

</script>
@endsection
