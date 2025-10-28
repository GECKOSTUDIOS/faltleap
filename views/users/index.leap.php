<a href="/users/edit" class="btn btn-primary">New Entry</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($this->data as $r) { ?>
            <tr>
                <td><?php echo $r->idusers; ?></td>
                <td><?php echo $r->username; ?></td>
                <td><?php echo $r->email; ?></td>
                <td><a class="btn btn-sm btn-primary" href="/users/edit/<?php echo $r->idusers; ?>">Edit</a> |
                    <a href="#"
                        class="btn btn-sm btn-danger"
                        onclick="if(confirm('Really delete?')) { window.location='/users/delete/<?php echo $r->idusers; ?>'; } return false;">
                        Delete
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>