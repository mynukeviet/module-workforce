<!-- BEGIN: main -->
<h1 class="title text-center">{LANG.salary_history}</h1>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="w50 text-center">{LANG.time}</th>
                <th>{LANG.salary}</th>
                <th>{LANG.allowance}</th>
                <th>{LANG.workday}</th>
                <th>{LANG.overtime}</th>
                <th>{LANG.advance}</th>
                <th>{LANG.bonus}</th>
                <th>{LANG.total}</th>
                <th>{LANG.deduction}</th>
                <th>{LANG.received}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">{VIEW.time}</td>
                <td>{VIEW.salary}</td>
                <td>{VIEW.allowance}</td>
                <td>{VIEW.workday}</td>
                <td>{VIEW.overtime}</td>
                <td>{VIEW.advance}</td>
                <td>{VIEW.bonus}</td>
                <td>{VIEW.total}</td>
                <td>{VIEW.deduction}</td>
                <td>{VIEW.received}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: main -->