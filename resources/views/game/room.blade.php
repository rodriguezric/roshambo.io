@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div 
                v-show="!bothAreReady()"
                class="panel panel-default"
             >
                <!-- Seats Section -->
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

            <!-- Game State -->
            <div    
                v-show="bothSitting() && !bothAreReady()"
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
                v-show="bothAreReady() && bothAreHealthy()"
                class="panel panel-default"
            >
                <div class="panel-heading">Battle: @{{seats.seat1}} vs @{{seats.seat2}}</div>
                <div class="panel-body">
                    <div class="col-md-4">
                        <pre id="stats1">@{{seats.seat1}}
****************
HP: @{{health.health1}}/3 </pre>
                    </div>                    
                    <div 
                        v-show="userIsPlaying()"
                        class="col-md-4"
                    >
                        <div 
                            v-show="!iAmAttacking()"
                            class="text-center center-block"
                            style="padding-bottom: 1em"
                        >
                            <h4>Choose your attack:</h4> 
                            <button 
                                class="btn btn-lg btn-danger"
                                v-on:click="clickAttack('fire')"
                            >
                                <span class="glyphicon glyphicon-fire"></span>
                            </button>
                            <button 
                                class="btn btn-lg btn-primary"
                                v-on:click="clickAttack('water')"
                            >
                                <span class="glyphicon glyphicon-tint"></span>
                            </button>
                            <button 
                                class="btn btn-lg btn-success"
                                v-on:click="clickAttack('leaf')"
                            >
                                <span class="glyphicon glyphicon-leaf"></span>
                            </button>
                            <h4 v-if="opponentIsAttacking()">
                                Opponent is waiting for you!
                            </h4>
                        </div>
                        <div 
                            v-show="iAmAttacking() && !opponentIsAttacking()"
                            class="text-center center-block"
                        >
                            <h4>Waiting for Opponent.</h4>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                        <pre id="stats2">@{{seats.seat2}}
****************
HP: @{{health.health2}}/3</pre>
                    </div>                    

                </div>
            </div>

            <div    
                v-show="bothAreReady() && bothAreHealthy()"
                class="panel panel-default"
            >
                <div class="panel-heading">Battle Log</div>
                <div class="panel-body">
                    <div style="height:200px, overflow: scroll"
                        <ul id="messages">
                            <li v-for="entry in log">
                                @{{entry.message}}
                            </li>
                        </ul>
                     </div>
                    <form action="">
                        <input id="m" autocomplete="off" />
                        <button class="btn">Send</button>
                    </form>
                </div>
            </div>
            
            
            <div    
                v-show="someoneIsDead()"
                class="panel panel-default"
            >
                <div class="panel-heading">Battle: @{{seats.seat1}} vs @{{seats.seat2}}</div>
                <div class="panel-body">
                    <div class="col-md-4">
                    </div>                    
                    <div class="col-md-4">
                        <div class="text-center center-block">
                            <h4> @{{ whoWon() }} Wins!</h4>
                        </div>
                    </div>                    
                    <div class="col-md-4">
                    </div>                    

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    function otherSeat (seat) {
        if (seat === '1') {
            return '2';
        }
        return '1';
    }

    var socket = io('http://50.116.47.108:3000');

    var vue = new Vue({
        el: 'body',

        data: {
            room: '{{$room}}',
            user: '{{ Auth::user()->name }}',
            seats: [],
            ready: [],
            attack: [],
            health: [],
            log: []
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
                $.ajax({
                    type: "POST",
                    url: "/api/room/ready",
                    data: {
                        room: this.room,
                        seat: this.getMySeatNumber()
                    }
                });
            },

            clickAttack: function(attack) {
                $.ajax({
                    type: "POST",
                    url: "/api/room/attack",
                    data: {
                        room: this.room,
                        seat: this.getMySeatNumber(),
                        attack: attack
                    }
                });
            },

            //Helper function for finding seat #
            getMySeatNumber: function () {
                var seat = false;

                if (this.seats.seat1 === this.user) {
                    seat = 1;
                }

                if (this.seats.seat2 === this.user) {
                    seat = 2;
                }

                return seat;
            },
            getOpponentSeatNumber: function () {
                if (this.getMySeatNumber() === 1) {
                    return 2;
                }
                return 1
            },
            performAttacks: function () {
                $.ajax({
                    type: "POST",
                    url: "/api/room/attack",
                    data: {
                        room: this.room,
                        seat: 1,
                        attack: null
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/api/room/attack",
                    data: {
                        room: this.room,
                        seat: 2,
                        attack: null
                    }
                });
            },

            iAmReady: function () {
                return this.ready['ready'+this.getMySeatNumber()];
            },
            opponentIsReady: function () {
                opponent = this.getOpponentSeatNumber();
                return this.ready['ready'+opponent];
            },

            iAmAttacking: function () {
                return this.attack['attack'+this.getMySeatNumber()];
            },
            opponentIsAttacking: function () {
                opponent = this.getOpponentSeatNumber();
                return this.attack['attack'+opponent];
            },
            userIsPlaying: function () {
                return this.user === this.seats.seat1 ||
                       this.user === this.seats.seat2;
            },

            /***********************************
             * Functions for determining state *
             ***********************************/
            bothSitting: function () {
                return this.seats.seat1 != null &&
                       this.seats.seat2 != null &&
                      (this.user === this.seats.seat1 ||
                       this.user === this.seats.seat2) 
            },
            bothAreReady: function () {
                return this.ready.ready1 && 
                       this.ready.ready2;
            },
            bothAttacking: function () {
                return this.attack.attack1 && 
                       this.attack.attack2;
            },
            bothAreHealthy: function () {
                return this.health.health1 > 0 && 
                       this.health.health2 > 0;
            },
            someoneIsDead: function () {
                return this.health.health1 === '0' ||
                       this.health.health2 === '0';
            },
            whoWon: function () {
                var winner = this.seats.seat1;

                if (this.health.health1 === '0') {
                    winner = this.seats.seat2;
                }

                return winner;
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
                this.ready = data;
            }.bind(this));

            $.getJSON('/api/room/'+this.room+'/health', function(data) {
                this.health = data;
                console.log("Health 1: " +this.health.health1);
                console.log("Health 2: " +this.health.health2);
            }.bind(this));

            $.getJSON('/api/room/'+this.room+'/attack', function(data) {
                this.attack = data;

                if (this.bothAttacking()) {
                    
                }
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

                if (this.bothAreReady()) {
                    this.health = {
                        'health1' : 3,
                        'health2' : 3
                    }
                }
                
                return;
            }.bind(this));

            socket.on('game-channel:App\\Events\\Attack', function(data) {
                this.attack['attack'+data.seat] = data.attack;

                if (this.bothAttacking()) {
                    var whoTakesDamage = {
                        "firefire"   : 0,
                        "firewater"  : 1,
                        "fireleaf"   : 2,

                        "waterfire"  : 2,
                        "waterwater" : 0,
                        "waterleaf"  : 1,

                        "leaffire"   : 1,
                        "leafwater"  : 2,
                        "leafleaf"   : 0
                    };

                    this.log.push({ 'message' : this.seats.seat1 + " uses " + this.attack.attack1 + " vs. " + this.seats.seat2 + " using " + this.attack.attack2 + "!" });

                    var concatAttack = this.attack.attack1+this.attack.attack2;
                    var seatNumber = whoTakesDamage[concatAttack];

                    if (seatNumber === 0) {
                        $("#stats1").effect("shake", {
                            direction : "up",
                            distance : 10,
                            times : 1
                        });
                        $("#stats2").effect("shake", {
                            direction : "up",
                            distance : 10,
                            times : 1
                        });
                    }

                    if (this.health["health"+seatNumber] === '1') {
                        $("#stats"+seatNumber).effect("explode");
                    } else {
                        $("#stats"+seatNumber).effect("shake");
                    }


                    this.performAttacks();

                    $.getJSON('/api/room/'+this.room+'/health', function(data) {
                        this.health = data;
                    }.bind(this));
                }
                return;
            }.bind(this));

            socket.on('chat message', function (data) {
                 this.log.push({ 'message' : data.user + ": " + data.message }); 
            }.bind(this));
        }
    });

    $('form').submit(function(){
        socket.emit('chat message', { message : $('#m').val(), user : '{{ Auth::user()->name }}' });
        $('#m').val('');
        return false;
    });

</script>
@endsection
