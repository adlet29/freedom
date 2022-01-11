@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-16">
            <div class="card">
                <div class="card-header">{{ __('Tickets') }}</div>
                <div class="card-body">
                    <table class="table table-bordered mb-5">
                        <thead>
                            <tr class="table-success">
                                <th scope="col">#</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Message</th>
                                <th scope="col">User</th>
                                <th scope="col">Email</th>
                                <th scope="col"></th>
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
                                    <td class="text-center">
                                        <a href="/edit/{{ $data->id }}" class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                            </svg>  
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
                {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {!! $tickets->links() !!}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
