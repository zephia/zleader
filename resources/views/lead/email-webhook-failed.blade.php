{{ $lead['form_name'] }}
<table>
@foreach($lead['lead_values'] as $field)
    <tr>
        <td>{{ $field['name'] }}</td>
        <td>{{ $field['values'][0] }}</td>
    </tr>
@endforeach
</table>