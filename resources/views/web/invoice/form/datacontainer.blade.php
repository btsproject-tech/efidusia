@if (!empty($data_container))
    @foreach ($data_container as $item)
        <tr>
            <td>{{ $item['container_number'] }}</td>
            <td>{{ $containerSize }}</td>
            <td>{{ $item['marks_and_number'] }}</td>
            <td>{{ $item['gross_weight'] }}</td>
            <td>{{ $item['measurement'] }}</td>
            <td>{{ $item['description_good'] }}</td>
        </tr>   
    @endforeach
@else
@endif