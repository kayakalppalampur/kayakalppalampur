<style type="text/css">
    table td, table th{
        border:1px solid black;
    }
</style>
<div class="container">


    <br/>


    <table>
        <tr>
            <th>Name</th>
            <th>Profile ID</th>
            <th>Email</th>
        </tr>
        {{--@foreach ($items as $key => $item)--}}
            {{--<tr>--}}
                {{--<td>{{ ++$key }}</td>--}}
                {{--<td>{{ $item->title }}</td>--}}
                {{--<td>{{ $item->description }}</td>--}}
            {{--</tr>--}}
        {{--@endforeach--}}
    </table>
</div>
