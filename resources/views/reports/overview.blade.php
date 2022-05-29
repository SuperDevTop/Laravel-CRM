@extends('master')
@section('page_name')
    Overview
@stop

<?php
    use App\Models\User;
?>

@section('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Advertising Type', 'Count'],
                @foreach($extraData['new-client-chart'] as $name => $count)
                    [{{ json_encode($name) }}, {{ $count}}],
                @endforeach
            ]);

            var options = {
                legend: 'none',
                backgroundColor: {
                    fill: 'transparent'
                },
                chartArea: {'width': '100%', 'height': '80%'},
            };

            var chart = new google.visualization.PieChart(document.getElementById('new-clients'));

            chart.draw(data, options);

            var data = google.visualization.arrayToDataTable([
                ['Advertising Type', 'Count'],
                @foreach($extraData['all-quotes-chart'] as $name => $count)
                    [{{ json_encode($name) }}, {{ $count}}],
                @endforeach
            ]);

            var options = {
                legend: 'none',
                backgroundColor: {
                    fill: 'transparent'
                },
                chartArea: {'width': '100%', 'height': '80%'},
            };

            var chart = new google.visualization.PieChart(document.getElementById('all-quotes'));

            chart.draw(data, options);

            var data = google.visualization.arrayToDataTable([
                ['Advertising Type', 'Count'],
                @foreach($extraData['pending-completed-chart'] as $name => $count)
                    [{{ json_encode($name) }}, {{ $count}}],
                @endforeach
            ]);

            var options = {
                legend: 'none',
                backgroundColor: {
                    fill: 'transparent'
                },
                chartArea: {'width': '100%', 'height': '80%'},
            };

            var chart = new google.visualization.PieChart(document.getElementById('completed-pending-quotes'));

            chart.draw(data, options);

            
        }
    </script>
@stop

@section('content')
    <div class="frame">
        <div class="bit-1">
            <div class="container" style="width: 650px; margin: 0 auto;">
                <div class="container_content" style="text-align: center;">
                    <form action="" method="GET">
                        See report by date: 
                        <input type="text" name="date1" class="basinput date" value="{{ Request::get('date1') }}" style="width:130px;">
                         to 
                        <input type="text" name="date2" class="basinput date" value="{{ Request::get('date2') }}" style="width:130px;">
                        <button type="submit" class="btn btn-default"><i class="fa fa-arrow-right"></i> Go</button>
                        @if (Request::has('date1'))
                            <button type="button" data-href="reports/overview" class="btn btn-grey"><i class="fa fa-remove"></i> Disable date filter</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="frame">
        <div class="bit-3">
            <div class="container">
                <div class="container_content" style="height: 256px;">
                    @if (Request::has('date1'))
                        <h2><i class="fa fa-calendar-o"></i> Details from {{ Request::get('date1') }} - {{ Request::get('date2') }}</h2>
                    @else
                        <h2><i class="fa fa-calendar-o"></i> Details from Today</h2>
                    @endif
                    <br><br><br>
                    <table style="table-layout: fixed; width: 100%;">
                        <tr>
                            <td style="width: 200px;">Comments created</td>
                            <td style="width: 100%;">
                                <b>{{ $today_comments_added }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Quotes created</td>
                            <td>
                                <b>{{ $today_quotes_created }}</b>
                                for a total of
                                <b>&euro;{{ number_format($today_quotes_created_sum, 2, '.', ',') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Invoices created</td>
                            <td>
                                <b>{{ $today_invoices_created }}</b>
                                for a total of
                                <b>&euro;{{ number_format($today_invoices_created_sum, 2, '.', ',') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Quotes completed</td>
                            <td>
                                <b>{{ $today_quotes_completed }}</b>
                                for a total of
                                <b>&euro;{{ number_format($today_quotes_completed_sum, 2, '.', ',') }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Completed pending payment</td>
                            <td>
                                <b>{{ $today_pending_payment }}</b>
                                for a total of
                                <b>&euro;{{ number_format($today_pending_payment_sum, 2, '.', ',') }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="bit-22">
        <div class="container">
            <div class="container_content">
                <h2 class="center">New Clients</h2>
                <div class="report-overview-chart" id="new-clients"></div>
            </div>
        </div>
    </div>
    <div class="bit-22">
        <div class="container">
            <div class="container_content">
                <h2 class="center">All Quotes</h2>
                <div class="report-overview-chart" id="all-quotes"></div>
            </div>
        </div>
    </div>
    <div class="bit-22">
        <div class="container">
            <div class="container_content">
                <h2 class="center">Completed & Pending Payment</h2>
                <div class="report-overview-chart" id="completed-pending-quotes"></div>
            </div>
        </div>
    </div>
    <br clear="both">
    @foreach(User::where('disabled', '=', 0)->get() as $user)
        <div style="float:left; width: 300px; margin-right: 20px; background-color: #FFF; margin: 10px; padding: 15px;">
            <center><img src="users/{{ $user->id }}/photo?thumb=true" width="150"></center>
            <br>
            <div style="font-weight: bold; font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $user->getFullname() }}</div>


            <table style="table-layout: fixed; width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 140px;">Number of comments</td>
                    <td style="width: 45px;"><b>{{ $user_comments[$user->id] }}</b></td>
                    <td style="width: 100%;"></td>
                </tr>
                <tr>
                    <td>Quotes created</td>
                    <td><b>{{ $user_quotes[$user->id] }}</b></td>
                    <td>( <b>&euro;{{ number_format($user_quotes_sum[$user->id], 2, '.', ',') }}</b> )</td>
                </tr>
                <tr>
                    <td>Invoices created</td>
                    <td><b>{{ $user_invoices[$user->id] }}</b></td>
                    <td>( <b>&euro;{{ number_format($user_invoices_sum[$user->id], 2, '.', ',') }}</b> )</td>
                </tr>
                <tr>
                    <td>Quotes in progress</td>
                    <td><b>{{ $user_quotes_progress[$user->id] }}</b></td>
                    <td>( <b>&euro;{{ number_format($user_quotes_progress_sum[$user->id], 2, '.', ',') }}</b> )</td>
                </tr>
                <tr>
                    <td>Quotes completed</td>
                    <td><b>{{ $user_quotes_completed[$user->id] }}</b></td>
                    <td>( <b>&euro;{{ number_format($user_quotes_completed_sum[$user->id], 2, '.', ',') }}</b> )</td>
                </tr>
                <tr>
                    <td>Quotes pending payment</td>
                    <td><b>{{ $user_quotes_pending[$user->id] }}</b></td>
                    <td>( <b>&euro;{{ number_format($user_quotes_pending_sum[$user->id], 2, '.', ',') }}</b> )</td>
                </tr>
            </table>
        </div>
    @endforeach
@stop