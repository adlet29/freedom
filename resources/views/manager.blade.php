@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-16">
            <div class="card">
                <div class="card-header">{{ __('Tickets') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered mb-5">
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Message</th>
                        <th scope="col">User</th>
                        <th scope="col">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $data)
                    <tr>
                        <th scope="row">{{ $data->id }}</th>
                        <td>{{ $data->subject }}</td>
                        <td>{{ $data->message }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
