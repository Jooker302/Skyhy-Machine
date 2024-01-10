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
                                <h4 class="mb-0">Available Requests</h4>

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

                                            <th>User Name</th>
                                            <th>User Email</th>
                                            <th>User Phone</th>
                                            <th>Title</th>
                                            <th>Shift Description</th>

                                            <th>User remarks.</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>


                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($shiftRequests as $request)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $request->user->name }}</td>
                                                <td>{{ $request->user->email }}</td>
                                                <td>{{ $request->user->phone_number }}</td>
                                                <td>{{ isset($request->shift->title) ? $request->shift->title : '' }}
                                                </td>
                                                <td>{{ isset($request->shift->description) ? $request->shift->description : '' }}
                                                </td>
                                                <td>{{ $request->shift_description }}</td>
                                                <td>{{ $request->shift->start_date }}</td>
                                                <td>{{ $request->shift->end_date }}</td>




                                                <td>
                                                    @isset($request->id)
                                                        @if ($request->status == 'pending')
                                                            <form
                                                                action="{{ route('shift-requests.accept', $request->id) }}"
                                                                method="post" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="shift"
                                                                    value="{{ isset($request->shift->id) ? $request->shift->id : '' }}">
                                                                <button type="submit"
                                                                    class="btn btn-success">Accept</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('shift-requests.reject', $request->id) }}"
                                                                method="get" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger">Reject</button>
                                                            </form>
                                                        @elseif ($request->status == 'accepted')
                                                            <form
                                                                action="{{ route('shift-requests.reject', $request->id) }}"
                                                                method="post" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger">Reject</button>
                                                            </form>
                                                            <span class="badge badge-success">Accepted</span>
                                                        @elseif ($request->status == 'rejected')
                                                            <form
                                                                action="{{ route('shift-requests.accept', ['id' => $request->id, 'shift_id' => isset($request->shift->id) ? $request->shift->id : '']) }}"
                                                                method="post" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-success">Accept</button>
                                                            </form>
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    @else
                                                        <!-- If $request->id is not set -->
                                                        ID Not Set
                                                    @endisset
                                                </td>


                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No pending requests</td>
                                            </tr>
                                        @endforelse



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
                    <form method="post" action="{{ route('open_shifts') }}" id="createShiftForm">
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
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" class="form-control" name="end_time" id="end_date">
                            @error('end_time')
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
