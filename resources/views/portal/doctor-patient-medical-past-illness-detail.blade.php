<style>
    div.control-label {
        text-align: left !important;
    }
</style>

<div class="container">

<div class="row">
<div class="col-sm-12">
<div class="panel panel-primary">
<div class="panel-body">

    <form role="form" method="POST" class="form-horizontal ">
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Endocrine diseases</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[0]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Hyperthyroidism</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[1]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Diabetes</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[2]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">HyperTension</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[3]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">CAD</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[4]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Asthma</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[5]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Tuberculosis</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[6]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Stroke</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[7]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Cancers</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[8]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Blood Transfusion</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[9]->isValueSet==1) Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Surgeries</label>
            <div class="col-sm-6 control-label">
                @if($pastIllness[10]->isValueSet==1) {{$pastIllness[10]->otherIllnessName}} - Yes @else No @endif
            </div>
        </div>
        <div class="form-group col-sm-6">
            <label class="col-sm-6 control-label">Others</label>
            <div class="col-sm-6 control-label">

                @if($pastIllness[11]->isValueSet==1) {{$pastIllness[11]->otherIllnessName}} - Yes @else No @endif
            </div>
        </div>

    </form>


</div> <!-- panel-body -->
</div> <!-- panel -->
</div> <!-- col -->
</div> <!-- End row -->

</div><!-- container -->
