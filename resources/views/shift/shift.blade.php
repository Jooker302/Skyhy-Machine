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
    <title>Homepage </title>
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
                            <!-- card header  -->
                            <div class="card-header bg-white py-4">
                                <h4 class="mb-0">Available Shifts</h4>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Add Shift
                                </button>
                            </div>
                            <!-- table  -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>

                                            <th>Title</th>
                                            <th>start_time</th>
                                            <th>end_time</th>
                                            <th>location</th>
                                            <th>Description</th>
                                            <th>Staff Needed</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shifts as $shift)
                                            <tr>


                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">{{ $shift->title }}</td>
                                                <td class="align-middle">
                                                    {{ \Carbon\Carbon::parse($shift->start_date)->format('Y-m-d H:i:s') }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ \Carbon\Carbon::parse($shift->end_date)->format('Y-m-d H:i:s') }}
                                                </td>


                                                <td class="align-middle">{{ $shift->location }}</td>
                                                <td class="align-middle">{{ $shift->description }}</td>
                                                <td class="align-middle">{{ $shift->staff_needed }}</td>
                                                <td>
                                                    <a href="{{ url('open-shifts/shift_edit', $shift->id) }}"><i
                                                            class="fas fa-edit"></i></a>
                                                    <a href="{{ url('shift.destroy', $shift->id) }}"
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $shift->id }}').submit();"><i
                                                            class="fas fa-trash"></i></a>
                                                    <form id="delete-form-{{ $shift->id }}"
                                                        action="{{ url('open-shifts/destroy', $shift->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach



                                    </tbody>
                                </table>
                            </div>
                            <!-- card footer  -->

                        </div>

                    </div>
                </div>
                <!-- row  -->

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('shift-request') }}" id="createShiftForm">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title"
                                placeholder="Title">
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description" placeholder="Description"></textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date and Time</label>
                            <input type="datetime-local" class="form-control" name="start_date" id="start_date">
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date and Time</label>
                            <input type="datetime-local" class="form-control" name="end_date" id="end_date">
                            @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" name="location" id="location"
                                placeholder="Location">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="location">Staff Needed</label>
                            <input type="number" class="form-control" name="staff_needed" id="staff_needed"
                                placeholder="staff_needed">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        <!-- Scripts -->
        @include('partials.scripts')



</body>

</html>
