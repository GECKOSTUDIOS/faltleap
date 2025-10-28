<a href="/manage/edit" class="btn btn-primary">New Entry</a>
<table class="table table-striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>URL</th>
			<th>IP</th>
			<th>Port</th>
			<th>Type</th>
			<th>ACME Status (SSL)</th>
			<th>Owner</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach ($this->data as $r) { ?>
			<tr>
				<td><?php echo $r->idreverseproxies; ?></td>
				<td><?php echo $r->server_name; ?></td>
				<td><?php echo $r->target_address; ?></td>
				<td><?php echo $r->target_port; ?></td>
				<td><?php echo ($r->is_websocket ? 'Websocket' : 'HTTP'); ?></td>
				<td><?php echo ($r->generate_ssl ? '<i class="bi bi-check-circle-fill"></i> Valid until ' . $r->acme_valid_until : '<i class="bi bi-x-circle-fill"></i> Invalid'); ?></td>
				<td><?php echo $r->username; ?></td>
				<td>
					<a class="btn btn-sm btn-success" href="/manage/deploy/<?php echo $r->idreverseproxies; ?>">Deploy</a> |
					<a class="btn btn-sm btn-primary" href="/manage/edit/<?php echo $r->idreverseproxies; ?>">Edit</a> |
					<a href="#"
						class="btn btn-sm btn-danger"
						onclick="if(confirm('Really delete?')) { window.location='/manage/delete/<?php echo $r->idreverseproxies; ?>'; } return false;">
						Delete
					</a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
