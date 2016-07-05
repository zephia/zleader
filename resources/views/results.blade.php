<script type="text/template" data-grid="standard" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><%= r.id %></td>
			<td><%= r.name %></td>
			<td><%= r.email %></td>
		</tr>

	<% }); %>

</script>