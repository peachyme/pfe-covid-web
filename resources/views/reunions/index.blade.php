@extends('layouts.app')
@include('partials.navbar')
@section('content')
    <div class="main-container d-flex">

        @include('partials.sidebar')
        <div class="content">
            <div class="justify-content-center mx-4">
                @if ($errors->any())
                    <div class="alert alert-danger mt-4">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-success mt-4">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card card-custom mt-4">
                    <div class="card-body p-4">
                        @include('reunions.addReunion')
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            {{-- dayclick modal --}}
            {{-- dayclick modal end --}}
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                monthNames: ['Janvier', 'février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                monthNamesShort: ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juill', 'Aôut', 'Sept',
                    'Oct', 'Nov', 'Dec'
                ],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'mois',
                    week: 'semaine',
                    day: 'jour',
                },
                hiddenDays: [ 5, 6 ],
                height: 500,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: '/reunions',
                // show model when date selected
                selectable: true,
                select: function(start, allDays) {
                    document.getElementById('date_reunion').value = moment(start).format();
                    $('#addReunionModel').modal('toggle');
                },
                editable: true,

                // edit reunions

                eventDrop: function(event) {
                    var id = event.id;
                    var start_date = moment(event.start).format('YYYY-MM-DD');
                    var end_date = moment(event.end).format('YYYY-MM-DD');

                    $.ajax({
                        url: "{{ route('reunions.update', '') }}" + "/" + id,
                        type: "PATCH",
                        dataType: 'json',
                        data: {
                            start_date,
                            end_date
                        },
                        success: function(response) {
                            swal("Très bien!", response, "success", {
                                buttons: false,
                            });
                        },
                        error: function(error) {
                            alert(error);
                        }
                    })
                },

                eventClick: function(event){
                    var id = event.id;
                    window.location.href = "{{ route('reunions.show', '') }}" + "/" + id;
                },

                // eventResize: function(reunion, delta) {
                //     var start = $.fullCalendar.formatDate(reunion.start, 'Y-MM-DD HH:mm:ss');
                //     var end = $.fullCalendar.formatDate(reunion.end, 'Y-MM-DD HH:mm:ss');
                //     var title = reunion.title;
                //     var id = reunion.id;
                //     $.ajax({
                //         url: "/reunions/create",
                //         type: "POST",
                //         data: {
                //             title: title,
                //             start: start,
                //             end: end,
                //             id: id,
                //             type: 'update'
                //         },
                //         success: function(response) {
                //             calendar.fullCalendar('refetchEvents');
                //             alert("Réunin modifée avec succès");
                //         }
                //     })
                // },


                // selectHelper: true,
                // select:function(start, end, allDay)
                // {
                //     var title = prompt('Event Title:');

                //     if(title)
                //     {
                //         var start = $.fullCalendar.formatDate(start, 'Y-MM-DD HH:mm:ss');

                //         var end = $.fullCalendar.formatDate(end, 'Y-MM-DD HH:mm:ss');

                //         $.ajax({
                //             url:"/full-calender/action",
                //             type:"POST",
                //             data:{
                //                 title: title,
                //                 start: start,
                //                 end: end,
                //                 type: 'add'
                //             },
                //             success:function(data)
                //             {
                //                 calendar.fullCalendar('refetchEvents');
                //                 alert("Event Created Successfully");
                //             }
                //         })
                //     }
                // },
                // eventDrop: function(event, delta)
                // {
                //     var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                //     var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                //     var title = event.title;
                //     var id = event.id;
                //     $.ajax({
                //         url:"/full-calender/action",
                //         type:"POST",
                //         data:{
                //             title: title,
                //             start: start,
                //             end: end,
                //             id: id,
                //             type: 'update'
                //         },
                //         success:function(response)
                //         {
                //             calendar.fullCalendar('refetchEvents');
                //             alert("Event Updated Successfully");
                //         }
                //     })
                // },

                // eventClick:function(event)
                // {
                //     if(confirm("Are you sure you want to remove it?"))
                //     {
                //         var id = event.id;
                //         $.ajax({
                //             url:"/full-calender/action",
                //             type:"POST",
                //             data:{
                //                 id:id,
                //                 type:"delete"
                //             },
                //             success:function(response)
                //             {
                //                 calendar.fullCalendar('refetchEvents');
                //                 alert("Event Deleted Successfully");
                //             }
                //         })
                //     }
                // }
            });

        });
    </script>
@endsection
