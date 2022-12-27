@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col col-lg-8">
            <div class="card">
                <div class="card-header">Add user</div>
                <div class="card-body">
                    <form method="post" action="{{ route('users.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-label-form">Name</label>
                            <div class="col-sm-10">
                                <input type="text" id="name" name="name" class="form-control" value="{{old('name')}}" required />
                                @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-2 col-label-form">Email</label>
                            <div class="col-sm-10">
                                <input type="text" id="email" name="email" class="form-control" value="{{old('email')}}" required />
                                @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="phone" class="col-sm-2 col-label-form">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" id="phone" name="phone" class="form-control" value="{{old('phone')}}" />
                                @error('phone')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="identification" class="col-sm-2 col-label-form">Identification</label>
                            <div class="col-sm-10">
                                <input type="text" id="identification" name="identification" class="form-control" value="{{old('identification')}}" required />
                                @error('identification')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="birthday" class="col-sm-2 col-label-form">Birthday</label>
                            <div class="col-sm-10">
                                <input type="date" id="birthday" name="birthday" class="form-control" value="{{old('birthday')}}" required />
                                @error('birthday')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-sm-2 col-label-form">Password</label>
                            <div class="col-sm-10">
                                <input type="password" id="password" name="password" class="form-control"required />
                                @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password-confirm" class="col-sm-2 col-label-form">Confirm password</label>
                            <div class="col-sm-10">
                                <input id="password-confirm" type="password" name="password_confirmation" class="form-control" required />
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="country" class="col-sm-2 col-label-form">Country</label>
                            <div class="col-sm-10">
                                <select id="country" class="my-select2"></select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="state" class="col-sm-2 col-label-form">State</label>
                            <div class="col-sm-10">
                                <select id="state" class="my-select2"></select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label for="city" class="col-sm-2 col-label-form">City</label>
                            <div class="col-sm-10">
                                <select id="city" class="my-select2"></select>
                                @error('city_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="hidden" id="city-id" name="city_id" />
                            <input type="submit" class="btn btn-primary" value="Add" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let country;
    let state;
    let city;

    function countrySelect() {
        $('#country').select2({
            ajax: {
                url: '{{ route("api.world.countries") }}',
                dataType: 'json',
                processResults: function (data) {
                    console.log(data.data);
                    return {
                        results: data.data.countries.map(data => {
                            return {
                                id: data.id,
                                text: data.name,
                            }
                        })
                    };

                }
            }
        });

        $('#country').on('select2:select', function (e) {
            let data = e.params.data;
            if (country?.id != data.id) {
                $('#state').val(null);
                $('#state').prop('disabled', false);
                $('#state').trigger('change');

                $('#city').val(null);
                $('#city').prop('disabled', true);
                $('#city').trigger('change');
                $('#city-id').val(null);

                state = null;
                city = null;

                country = data;
            }
        });
    }

    function stateSelect() {
        $('#state').select2({
            ajax: {
                url: '{{ route("api.world.states") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: params.term,
                        country_id: country?.id
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.data.states.map(data => {
                            return {
                                id: data.id,
                                text: data.name,
                            }
                        })
                    };
                }
            }
        });

        $('#state').on('select2:select', function (e) {
            let data = e.params.data;
            if (state?.id != data.id) {
                $('#city').val(null);
                $('#city').prop('disabled', false);
                $('#city').trigger('change');
                $('#city-id').val(null);

                city = null;

                state = data;
            }
        });
    }

    function citySelect() {
        $('#city').select2({
            ajax: {
                url: '{{ route("api.world.cities") }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: params.term,
                        state_id: state?.id
                    }
                },
                processResults: function (data) {
                    return {
                        results: data.data.cities.map(data => {
                            return {
                                id: data.id,
                                text: data.name,
                            }
                        })
                    };

                }
            }
        });

        $('#city').on('select2:select', function (e) {
            let data = e.params.data;
            $('#city-id').val(data.id);
        });
    }

    $(function() {
        countrySelect();
        stateSelect();
        citySelect();
        $('#state').prop('disabled', true);
        $('#city').prop('disabled', true);
    })

</script>
@endsection
