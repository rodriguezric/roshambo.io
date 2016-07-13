@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                {{ //Seats Section }}
                <div class="panel-heading">Room: @{{room}}</div>
                <div class="panel-body">
                    
                    <button 
                        class="btn" 
                        v-show="seats.seat1 === null"
                        v-on:click="sitdown(room, 1, user)"
                    >
                        Seat 1
                    </button>
                    
                    <button 
                        class="btn"
                        v-else 
                        v-on:click="standup(room, 1, user)"
                    >
                        @{{seats.seat1}}
                    </button>
                    
                    <button 
                        v-show="seats.seat2 === null"
                        class="btn pull-right" 
                        v-on:click="sitdown(room, 2, user)"
                    >
                            Seat 2
                    </button>
                    
                    <button 
                        class="btn pull-right"
                        v-else 
                        v-on:click="standup(room, 2, user)"
                    >
                        @{{seats.seat2}}
                    </button>

                </div>

            </div>

            {{ //Game State }}
            <div    
                v-show="bothsitting() && !gameready()"
                class="panel panel-default"
            >
                <div class="panel-heading">Battle: @{{seats.seat1}} vs @{{seats.seat2}}</div>
                <div class="panel-body">
                    <div class="col-md-4">
                    </div>                    
                    <div class="col-md-4">
                        <div class="text-center center-block">
                            <div v-show="!iAmReady()">
                                <h4>Click to Start Battle</h4> 
                                <button 
                                    class="btn btn-lg btn-primary"
                                    v-on:click="clickReady()"
                                >
                                    Start Match
                                </button>
                                <h4 v-show="opponentIsReady()">
                                    Opponent is waiting for you!
                                </h4>
                            </div>
                            <div v-show="iAmReady() && !opponentIsReady()">
                                <h4>Waiting for Opponent.</h4>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                    </div>                    

                </div>
            </div>

            
            <div    
                v-show="gameready()"
                class="panel panel-default"
            >
                <div class="panel-heading">Battle: @{{seats.seat1}} vs @{{seats.seat2}}</div>
                <div class="panel-body">
                    <div class="col-md-4">
                        <pre>
@{{seats.seat1}}
****************
HP: 3/3
                        </pre>
                    </div>                    
                    <div class="col-md-4">
                        <div class="text-center center-block">
                            <h4>Choose your attack:</h4> 
                            <button class="btn btn-lg btn-danger">
                                <span class="glyphicon glyphicon-fire"></span>
                            </button>
                            <button class="btn btn-lg btn-primary">
                                <span class="glyphicon glyphicon-tint"></span>
                            </button>
                            <button class="btn btn-lg btn-success">
                                <span class="glyphicon glyphicon-leaf"></span>
                            </button>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <pre>
@{{seats.seat2}}
****************
HP: 3/3
                        </pre>
                    </div>                    

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
            user: '{{ Auth::user()->name }}',
            seats: [],
            ready: []
        },

        methods: {
            /*************************
             * Functions for actions *
             *************************/
            sitdown: function(room, seat, user) {
                $.ajax({
                    type: "POST",
                    url:  "/api/room/sitdown",
                    data: {
                        room: room,
                        user: user,
                        seat: seat
                    },
                    success: function(data) {
                    }
                });
            },
            standup: function(room, seat, user) {
                $.ajax({
                    type: "POST",
                    url:  "/api/room/standup",
                    data: {
                        room: room,
                        user: user,
                        seat: seat
                    },
                    success: function(data) {
                    }
                });
            },
            clickReady: function() {
                var dbug =  {
                        room: this.room,
                        seat: this.getSeat()
                };
                console.log(dbug);
                $.ajax({
                    type: "POST",
                    url: "/api/room/ready",
                    data: {
                        room: this.room,
                        seat: this.getSeat()
                    }
                });
            },

            //Helper function for finding seat #
            getSeat: function () {
                var seat = false;

                if (this.seats.seat1 === this.user) {
                    seat = 1;
                }

                if (this.seats.seat2 === this.user) {
                    seat = 2;
                }

                return seat;
            },
            iAmReady: function () {
                return this.ready['ready'+this.getSeat()];
            },
            opponentIsReady: function () {
                opponent = 1;
                if (opponent === this.getSeat()) {
                    opponent = 2;
                }
                return this.ready['ready'+opponent];
            },

            /***********************************
             * Functions for determining state *
             ***********************************/
            bothsitting: function () {
                return this.seats.seat1 != null &&
                       this.seats.seat2 != null &&
                      (this.user === this.seats.seat1 ||
                       this.user === this.seats.seat2) 
            },
            gameready: function () {
                return this.ready.ready1 && 
                       this.ready.ready2;
            }
        },
        ready: function () {
            /*******************
             * Initialize Data *
             *******************/
            $.getJSON('/api/room/'+this.room+'/seats', function(data) {
                this.seats = data;
            }.bind(this));

            $.getJSON('/api/room/'+this.room+'/ready', function(data) {
                console.log(data);
                this.ready = data;
            }.bind(this));

            /***********
             * Sockets *
             ***********/
            socket.on('game-channel:App\\Events\\SitDown', function(data) {
                console.log(data);
                if (data.result === true) {
                    this.seats['seat'+data.seat] = data.user;
                    return;
                }

                if (data.user === this.user) {
                    alert("You are already sitting in this room.");
                }
            }.bind(this));

            socket.on('game-channel:App\\Events\\StandUp', function(data) {
                console.log(data);
                if (data.result === true) {
                    this.seats['seat'+data.seat] = null;
                    return;
                } 
            }.bind(this));

            socket.on('game-channel:App\\Events\\Ready', function(data) {
                this.ready['ready'+data.seat] = true;
                return;
            }.bind(this));
        }
    });

</script>
@endsection
