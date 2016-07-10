@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Rooms</div>
                <div class="panel-body">
                    <ul v-if="rooms.length > 0">
                        <li v-for="room in rooms">@{{room}}</li>
                    </ul>
                    
                    <button class="btn" v-on:click="createRoom('A New Room')">Create Room</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var vue = new Vue({
        el: 'body',

        data: {
            rooms: {!! json_encode($rooms) !!}
        },

        methods: {
            createRoom: function (name) {
                $.post("/api/room", function (data) {
                    console.log(data);
                    this.rooms.push(data);
                }.bind(this));
            }

        }
    });

</script>
@endsection
