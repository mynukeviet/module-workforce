<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css" />
<!-- BEGIN: error -->
<div class="alert alert-warning">{ERROR}</div>
<!-- END: error -->
<form id="form-workforce" class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="id" value="{ROW.id}" />
            <input type="hidden" name="redirect" value="{ROW.redirect}" />
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.fullname}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <div class="row">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="last_name" value="{ROW.last_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.last_name}" />
                        </div>
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="first_name" value="{ROW.first_name}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.first_name}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 text-right"><strong>{LANG.gender}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <!-- BEGIN: gender -->
                    <label><input type="radio" name="gender" value="{GENDER.index}"{GENDER.checked} >{GENDER.value}</label>&nbsp;&nbsp;&nbsp;
                    <!-- END: gender -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.birthday}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <div class="input-group">
                        <input class="form-control datepicker" type="text" name="birthday" value="{ROW.birthday}" autocomplete="off" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="birthday-btn">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.main_phone}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="main_phone" value="{ROW.main_phone}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.other_phone}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="other_phone" value="{ROW.other_phone}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.main_email}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="main_email" value="{ROW.main_email}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.other_email}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="other_email" value="{ROW.other_email}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.address}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="address" value="{ROW.address}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.knowledge}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <textarea class="form-control" style="height: 100px;" cols="75" rows="5" name="knowledge">{ROW.knowledge}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.jointime}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <div class="input-group">
                        <input class="form-control datepicker" type="text" name="jointime" value="{ROW.jointime}" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <em class="fa fa-calendar fa-fix"> </em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.image}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <div class="input-group">
                        <input class="form-control" type="text" name="image" value="{ROW.image}" id="id_image" />
                        <span class="input-group-btn">
                            <button class="btn btn-default selectfile" type="button">
                                <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.account}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.type_account}</strong><span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <input class="col-sm-19 col-md-20" type="radio" name="portion_selection" id="button_one" value="button_one" checked="checked" />
                    <label class="col-sm-19 col-md-4"><strong>{LANG.haveaccount}</strong></label>
                    <input class="col-sm-19 col-md-20" type="radio" name="portion_selection" value="infoaccount" />
                    <label class="col-sm-19 col-md-4"><strong>{LANG.createaccount}</strong></label>
                </div>
            </div>
            <div class="form-group" id="portion_one">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.user_account}</strong> <span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <select name="userid" id="userid" class="form-control">
                        <!-- BEGIN: user_info -->
                        <option value="{USER_INFO.userid}" selected="selected">{USER_INFO.fullname}</option>
                        <!-- END: user_info -->
                    </select>
                </div>
            </div>
            <div class="form-group" id="infoaccount" style="display: none">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.infoaccount}</strong><span class="red">(*)</span></label>
                <div class="col-sm-19 col-md-20">
                    <div class="row">
                        <div class="col-xs-8">
                            <input class="form-control" type="text" name="username" value="{ROW.username}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.username}" />
                        </div>
                        <div class="col-xs-8">
                            <input class="form-control" type="password" name="password" value="{ROW.password}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.password}" />
                        </div>
                        <div class="col-xs-8">
                            <input class="form-control" type="password" name="looppassword" value="{ROW.looppassword}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" placeholder="{LANG.looppassword}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.salary}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.salary_base}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="salary" value="{ROW.salary}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-4 control-label"><strong>{LANG.allowance}</strong></label>
                <div class="col-sm-19 col-md-20">
                    <input class="form-control" type="text" name="allowance" value="{ROW.allowance}" />
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-center button_fixed_bottom">
        <input type="hidden" name="submit" value="1" />
        <input type="hidden" name="ajax" value="{ROW.ajax}" />
        <input type="hidden" name="useridlink" value="{ROW.useridlink}" />
        <input class="btn btn-primary" type="submit" id="btn-submit" value="{LANG.save}" />
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        $("#userid").select2({
            language : "{NV_LANG_INTERFACE}",
            theme : "bootstrap",
            ajax : {
                url : '{URL_USERS}',
                dataType : 'json',
                delay : 250,
                data : function(params) {
                    return {
                        q : params.term, // search term
                        page : params.page
                    };
                },
                processResults : function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results : data,
                        pagination : {
                            more : (params.page * 30) < data.total_count
                        }
                    };
                },
                cache : true
            },
            escapeMarkup : function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength : 3,
            templateResult : formatRepo, // omitted for brevity, see the source of this page
            templateSelection : formatRepoSelection
        // omitted for brevity, see the source of this page
        });
        
        $(".datepicker").datepicker({
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            showOn : "focus",
            yearRange : "-90:+5",
        });
        
        $(".selectfile").click(function() {
            var area = "id_image";
            var path = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
            var currentpath = "{NV_UPLOADS_DIR}/{MODULE_UPLOAD}";
            var type = "image";
            nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
            return false;
        });
    });
    
    function formatRepo(repo) {
        if (repo.loading)
            return repo.text;
        var markup = '<div class="clearfix">' + '<div class="col-sm-19">' + repo.username + '</div>' + '<div clas="col-sm-5"><span class="show text-right">' + repo.fullname + '</span></div>' + '</div>';
        markup += '</div></div>';
        return markup;
    }

    function formatRepoSelection(repo) {
        $('#username').val(repo.username);
        return repo.username || repo.text;
    }

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
                    window.location.href = json.redirect;
                }
            }
        });
    })

    //]]>
</script>
<script type="text/javascript">
    $("button_one[value='1']:checked").val();
    $("input[name='portion_selection']:radio").change(function() {
        $("#portion_one").toggle($(this).val() == "button_one");
        
        $("#infoaccount").toggle($(this).val() == "infoaccount");
    });
</script>
<!-- END: main -->