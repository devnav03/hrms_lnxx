<table>
    <thead>
        <tr>
            <th>Message ID</th>
            <th>Employee Name</th>
            <th>Employee Code</th>
            <th>Status</th>
            <th>Mobile NO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $res)
            <tr>
                <td>HRMSNOTI0{{ $res->notification_id }}</td>
                <td>{{ $res->name }}</td>
                <td>{{ $res->employee_code }}</td>
                @if($res->status == 1)
                <td>Success</td>
                @else
                <td>Failed</td>
                @endif
                <td>{{ $res->mobile }}</td>
            </tr>       
        @endforeach
    </tbody>
</table>