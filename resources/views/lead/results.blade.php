<script type="text/template" data-grid="standard" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-lead-id="<%= r.id %>">
			<td><%= r.date %></td>
            @foreach($columnables as $field)
			<td><%= r.{{ $field->key }} %></td>
            @endforeach
            <td><%= r.utm_source %></td>
            <td><%= r.utm_medium %></td>
		</tr>

	<% }); %>

</script>