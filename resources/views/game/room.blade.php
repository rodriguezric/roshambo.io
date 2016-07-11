@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Room: @{{room}}</div>
                <div class="panel-body">
                    
                    <button class="btn" v-on:click="sitdown(room, 1)">Seat 1</button>
                    <button class="btn" v-on:click="sitdown(room, 2)">Seat 2</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var socket = io('http://50.116.47.108:3000');

    var vue = new Vue({
        el: 'body',

        data: {
            room: '{{$room}}',
            seats: []
        },

        methods: {
            sitdown: function(room, seat) {
                $.ajax({
                    type: "POST",
                    url:  "/api/room/sitdown",
                    data: {
                        room: room,
                        seat: seat
                    },
                    success: function(data) {
                    }
                });
            }
        },
        ready: function () {
            console.log("This is ready");
            socket.on('game-channel:App\\Events\\Sitdown', function(data) {
                console.log(data);
                console.log(data.room);
            }.bind(this));
        }
    });

</script>
@endsection
