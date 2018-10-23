<!-- BEGIN: main -->
<!-- BEGIN: view -->
<form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col width="50"/>
                <col />
                <col />
                <col width="50" />
                <col width="180" />
            </colgroup>
            <thead>
                <tr>
                    <th>{LANG.weight}</th>
                    <th>{LANG.part}</th>
                    <th>Email</th>
                    <th class="text-center">{LANG.active}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td class="text-center" colspan="5">{NV_GENERATE_PAGE}</td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                    <select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_change_weight('{VIEW.id}');">
                        <!-- BEGIN: weight_loop -->
                        <option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: weight_loop -->
                    </select></td>
                    <td> <a href="{VIEW.link_view}" title="{VIEW.title}">{VIEW.title}</a> <span class="red">({VIEW.numsub})</span> </td>
                    <td> {VIEW.email} </td>
                    <td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" /></td>
                    <td class="text-center"><i class="fa fa-edit fa-lg">&nbsp;</i><a href="{VIEW.link_edit}#edit">{LANG.edit}</a> - <em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a></td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: view -->

<h3>{LANG.part_add}</h3>

<!-- BEGIN: error -->
<div class="alert alert-warning">
    {ERROR}
</div>
<!-- END: error -->
<div class="panel panel-default">
    <div class="panel-body">
        <form class="form-horizontal" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="id" value="{ROW.id}" />
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>{LANG.title}</strong> <span class="red">*</span></label>
                <div class="col-sm-21">
                    <input class="form-control" type="text" name="title" value="{ROW.title}" required="required" oninvalid="setCustomValidity( nv_required )" oninput="setCustomValidity('')" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 col-md-3 control-label"><strong>{LANG.alias}</strong></label>
                <div class="col-sm-14 col-md-21">
                    <div class="input-group">
                        <input class="form-control" type="text" name="alias" value="{ROW.alias}" id="id_alias" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <i class="fa fa-refresh fa-lg" onclick="nv_get_alias('id_alias');">&nbsp;</i>
                            </button> </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>{LANG.part_parent}</strong> </label>
                <div class="col-sm-21">
                    <select class="form-control" name="parentid">
                        <!-- BEGIN: parent_loop -->
                        <option value="{pid}" {pselect}>{ptitle}</option>
                        <!-- END: parent_loop -->
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>{LANG.office}</strong></label>
                <div class="col-sm-21">
                    <input class="form-control" type="text" name="office" value="{ROW.office}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>{LANG.address}</strong></label>
                <div class="col-sm-21">
                    <input class="form-control" type="text" name="address" value="{ROW.address}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>{LANG.phone}</strong></label>
                <div class="col-sm-21">
                    <input class="form-control" type="text" name="phone" value="{ROW.phone}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>Fax</strong></label>
                <div class="col-sm-21">
                    <input class="form-control" type="text" name="fax" value="{ROW.fax}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>Email</strong></label>
                <div class="col-sm-21">
                    <input class="form-control" type="email" name="email" value="{ROW.email}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>Website</strong></label>
                <div class="col-sm-21">
                    <input class="form-control" type="url" name="website" value="{ROW.website}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label"><strong>{LANG.description}</strong></label>
                <div class="col-sm-21">
                    {ROW.note}
                </div>
            </div>
            <div class="form-group" style="text-align: center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    //<![CDATA[
    function nv_change_weight(id) {
        var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
        var new_vid = $('#id_weight_' + id).val();
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=part&nocache=' + new Date().getTime(), 'ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            clearTimeout(nv_timer);
            window.location.href = window.location.href;
            return;
        });
        return;
    }

    function nv_change_status(id) {
        var new_status = $('#change_status_' + id).is(':checked') ? true : false;
        if (confirm(nv_is_change_act_confirm[0])) {
            var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=part&nocache=' + new Date().getTime(), 'change_status=1&id=' + id, function(res) {
                var r_split = res.split('_');
                if (r_split[0] != 'OK') {
                    alert(nv_is_change_act_confirm[2]);
                }
            });
        }
        else{
            $('#change_status_' + id).prop('checked', new_status ? false : true );
        }
        return;
    }

    function nv_get_alias(id) {
        var title = strip_tags($("[name='title']").val());
        if (title != '') {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=part&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {
                $("#" + id).val(strip_tags(res));
            });
        }
        return false;
    }
    //]]>
</script>

<!-- BEGIN: auto_get_alias -->
<script type="text/javascript">
    //<![CDATA[
    $("[name='title']").change(function() {
        nv_get_alias('id_alias');
    });
    //]]>
</script>
<!-- END: auto_get_alias -->

<!-- END: main -->