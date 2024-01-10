{{-- @extends('layouts.app')

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

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}





<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    <title>Homepage | Dash Ui - Bootstrap 5 Admin Dashboard Template</title>
</head>

<body class="bg-light">
    <div id="db-wrapper">
        <!-- navbar vertical -->
        @include('partials.navbar-vertical')
        <!-- Page content -->
        <div id="page-content">
            @include('partials.header')
            <!-- Container fluid -->
            <div class="bg-primary pt-10 pb-21"></div>
            <div class="container-fluid mt-n22 px-6">

                <!-- row  -->
                <div class="row mt-6">
                    <div class="col-md-12 col-12">
                        <!-- card  -->
                        <div class="card">
                            <div class="card-header bg-white py-4">
                                <h4 class="mb-0">Update Shift</h4>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form method="post" action="{{ url('open-shifts/shift_update', $shift->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" id="title"
                                            value="{{ $shift->title }}">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" rows="3" name="description" placeholder="Description">{{ $shift->description }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="start_date">Start Date and Time</label>
                                        <input type="datetime-local" class="form-control" name="start_date"
                                            id="start_date"
                                            value="{{ \Carbon\Carbon::parse($shift->start_date)->format('Y-m-d\TH:i') }}">
                                        @error('start_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="end_date">End Date and Time</label>
                                        <input type="datetime-local" class="form-control" name="end_date" id="end_date"
                                            value="{{ \Carbon\Carbon::parse($shift->end_date)->format('Y-m-d\TH:i') }}">
                                        @error('end_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" name="location" id="location"
                                            value="{{ $shift->location }}">
                                        @error('location')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Staff Needed</label>
                                        <input type="text" class="form-control" name="staff_needed" id="staff_needed"
                                            value="{{ $shift->staff_needed }}">
                                        @error('location')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- row  -->

            </div>
        </div>
    </div>



    <!-- Scripts -->
    @include('partials.scripts')



</body>

</html>
