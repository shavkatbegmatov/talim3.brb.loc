<div class="card">
    <div class="card-header">
        <h3 class="card-title">Таблица филиалов</h3>
    </div>
    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Поиск:
                <div class="ms-2 d-inline-block">
                    <input type="text" id="search-input" class="form-control form-control-sm" aria-label="Search branches" placeholder="Введите для поиска...">
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1">ID</th>
                    <th>Код2</th>
                    <th>Название</th>
                    <th style="max-width: 300px;">Адрес</th>
                    <th>Номер телефона</th>
                    <th>Кол-во заявок</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="branch-table">
                <?php foreach ($branches as $branch): ?>
                    <tr>
                        <td><?php echo $branch['id']; ?></td>
                        <td><?php echo $branch['code']; ?></td>
                        <td><?php echo $branch['name']; ?></td>
                        <td style="max-width: 300px; text-wrap: wrap;"><?php echo $branch['address']; ?></td>
                        <td>
                            <?php 
                                $phone = preg_replace("/[^0-9]/", "", $branch['phone']);
                                echo "+998 (" . substr($phone, 0, 2) . ") " . substr($phone, 2, 3) . "-" . substr($phone, 5, 2) . "-" . substr($phone, 7, 2);
                            ?>
                        </td>
                        <td><?php echo R::count('applications', 'bank_branch_code = ?', [$branch['code']]); ?></td>
                        <td class="text-end">
                            <div class="btn-list d-flex flex-nowrap">
                                <a class="btn btn-icon btn-outline-warning" href="/branch/change/<?php echo $branch['id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                        <path d="M13.5 6.5l4 4" />
                                    </svg>
                                </a>
                                <a class="btn btn-icon btn-outline-danger" href="/branch/delete/<?php echo $branch['id']; ?>" onclick="return confirm('Вы точно хотите удалить филиал <?php echo $branch['name']; ?>?');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('search-input').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#branch-table tr');
        
        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = '';
                let cells = row.querySelectorAll('td');
                cells.forEach(function(cell) {
                    cell.innerHTML = cell.textContent.replace(new RegExp(filter, "gi"), match => `<b style="font-weight: 700;">${match}</b>`);
                });
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
