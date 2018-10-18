<!-- BEGIN: main -->
<form action="" method="post" class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_general}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_admin}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groups -->
                    <label class="show"><input type="checkbox" name="groups_admin[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groups -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_groups_use}</label>
                <div class="col-sm-20" style="height: 200px; padding: 10px; border: solid 1px #ddd; overflow: scroll;">
                    <!-- BEGIN: groups_use -->
                    <label class="show"><input type="checkbox" name="groups_use[]" value="{GROUPS.value}" {GROUPS.checked} />{GROUPS.title}</label>
                    <!-- END: groups_use -->
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config_salary}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_overtime}</label>
                <div class="col-sm-20">
                    <input class="form-control" name="overtime" value="{DATA.overtime}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_workdays}</label>
                <div class="col-sm-20">
                    <input class="form-control" name="workdays" value="{DATA.workdays}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_insurrance}</label>
                <div class="col-sm-20">
                    <input class="form-control" name="insurrance" value="{DATA.insurrance}" />
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
    </div>
</form>
<!-- END: main -->