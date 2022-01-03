@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Welcome Clientes') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="/send" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="subject" class="col-md-4 col-form-label text-md-end">{{ __('Subject') }}</label>

                            <div class="col-md-6">
                                <input id="subject" type="text" class="form-control" name="subject" value="{{ old('subject') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="message" class="col-md-4 col-form-label text-md-end">{{ __('Message') }}</label>

                            <div class="col-md-6">
                                <textarea id="message" type="text" class="form-control" name="message" value="{{ old('message') }}"> </textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('File') }}</label>

                            <div class="col-md-6">
                                <input id="file" type="file" class="form-control" name="file">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
