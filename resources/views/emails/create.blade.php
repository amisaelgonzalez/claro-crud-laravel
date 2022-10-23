@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-8">
            <div class="card">
                <div class="card-header">Add email</div>
                <div class="card-body">
                    <form method="post" action="{{ route('emails.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="subject" class="col-sm-2 col-label-form">Subject</label>
                            <div class="col-sm-10">
                                <input type="text" id="subject" name="subject" class="form-control" value="{{old('subject')}}" required />
                                @error('subject')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="to" class="col-sm-2 col-label-form">To</label>
                            <div class="col-sm-10">
                                <input type="text" id="to" name="to" class="form-control" value="{{old('to')}}" required />
                                @error('to')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="message" class="col-sm-2 col-label-form">Message</label>
                            <div class="col-sm-10">
                                <textarea id="message" name="message" class="form-control" rows="5">{{old('message')}}</textarea>
                                @error('message')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="Add" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
