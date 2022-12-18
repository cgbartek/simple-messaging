@extends('layouts.app')

@section('content')
<meta http-equiv="Refresh" content="0; url=/" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Just a sec</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are being redirected...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
