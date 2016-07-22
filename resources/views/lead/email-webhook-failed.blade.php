{{ $lead['form_name'] }}
<table>
@foreach($lead['lead_values'] as $field)
    <tr>
        <td>{{ $field['name'] }}</td>
        <td>{{ $field['value'] }}</td>
    </tr>
@endforeach
</table>