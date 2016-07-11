@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Rooms</div>
                <div class="panel-body">
                    
                    <button class="btn">Seat 1</button>
                    <button class="btn">Seat 2</button>
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
        },

        methods: {

        }
    });

</script>
@endsection
