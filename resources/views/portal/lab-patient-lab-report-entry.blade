@extends('layout.master-lab-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
    <style>.tab-pane { min-height: 300px; }</style>
@stop
<?php
$dashboard_menu="1";
$lab_menu="0";
$patient_menu="0";
$profile_menu="0";
?>
@section('content')
<div class="wrapper">
    @include('portal.lab-header')
    <!-- Left side column. contains the logo and sidebar -->
    <!-- sidebar: style can be found in sidebar.less -->
    @include('portal.lab-sidebar')
    <!-- /.sidebar -->


    <!-- Start right Content here -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">

            <div class="">
                <div class="page-header-title">
                    <h4 class="page-title">Patient Lab Details</h4>
                </div>
            </div>

            <div class="page-content-wrapper ">

                <div class="container">

                    <div class="row">
                        <div class="col-sm-12">



                            <div class="panel panel-primary">
                                <div class="panel-body">


                                    <div style="float:right;">
                                        <a href="{{URL::to('/')}}/lab/rest/api/{{Auth::user()->id}}/hospital/{{Session::get('LoginUserHospital')}}/patients">
                                            <button class="btn btn-info waves-effect waves-light">Back to Patients List</button>
                                        </a>
                                    </div>

                                    <div style="float:right;display:none;">
                                    <button style="margin: 0px 10px;" type="button" id="btn" value="Print" class="btn btn-success waves-effect waves-light" onclick="javascript:printDiv();" ><i class="icon-print"></i> Print</button>
                                    </div>

                                    <div style="float:right; display:none;">
                                        <a style="margin: 0px 10px;" href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patientDetails[0]->patient_id}}/labtestreceipts?hospitalId={{Auth::user()->id}}">
                                            <button class="btn btn-error waves-effect waves-light">Generate Receipt</button>
                                        </a>
                                    </div>
                                    <h4 class="m-t-0 m-b-30">Patient Lab Details</h4>


                                    @if (session()->has('message'))
                                        <div class="col_full login-title">
                                            <span style="color:red;">
                                                <p class="text-danger">{{session('message')}}</p>
                                            </span>
                                        </div>
                                    @endif

                                    @if (session()->has('success'))
                                        <div class="col_full login-title">
                                            <span style="color:green;">
                                                <p class="text-success">{{session('success')}}</p>
                                            </span>
                                        </div>
                                    @endif


                                    <div class="row">

                                        <div class="col-lg-12">


                                            <div class="form-group col-md-4">
                                                <label class="col-sm-3 control-label">PID</label>
                                                <div class="col-sm-9">
                                                    {{$patientDetails[0]->pid}}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-sm-3 control-label">Name</label>
                                                <div class="col-sm-9">
                                                    {{$patientDetails[0]->name}}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-sm-3 control-label">Number</label>
                                                <div class="col-sm-9">
                                                    {{$patientDetails[0]->telephone}}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-sm-3 control-label">E-Mail</label>
                                                <div class="col-sm-9">
                                                    {{$patientDetails[0]->email}}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-sm-3 control-label">Age</label>
                                                <div class="col-sm-9">
                                                    {{$patientDetails[0]->age}}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-sm-3 control-label">Gender</label>
                                                <div class="col-sm-9">
                                                    @if($patientDetails[0]->gender==1) Male @else Female @endif
                                                </div>
                                            </div>
                                            <div class="hidden form-group col-md-4">
                                                <label class="col-sm-3 control-label">Relationship</label>
                                                <div class="col-sm-9">
                                                    {{$patientDetails[0]->relationship}}
                                                </div>
                                            </div>
                                            <div class="hidden form-group col-md-4">
                                                <label class="col-sm-6 control-label">Relation Name</label>
                                                <div class="col-sm-6">
                                                    {{$patientDetails[0]->spouseName}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <form action="{{URL::to('/')}}/lab/{{Auth::user()->id}}/hospital/{{Session::get('LoginUserHospital')}}/patient/{{$patientDetails[0]->id}}/labreports" role="form" method="POST" enctype="multipart/form-data" class="form-horizontal ">
                                                <style>
                                                    .addButton, .removeButton { float:right; }
                                                </style>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Report Document Upload Details</label><br /><br />
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-1 control-label"></label>
                                                    <div class="col-sm-10" id="symptom-form">
                                                        <div class="form-group">
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                Report Type
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                Report Name
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                Report Document
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                <select id="reporttypebox" class="form-control" name="lab_documents[0][test_category_name]" required="required">
                                                                    <option value="Blood">Blood</option>
                                                                    <option value="Motion">Motion</option>
                                                                    <option value="Urine">Urine</option>
                                                                    <option value="Ultra Sound">Ultra Sound</option>
                                                                    <option value="Scan">Scan</option>
                                                                    <option value="Dental">Dental</option>
                                                                    <option value="X-Ray">X-Ray</option>
                                                                </select>

                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                <input type="text" class="form-control" name="lab_documents[0][document_name]" placeholder="Report Name" required="required" />
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                <input type="file" class="form-control" name="lab_documents[0][document_upload_path]" required="required" style="width:80%;float: left;" />
                                                                <div class="btn btn-primary addButton">+</div>
                                                            </div>

                                                        </div>

                                                        <div class="form-group remove-doc-box hide" id="symptomTemplate">
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                <select class="form-control" name="report_type">
                                                                    <option value="Blood">Blood</option>
                                                                    <option value="Motion">Motion</option>
                                                                    <option value="Urine">Urine</option>
                                                                    <option value="Ultra Sound">Ultra Sound</option>
                                                                    <option value="Scan">Scan</option>
                                                                    <option value="Dental">Dental</option>
                                                                    <option value="X-Ray">X-Ray</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                <input type="text" class="form-control" name="report_name" placeholder="Report Name" />
                                                            </div>
                                                            <div class="col-md-4 col-sm-4 col-xs-4">
                                                                <input type="file" class="form-control" name="report_document" placeholder="Symptom" style="width:80%;float: left;" />
                                                                <div class="btn btn-default removeButton min-button" value="-">-</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-6">
                                                        <input type="hidden" class="form-control" name="patient_id" value="{{$patientDetails[0]->patient_id}}" required="required" />
                                                        <input type="hidden" class="form-control" name="lab_id" value="{{Auth::user()->id}}" required="required" />
                                                        <input type="hidden" class="form-control" name="hospital_id" value="{{Session::get('LoginUserHospital')}}" required="required" />
                                                        <input type="submit" name="addsymptom" value="Save" class="btn btn-success">

                                                        <a href="{{URL::to('/')}}/fronthospital/rest/api/{{Auth::user()->id}}/patient/{{$patientDetails[0]->patient_id}}/medical-details" style="margin: 0px 16px;"><button type="button" class="btn btn-success"> Cancel </button></a>
                                                    </div>
                                                </div>

                                            </form>


                                        </div>
                                    </div>

                                </div> <!-- panel-body -->
                            </div> <!-- panel -->
                        </div> <!-- col -->
                    </div> <!-- End row -->

                </div><!-- container -->


            </div> <!-- Page content Wrapper -->

        </div> <!-- content -->

        @include('portal.lab-footer')

    </div>
    <!-- End Right content here -->


</div><!-- ./wrapper -->

@endsection



@section('scripts')


    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <script>

        $(document).ready(function(){
            $( "input#TestTime" ).timepicker({
                timeFormat: 'HH:mm:ss',
                interval: 60,
                minTime: '10',
                maxTime: '6:00pm',
                defaultTime: '11',
                startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });


        function printDiv()
        {
            var divToPrint=document.getElementById('DivIdToPrint');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},10);
        }

        function UpdateTestDates(dateValue) {

            for (var i = 0; i < $('input#TestDates').length; i++) {
                $('input#TestDates').val(dateValue);
            }

        }

        function UpdateTestTimes(timeValue) {

            for (var i = 0; i < $('input#TestTimes').length; i++) {
                $('input#TestTimes').val(timeValue);
            }

        }

    </script>




    <script>
        /*
         $( function() {
         $( "#TestDate" ).datepicker({ dateFormat: 'yy-mm-dd',minDate: new Date() });
         } );
         */
    </script>


    <script>

        $(document).ready(function() {
            symptomIndex = 0;
            $('#symptom-form')
                    .on('click', '.addButton', function() {
                        symptomIndex++;
                        var $template = $('#symptomTemplate'),
                                $clone    = $template.clone().removeClass('hide').removeAttr('id').attr('data-book-index', symptomIndex).insertBefore($template);

                        $clone
                                .find('[name="report_type"]').attr('name', 'lab_documents[' + symptomIndex + '][test_category_name]').end()
                                .find('[name="report_name"]').attr('name', 'lab_documents[' + symptomIndex + '][document_name]').end()
                                .find('[name="report_document"]').attr('name', 'lab_documents[' + symptomIndex + '][document_upload_path]').end();

// Add new fields

                    })

// Remove button click handler
                    .on('click', '.removeButton', function() {
//alert("Hi");
                        var $row  = $(this).parents('.remove-doc-box'),
                                index = $row.attr('data-book-index');

//alert(attr('data-book-index'));
// Remove element containing the fields
                        $row.remove();
                    });
        });

    </script>


@stop