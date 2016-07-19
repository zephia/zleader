<script type="text/template" data-grid="standard" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><%= r.date %></td>
            @foreach($columnables as $field)
			<td><%= r.{{ $field->key }} %></td>
            @endforeach
            <td><%= r.utm_source %></td>
            <td><%= r.utm_medium %></td>
            <td><%= r.utm_campaign %></td>
		</tr>

	<% }); %>

</script>