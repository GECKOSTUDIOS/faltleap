<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1>
                <i class="bi bi-menu-button"></i>
                <?php echo isset($this->data->item) ? 'Edit Menu Item' : 'Create Menu Item'; ?>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo isset($this->data->item) ? '/admin/menuitems/update/' . $this->data->item->idmenuitems : '/admin/menuitems/store'; ?>">

                        <div class="mb-3">
                            <label for="menuitem" class="form-label">Menu Item Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                id="menuitem"
                                name="menuitem"
                                value="<?php echo isset($this->data->item) ? htmlspecialchars($this->data->item->menuitem ?? '') : ''; ?>"
                                required
                                placeholder="e.g., Dashboard, Settings, Reports">
                            <div class="form-text">The display name for this menu item</div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Link URL</label>
                            <input
                                type="text"
                                class="form-control"
                                id="link"
                                name="link"
                                value="<?php echo isset($this->data->item) ? htmlspecialchars($this->data->item->link ?? '') : ''; ?>"
                                placeholder="e.g., /dashboard, /settings, #">
                            <div class="form-text">The URL this menu item links to. Use # for dropdown parents.</div>
                        </div>

                        <div class="mb-3">
                            <label for="to_menuitems" class="form-label">Parent Menu Item</label>
                            <select class="form-select" id="to_menuitems" name="to_menuitems">
                                <option value="">None (Top Level)</option>
                                <?php if (isset($this->data->parentItems) && !empty($this->data->parentItems)): ?>
                                    <?php foreach ($this->data->parentItems as $parent): ?>
                                        <option
                                            value="<?php echo (int)$parent->idmenuitems; ?>"
                                            <?php echo (isset($this->data->item) && $this->data->item->to_menuitems == $parent->idmenuitems) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($parent->menuitem ?? ''); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="form-text">Select a parent to create a sub-menu item</div>
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    <i id="icon-preview" class="<?php echo isset($this->data->item) && !empty($this->data->item->icon) ? htmlspecialchars($this->data->item->icon) : 'bi-question-circle'; ?>"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="icon"
                                    name="icon"
                                    value="<?php echo isset($this->data->item) ? htmlspecialchars($this->data->item->icon ?? '') : ''; ?>"
                                    placeholder="e.g., bi-house, bi-gear, bi-graph-up">
                                <button type="button" class="btn btn-outline-secondary" id="pick-icon-btn">
                                    <i class="bi bi-palette"></i> Pick Icon
                                </button>
                            </div>
                            <div class="form-text">
                                Click "Pick Icon" to select from Bootstrap Icons.
                                <a href="https://icons.getbootstrap.com/" target="_blank">Browse all icons</a>
                            </div>

                            <!-- Icon Picker Modal -->
                            <div class="modal fade" id="iconPickerModal" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Pick an Icon</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" class="form-control mb-3" id="icon-search" placeholder="Search icons...">
                                            <div id="icon-grid" class="row g-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="orderid" class="form-label">Order</label>
                            <input
                                type="number"
                                class="form-control"
                                id="orderid"
                                name="orderid"
                                value="<?php echo isset($this->data->item) ? (int)($this->data->item->orderid ?? 0) : 0; ?>"
                                min="0"
                                style="max-width: 150px;">
                            <div class="form-text">Lower numbers appear first in the menu</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i>
                                <?php echo isset($this->data->item) ? 'Update' : 'Create'; ?>
                            </button>
                            <a href="/admin/menuitems" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Use <strong>Order</strong> to control menu item sequence</li>
                        <li>Set <strong>Link</strong> to <code>#</code> for dropdown parent items</li>
                        <li>Choose Bootstrap Icons for consistent styling</li>
                        <li>Create hierarchical menus by selecting a parent item</li>
                        <li>Top-level items appear in the main navigation bar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-item {
    cursor: pointer;
    padding: 15px;
    text-align: center;
    border: 2px solid transparent;
    border-radius: 8px;
    transition: all 0.2s;
}
.icon-item:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}
.icon-item.selected {
    background-color: #e7f1ff;
    border-color: #0d6efd;
}
.icon-item i {
    font-size: 24px;
    display: block;
    margin-bottom: 5px;
}
.icon-item small {
    display: block;
    font-size: 10px;
    color: #6c757d;
    word-break: break-all;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Popular Bootstrap Icons (curated list of commonly used icons)
    const popularIcons = [
        'bi-house', 'bi-house-door', 'bi-speedometer2', 'bi-grid', 'bi-grid-3x3',
        'bi-graph-up', 'bi-graph-down', 'bi-bar-chart', 'bi-pie-chart',
        'bi-person', 'bi-people', 'bi-person-circle', 'bi-person-badge',
        'bi-gear', 'bi-sliders', 'bi-wrench', 'bi-tools',
        'bi-bell', 'bi-envelope', 'bi-inbox', 'bi-mailbox',
        'bi-calendar', 'bi-calendar-event', 'bi-clock', 'bi-stopwatch',
        'bi-file-text', 'bi-file-earmark', 'bi-folder', 'bi-folder-open',
        'bi-box', 'bi-archive', 'bi-collection',
        'bi-cart', 'bi-bag', 'bi-basket', 'bi-credit-card', 'bi-wallet',
        'bi-star', 'bi-heart', 'bi-bookmark', 'bi-flag',
        'bi-search', 'bi-filter', 'bi-funnel',
        'bi-plus-circle', 'bi-dash-circle', 'bi-x-circle', 'bi-check-circle',
        'bi-info-circle', 'bi-question-circle', 'bi-exclamation-circle',
        'bi-pencil', 'bi-trash', 'bi-download', 'bi-upload', 'bi-save',
        'bi-printer', 'bi-camera', 'bi-image',
        'bi-lock', 'bi-unlock', 'bi-shield', 'bi-key',
        'bi-lightning', 'bi-cloud', 'bi-sun', 'bi-moon',
        'bi-wifi', 'bi-bluetooth', 'bi-cpu', 'bi-hdd',
        'bi-map', 'bi-pin-map', 'bi-geo-alt',
        'bi-chat', 'bi-chat-dots', 'bi-telephone',
        'bi-toggle-on', 'bi-toggle-off', 'bi-power',
        'bi-arrow-right', 'bi-arrow-left', 'bi-arrow-up', 'bi-arrow-down',
        'bi-chevron-right', 'bi-chevron-left', 'bi-chevron-up', 'bi-chevron-down',
        'bi-list', 'bi-menu-button', 'bi-menu-button-wide',
        'bi-table', 'bi-kanban', 'bi-diagram-3',
        'bi-eye', 'bi-eye-slash', 'bi-link', 'bi-paperclip',
        'bi-tag', 'bi-tags', 'bi-bookmark-star',
        'bi-trophy', 'bi-award', 'bi-gift',
        'bi-thermometer', 'bi-droplet', 'bi-brightness-high',
        'bi-server', 'bi-database', 'bi-router'
    ];

    let allIcons = [...popularIcons];

    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const iconGrid = document.getElementById('icon-grid');
    const iconSearch = document.getElementById('icon-search');
    const pickIconBtn = document.getElementById('pick-icon-btn');
    const iconPickerModalEl = document.getElementById('iconPickerModal');

    let selectedIcon = iconInput ? (iconInput.value || '') : '';
    let iconPickerModal = null;

    // Initialize Bootstrap modal
    if (iconPickerModalEl && typeof bootstrap !== 'undefined') {
        iconPickerModal = new bootstrap.Modal(iconPickerModalEl);
    }

    // Render icons
    function renderIcons(icons) {
        if (!iconGrid) return;

        iconGrid.innerHTML = '';
        icons.forEach(icon => {
            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-lg-2';

            const iconItem = document.createElement('div');
            iconItem.className = 'icon-item';
            if (icon === selectedIcon) {
                iconItem.classList.add('selected');
            }

            iconItem.innerHTML = `
                <i class="${icon}"></i>
                <small>${icon.replace('bi-', '')}</small>
            `;

            iconItem.addEventListener('click', () => {
                selectedIcon = icon;
                if (iconInput) iconInput.value = icon;
                if (iconPreview) iconPreview.className = icon;

                // Update selection styling
                document.querySelectorAll('.icon-item').forEach(item => {
                    item.classList.remove('selected');
                });
                iconItem.classList.add('selected');

                // Close modal
                if (iconPickerModal) {
                    iconPickerModal.hide();
                }
            });

            col.appendChild(iconItem);
            iconGrid.appendChild(col);
        });
    }

    // Search functionality
    if (iconSearch) {
        iconSearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = allIcons.filter(icon =>
                icon.toLowerCase().includes(searchTerm)
            );
            renderIcons(filtered);
        });
    }

    // Open picker
    if (pickIconBtn) {
        pickIconBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            renderIcons(allIcons);
            if (iconPickerModal) {
                iconPickerModal.show();
            }
        });
    }

    // Live icon preview (for manual input)
    if (iconInput) {
        iconInput.addEventListener('input', function(e) {
            if (iconPreview) {
                iconPreview.className = e.target.value || 'bi-question-circle';
            }
            selectedIcon = e.target.value;
        });
    }
});
</script>
