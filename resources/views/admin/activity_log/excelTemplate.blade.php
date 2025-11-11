<table>
    <thead>
    <tr>
        <th>Description</th>
        <th>User name</th>
        <th>User type</th>
        <th>Logged on</th>
        <th>Details</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logHistory as $log)
        <tr>
            <td>{{ $log['description']}}</td>
            <td>{{ $log['user_name']}}</td>
            <td>{{ $log['user_type']}}</td>            
            <td>{{ $log['created_at']}}</td>
            <td>
                @foreach($log['properties'] as $key=>$property)
                    {{$key. " : ".$property}} <br>
                @endforeach
            </td>
            
            
        </tr>
    @endforeach
    </tbody>
</table>
<style>
    br {mso-data-placement:same-cell;}
</style>