<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    {{-- <script src="{{ asset('css/moment.js') }}"></script> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">


</head>

<body class="antialiased">
    <div class="container">
        <div class="row justify-content-center mt-5">
            {{-- <div class="d-none">
                eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlVVNzhVblVyUnAyQnhGN1lMNVVQcVEiLCJleHAiOjE2Mjg3MDY1NDAsImlhdCI6MTYyMDcxNTkzMX0.INVxF6VRUBlJhg5BZ2hC7Wdbs7P7e7PGUlq3Z1xQWHY
            </div> --}}
            <div class="h1 font-weight-bold">
                Get Participants list
            </div>

            <div class="col-md-12">

                @if (isset($error))
                    <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $error }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('get.participants') }}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12 my-1">
                            <label for="#JWTToken">Access Token</label>
                            <input type="text" id="JWTToken"
                                class="form-control w-100 @error('token') is-invalid @enderror"
                                value="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlVVNzhVblVyUnAyQnhGN1lMNVVQcVEiLCJleHAiOjE2Mjg3MDY1NDAsImlhdCI6MTYyMDcxNTkzMX0.INVxF6VRUBlJhg5BZ2hC7Wdbs7P7e7PGUlq3Z1xQWHY"
                                name="token">
                            @error('token')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 my-1">
                            <label for="#formDate">From Date</label>
                            <input type="date" id="formDate"
                                class="form-control w-100 @error('from_date') is-invalid @enderror"
                                value="@isset($from_date){{ $from_date }}@endisset" name="from_date">
                                @error('from_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label for="#toDate">To Date</label>
                                <input type="date" id="toDate" pattern="{yyyy-mm-dd}"
                                    class="form-control w-100 @error('to_date') is-invalid @enderror"
                                    value="@isset($to_date){{ $to_date }}@endisset" name="to_date">
                                    @error('to_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="from-group col-md-12 mt-3">
                                    <input type="submit" id="getDate" class="btn btn-primary btn-block" value="Submit">
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- <div class="col-md-12 mt-4 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <a href="#" class="btn btn-primary">Excel</a>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id='getData' placeholder="Search" class="form-control float-right w-50">
                    </div>
                </div>
            </div> --}}

                    <div class="col-md-12 mt-5">
                        <div class="text-center">
                            <div class="spinner-border" id="loader" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <table id="particitants" class="table">

                        </table>
                    </div>
                </div>
            </div>
            <div class="overlay"></div>
            <script src="{{ asset('js/part.js') }}" defer></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.js"></script>

            <script>
                $('#loader').hide();

                $('#getDate').on('click', function() {
                    $('#loader').show();
                    $('body').addClass('loader');
                });
            </script>

            @if (isset($response))

                <script>
                    $(document).ready(function() {

                        $('#loader').hide();
                        $('body').remove('loader');

                        let row = {!! json_encode($response) !!};
                        if (row) {
                            $("#particitants").DataTable({
                                dom: "Bfrtip",
                                processing: true,
                                bSort: true,
                                bPaginate: true,
                                buttons: ["copy", "excel", "pdf", "print"],
                                data: row,
                                columns: [{
                                        title: "Meeting Id"
                                    },
                                    {
                                        title: "Host Name"
                                    },
                                    {
                                        title: "Meeting Topic"
                                    },
                                    {
                                        title: "Start Time."
                                    },
                                    {
                                        title: "Participant Name"
                                    },
                                    {
                                        title: "Participant Email"
                                    },
                                ],
                            });

                            $(".dt-button").addClass("btn");
                            $(".dt-button").addClass("btn-primary");
                        }
                    });
                </script>
            @endif

        </body>

        </html>
