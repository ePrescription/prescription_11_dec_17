@extends('layout.master-doctor-inner')

@section('title', 'Dr All Caps')

@section('styles')
@stop
<?php
$dashboard_menu="0";
$patient_menu="0";
$prescription_menu="0";
$lab_menu="1";
$profile_menu="0";
?>
@section('content')
<div class="wrapper">
    @include('portal.doctor-header')
    <!-- Left side column. contains the logo and sidebar -->
    <!-- sidebar: style can be found in sidebar.less -->
    @include('portal.doctor-sidebar')
    <!-- /.sidebar -->



    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <div class="">
                <div class="page-header-title">
                    <h4 class="page-title">Lab Test Details</h4>
                </div>
            </div>

            <div class="page-content-wrapper ">

                <div class="container">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <!-- <div class="panel-heading">
                                    <h4>Invoice</h4>
                                </div> -->
                                <div class="panel-body">

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="invoice-title">
                                                <h4 class="pull-right">Lab Test ID # {{$labTestDetails['LabTestInfo'][0]->LTID}}</h4>
                                                <h3 class="m-t-0">Lab Test Details</h3>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <address>
                                                        <strong>Patient Details:</strong><br>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">Patient ID</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['PatientProfile'][0]->pid}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">Patient Name</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['PatientProfile'][0]->name}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">Phone Number</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['PatientProfile'][0]->telephone}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">E-Mail</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['PatientProfile'][0]->email}}
                                                            </div>
                                                        </div>
                                                    </address>
                                                </div>
                                                <div class="col-xs-6">
                                                    <address>
                                                        <strong>Doctor Details:</strong><br>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">Doctor ID</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['DoctorProfile'][0]->did}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">Doctor Name</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['DoctorProfile'][0]->name}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">Phone Number</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['DoctorProfile'][0]->telephone}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="col-sm-6 control-label">E-Mail</label>
                                                            <div class="col-sm-6">
                                                                {{$labTestDetails['DoctorProfile'][0]->email}}
                                                            </div>
                                                        </div>
                                                    </address>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <address>
                                                        <strong>Notes:</strong><br>
                                                        @foreach($labTestDetails['LabTestInfo'] as $labtestinfo)
                                                            {{$labtestinfo->brief_description}}
                                                        @endforeach
                                                    </address>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <address>
                                                        <strong>Lab Test Date:</strong><br>
                                                        {{$labTestDetails['LabTestInfo'][0]->labtest_date}}
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><strong>TEST DETAILS</strong></h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>TEST NAME</th>
                                                                <th>TEST DETAILS</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($labTestDetails['PatientLabTestDetails'] as $labtest)
                                                                <tr>
                                                                    <td>{{$labtest->test_name}}</td>
                                                                    <td>{{$labtest->brief_description}}</td>
                                                                    <td>
                                                                        @if(!is_null($labtest->labtest_report))
                                                                            <a target="_blank" href="{{$labtest->labtest_report}}">Download</a>
                                                                        @else
                                                                            None
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="hidden-print">
                                                        <div class="pull-right">
                                                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                            <a href="#" class="btn btn-primary waves-effect waves-light hidden">Send</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div> <!-- end row -->
                                </div> <!-- panel body -->
                            </div> <!-- end panel -->

                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->


                </div><!-- container -->


            </div> <!-- Page content Wrapper -->

        </div> <!-- content -->

        @include('portal.doctor-footer')

    </div>
    <!-- End Right content here -->

</div><!-- ./wrapper -->

<script>
    function printDiv()
    {

        var divToPrint=document.getElementById('PagePrint');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();

        setTimeout(function(){newWin.close();},10);

    }
</script>
@endsection

@section('scripts')
    {!!  Html::script(asset('theme/assets/js/app.js')) !!}
@stop
