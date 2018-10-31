<!-- BEGIN: main -->
<ul class="list-inline pull-right">
<!-- BEGIN: salary -->
    <li><a href="{URL_APPROVAL}" class="btn btn-primary btn-xs"><em class="fa fa-bar-chart">&nbsp;</em>{LANG.approval}</a></li>
<!-- END: salary -->
    <li><a href="{URL_EDIT}" class="btn btn-default btn-xs"><em class="fa fa-edit">&nbsp;</em>{LANG.workforce_edit}</a></li>
    <li><a href="{URL_DELETE}" class="btn btn-danger btn-xs" onclick="return confirm(nv_is_del_confirm[0]);"><em class="fa fa-trash-o">&nbsp;</em>{LANG.delete}</a></li>
</ul>
<div class="clearfix"></div>
<div class="panel panel-default">
    <div class="panel-heading">{LANG.info}</div>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th width="220">{LANG.fullname}</th>
                <td>{WORKFORCE.fullname}</td>
                <th>{LANG.part}</th>
                <td>{WORKFORCE.part}</td>
            </tr>
            <tr>
                <th>{LANG.position}</th>
                <td>{WORKFORCE.position}</td>
                <th>{LANG.birthday}</th>
                <td>{WORKFORCE.birthday}</td>
            </tr>
            <tr>
                <th>{LANG.gender}</th>
                <td>{WORKFORCE.gender}</td>
                <th>{LANG.address}</th>
                <td>{WORKFORCE.address}</td>
            </tr>
            <tr>
                <th>{LANG.main_phone}</th>
                <td>{WORKFORCE.main_phone}</td>
                <th>{LANG.other_phone}</th>
                <td>{WORKFORCE.other_phone}</td>
            </tr>
            <tr>
                <th>{LANG.main_email}</th>
                <td>{WORKFORCE.main_email}</td>
                <th>{LANG.other_email}</th>
                <td>{WORKFORCE.other_email}</td>
            </tr>
            <tr>
                <th>{LANG.jointime}</th>
                <td>{WORKFORCE.jointime}</td>
                <th>{LANG.addtime}</th>
                <td>{WORKFORCE.addtime}</td>
            </tr>
            <tr>
                <th>{LANG.edittime}</th>
                <td>{WORKFORCE.edittime}</td>
                <th>{LANG.status}</th>
                <td>
                    <select class="form-control" style="width: 200px" id="change_status_{WORKFORCE.id}" onchange="nv_chang_status('{WORKFORCE.id}');">
                        <!-- BEGIN: status -->
                        <option value="{STATUS.data}"{STATUS.selected}>{STATUS.value}</option>
                        <!-- END: status -->
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<!-- BEGIN: approval -->
<div class="panel panel-default">
    <div class="panel-heading">{LANG.hisapproval}</div>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th>{LANG.addtime}</th>
                <th width="220">{LANG.salary}</th>
                <th>{LANG.allowance}</th>
            </tr>
            <!-- BEGIN: loop -->
            <tr>
                <td>{APPROVAL.addtime}</td>
                <td>{APPROVAL.salary}</td>
                <td>{APPROVAL.allowance}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: approval -->
<!-- END: main -->