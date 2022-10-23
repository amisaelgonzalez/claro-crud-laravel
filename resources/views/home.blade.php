@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td class="text-left">{{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td class="text-left">{{ auth()->user()->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td class="text-left">{{ auth()->user()->phone }}</td>
                            </tr>
                            <tr>
                                <th>Identification</th>
                                <td class="text-left">{{ auth()->user()->identification }}</td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td class="text-left">{{ auth()->user()->age }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
