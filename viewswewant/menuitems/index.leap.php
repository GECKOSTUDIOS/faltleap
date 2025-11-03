<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="bi bi-menu-button"></i> Menu Management</h1>
        </div>
        <div class="col-auto">
            <a href="/admin/menuitems/create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Menu Item
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($this->data->menuTree)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No menu items found. Click "Add Menu Item" to create one.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Menu Item</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Order</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data->menuTree as $item): ?>
                                <?php echo renderMenuItem($item, 0); ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
/**
 * Recursively render menu items with indentation
 */
function renderMenuItem($item, $level)
{
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    $prefix = $level > 0 ? '<i class="bi bi-arrow-return-right text-muted"></i> ' : '';

    $html = '<tr>';
    $html .= '<td>' . $indent . $prefix . htmlspecialchars($item->menuitem) . '</td>';
    $html .= '<td><code>' . htmlspecialchars($item->link ?? '') . '</code></td>';
    $html .= '<td>';
    if ($item->icon) {
        $html .= '<i class="' . htmlspecialchars($item->icon) . '"></i> ';
        $html .= '<small class="text-muted">' . htmlspecialchars($item->icon) . '</small>';
    }
    $html .= '</td>';
    $html .= '<td><span class="badge bg-secondary">' . $item->orderid . '</span></td>';
    $html .= '<td class="text-end">';
    $html .= '<div class="btn-group btn-group-sm">';
    $html .= '<a href="/admin/menuitems/edit/' . $item->idmenuitems . '" class="btn btn-outline-primary" title="Edit">';
    $html .= '<i class="bi bi-pencil"></i>';
    $html .= '</a>';
    $html .= '<a href="/admin/menuitems/delete/' . $item->idmenuitems . '" class="btn btn-outline-danger" title="Delete" onclick="return confirm(\'Are you sure you want to delete this menu item?\');">';
    $html .= '<i class="bi bi-trash"></i>';
    $html .= '</a>';
    $html .= '</div>';
    $html .= '</td>';
    $html .= '</tr>';

    // Render children recursively
    if (!empty($item->children)) {
        foreach ($item->children as $child) {
            $html .= renderMenuItem($child, $level + 1);
        }
    }

    return $html;
}
            ?>
