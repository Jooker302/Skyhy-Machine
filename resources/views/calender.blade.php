<!doctype html>
<html lang="en">

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />
</head>

<body>
    <div class="container-fluid">
        <nav id="sidebar" class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">

                    <!-- Add more sidebar items as needed -->
                </ul>
            </div>
        </nav>

        <div class="panel panel-primary">
            <div class="panel-heading">Notifications
                <button type="button" class="btn btn-primary btn-sm float-end" id="showAllNotifications">Show
                    All</button>
                <span class="badge badge-danger" id="notificationCount">{{ $notifications->count() }}</span>
            </div>
            <div class="panel-body">
                <button class="btn btn-sm btn-primary" id="markAll">Mark All</button>
                <button class="btn btn-sm btn-danger" id="deleteAll">Delete All</button>
                @forelse ($notifications as $notification)
                    <div class="notification" data-notification-id="{{ $notification->id }}">
                        <input type="checkbox" class="notification-checkbox"> {{ $notification->message }}
                    </div>
                @empty
                    <div class="notification">No new notifications.</div>
                @endforelse
            </div>
            @if (Auth::user() && Auth::user()->role_id == 2)
                <form method="POST" action="{{ url('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-primary" type="submit">Logout</button>
                </form>
            @endif
        </div>
        <div id="successMessage" class="alert alert-success" style="display: none;"></div>





        <div class="panel panel-primary">
            <div class="panel-heading"> Calendar </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel-body">
                <div id='calendar'></div>
            </div>
        </div>

    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Modal for sending request -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Send Request for Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="requestForm">
                        @csrf
                        <input type="hidden" name="shift_id" id="shiftId">
                        <div class="form-group">
                            <label for="description">Add any information that we need to be aware of i.e. Need
                                transport, need to leave early etc</label>
                            <textarea class="form-control" id="description" rows="3" name="description" id="description"
                                placeholder="Description"></textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="sendRequest">Send Request</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#calendar').fullCalendar({
                events: [
                    @php
                        use Carbon\Carbon;
                    @endphp
                    @foreach ($shifts as $date => $dateShifts)
                        @php
                            $remainingSlots = $dateShifts->count();
                        @endphp

                        @foreach ($dateShifts as $shift)
                            {
                                title: `{{ $shift->title }}\nstart time: {{ date('h:i A', strtotime($shift->start_date)) }}\nend time: {{ date('h:i A', strtotime($shift->end_date)) }}\nRemaining Slots: {{ $remainingSlots }}\nStaff Needed: {{ $shift->staff_needed }} `,
                                start: '{{ $shift->start_date }}',
                                end: '{{ $shift->end_date }}',
                                id: '{{ $shift->id }}',
                            },
                        @endforeach
                    @endforeach

                ],
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView: 'month',
                editable: false,
                eventLimit: true,

                eventClick: function(calEvent, jsEvent, view) {
                    const shiftId = calEvent.id;
                    $('#shiftId').val(shiftId);
                    $('#requestModal').modal('show');
                }
            });

            $('#sendRequest').click(function() {
                const formData = $('#requestForm').serialize();

                $.post('/shift-request', formData, function(data) {
                    $('#requestModal').modal('hide');
                    $('#successMessage').text(
                        'your request for the Shift submit please wait for the admin Approval!');
                    $('#successMessage').show();
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.notification').click(function() {
                var notificationId = $(this).data('notification-id');

                $.ajax({
                    url: '/mark-notification-as-read/' + notificationId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $(this).remove();
                            var newCount = parseInt($('#notificationCount').text()) - 1;
                            $('#notificationCount').text(newCount);
                        }
                    }
                });
            });

            $('#showAllNotifications').click(function() {
                $('#notificationCount').text('0');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#showAllNotifications').click(function() {
                $('#notificationCount').text('0');
            });

            $('#markAll').click(function() {
                $('.notification-checkbox').prop('checked', true);
            });

            $('#deleteAll').click(function() {

                $('.notification-checkbox:checked').each(function() {
                    var notificationId = $(this).closest('.notification').data('notification-id');
                    console.log(notificationId);
                    $.ajax({
                        url: '/delete-notification/' + notificationId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('[data-notification-id="' + notificationId + '"]')
                                    .remove();

                                var newCount = parseInt($('#notificationCount')
                                    .text()) - 1;
                                $('#notificationCount').text(newCount);
                            }
                        }
                    });
                });
            });
        });
    </script>



    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
