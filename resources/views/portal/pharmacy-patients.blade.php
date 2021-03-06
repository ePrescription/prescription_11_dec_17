@extends('layout.master-pharmacy-inner')

@section('title', 'ePrescription and Lab Tests Application')

@section('styles')
    <!-- DataTables -->
    {!!  Html::style(asset('theme/assets/plugins/datatables/jquery.dataTables.min.css')) !!}
    {!!  Html::style(asset('theme/assets/plugins/datatables/buttons.bootstrap.min.css')) !!}
    {!!  Html::style(asset('theme/assets/plugins/datatables/fixedHeader.bootstrap.min.css')) !!}
    {!!  Html::style(asset('theme/assets/plugins/datatables/responsive.bootstrap.min.css')) !!}
    {!!  Html::style(asset('theme/assets/plugins/datatables/dataTables.bootstrap.min.css')) !!}
    {!!  Html::style(asset('theme/assets/plugins/datatables/scroller.bootstrap.min.css')) !!}
@stop
<?php
$dashboard_menu="0";
$prescription_menu="0";
$patient_menu="1";
$profile_menu="0";
?>
@section('content')
    <div class="wrapper">
        @include('portal.pharmacy-header')
        <!-- Left side column. contains the logo and sidebar -->

        <!-- sidebar: style can be found in sidebar.less -->
        @include('portal.pharmacy-sidebar')
        <!-- /.sidebar -->

        <div class="content-page">
            <!-- Start content -->
            <div class="content">

                <div class="">
                    <div class="page-header-title">
                        <h4 class="page-title">Pharmacy Patients List</h4>
                    </div>
                </div>

                <div class="page-content-wrapper ">

                    <div class="container">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <h4 class="m-b-30 m-t-0">Pharmacy Patients Details List</h4>


                                        @if (session()->has('message'))
                                            <div class="col_full login-title">
                                        <span style="color:red;">
                                            <b>{{session('message')}}</b>
                                        </span>
                                            </div>
                                        @endif

                                        @if (session()->has('success'))
                                            <div class="col_full login-title">
                                        <span style="color:green;">
                                            <b>{{session('success')}}</b>
                                        </span>
                                            </div>
                                        @endif

                                        <div>
                                            PID ( Patient Identification)
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <table id="datatable" class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Patient ID</th>
                                                        <th>Name in Full</th>
                                                        <th>Mobile No</th>
                                                        <th>Age</th>
                                                        <th>Gender</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($patients as $patient)
                                                        <tr>
                                                            <td>{{$patient->id}}</td>
                                                            <td>{{$patient->pid}}</td>
                                                            <td>{{$patient->name}}</td>
                                                            <td>{{$patient->telephone}}</td>
                                                            <td>{{$patient->age}}</td>
                                                            <td>@if($patient->gender==1) Male @else Female @endif</td>
                                                            <td>
                                                                <!--
                                                                                                        <a href="#doctorview.html"><button type="submit" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View</button></a>
                                                                -->
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- End Row -->

                    </div><!-- container -->

                </div> <!-- Page content Wrapper -->

            </div> <!-- content -->

            @include('portal.pharmacy-footer')

        </div>
        <!-- End Right content here -->


    </div><!-- ./wrapper -->

@endsection
@section('scripts')
    <!-- Datatables-->
    {!!  Html::script(asset('theme/assets/plugins/datatables/jquery.dataTables.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/dataTables.bootstrap.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/dataTables.buttons.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/buttons.bootstrap.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/jszip.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/pdfmake.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/vfs_fonts.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/buttons.html5.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/buttons.print.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/dataTables.fixedHeader.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/dataTables.keyTable.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/dataTables.responsive.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/responsive.bootstrap.min.js')) !!}
    {!!  Html::script(asset('theme/assets/plugins/datatables/dataTables.scroller.min.js')) !!}

    <!-- Datatable init js -->
    {!!  Html::script(asset('theme/assets/pages/datatables.init.js')) !!}

    {!!  Html::script(asset('theme/assets/js/app.js')) !!}
    {!!  Html::script(asset('theme/assets/js/app.js')) !!}
@stop

