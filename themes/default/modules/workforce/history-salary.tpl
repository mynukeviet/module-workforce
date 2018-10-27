<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css" />
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form id="form-salary" class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="redirect" value="{ROW.redirect}" />
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.salary}</div>
        <div class="panel-body">
            <div class="form-group">
                <input class="form-control" type="hidden" name="userid" value="{ROW.id}" /> <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.salary_base}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="number" name="salary" value="{ROW.salary}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.allowance}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="number" name="allowance" value="{ROW.allowance}" />
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.hisapproval}</div>
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>{LANG.addtime}</th>
                    <th width="220">{LANG.salary}</th>
                    <th>{LANG.allowance}</th>
                </tr>
                <!-- BEGIN: approval -->
                <tr>
                    <td>{APPROVAL.addtime}</td>
                    <td>{APPROVAL.salary}</td>
                    <td>{APPROVAL.allowance}</td>
                </tr>
                <!-- END: approval -->
            </tbody>
        </table>
    </div>
    <div class="form-group text-center button_fixed_bottom">
        <input type="hidden" name="submit" value="1" /> <input class="btn btn-primary" type="submit" id="btn-submit" value="{LANG.save}" />
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
     $('#form-workforce').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&nocache=' + new Date().getTime(),
            data : $(this).serialize(),
            beforeSend : function() {
                $('#btn-submit').prop('disabled', true);
            },
            success : function(json) {
                if (json.msg) {
                    alert(json.msg);
                }
                if (json.error) {
                    $('#' + json.input).focus();
                    $('#btn-submit').prop('disabled', false);
                    
                } else {
                    /* window.location.href = json.redirect; */
                }
            }
        });
     })
    //]]>
</script>
<!-- END: main -->