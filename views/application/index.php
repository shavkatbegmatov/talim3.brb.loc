<?php
$branches = [];
foreach ($applications as $application) {
    $branch_code = $application['bank_branch_code'];
    $branch_name = $application['bank_branch_name'];
    $branches[$branch_code] = $branch_name;
}

$statuses = [
    '0' => 'В ожидании',
    '1' => 'Принято',
    '2' => 'Отказано',
    '3' => 'Кредит выдан',
    '4' => 'Отменена',
];
?>

<div class="card">
	<div class="card-header">
		<h3 class="card-title">Таблица заявок <span class="badge">Общее количество заявок: <span
					id="all-count">0</span></span> | <span class="badge bg-orange-lt">В ожидании: <span
					id="pending-count">0</span></span> | <span class="badge bg-green-lt">Принято: <span
					id="accepted-count">0</span></span> | <span class="badge bg-red-lt">Отказано: <span
					id="rejected-count">0</span></span> | <span class="badge bg-green-lt">Кредит выдан: <span
					id="issued-count">0</span></span> | <span class="badge bg-red-lt">Отменена: <span
					id="cancelled-count">0</span></span></h3>
	</div>
	<div class="card-body border-bottom py-3">
		<div class="d-flex">
			<div class="text-secondary">
				Поиск:
				<div class="ms-2 d-inline-block">
					<input type="text" id="search-input" class="form-control form-control-sm"
						aria-label="Search applications" placeholder="Введите для поиска...">
				</div>
			</div>
			<div class="ms-2 d-inline-block">
				<select id="branch-select" class="form-select form-select-sm">
					<option value="">Все филиалы</option>
					<?php foreach ($branches as $code => $name): ?>
					<option value="<?php echo htmlspecialchars($code); ?>"><?php echo htmlspecialchars($name); ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="ms-2 d-inline-block">
				<select id="status-select" class="form-select form-select-sm">
					<option value="">Все статусы</option>
					<?php foreach ($statuses as $statusCode => $statusName): ?>
					<option value="<?php echo htmlspecialchars($statusCode); ?>">
						<?php echo htmlspecialchars($statusName); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<?php if (user()['type'] == '1'): ?>
			<div class="ms-auto">
				<div class="ms-2 d-inline-block">
					<?php if (DEV_MODE === false): ?>
					<a class="btn btn-outline-blue btn-sm" href="/application/refresh">Обновить таблицу заявок</a>
					<a class="btn btn-outline-blue btn-sm" href="/application/set_status_4_test">TEST Status 4 1</a>
					<a class="btn btn-outline-blue btn-sm" href="/application/set_status_4_test_2">TEST Status 4 2</a>
					<a class="btn btn-outline-blue btn-sm" href="/application/set_status_3">TEST Status 3</a>
					<?php else: ?>
					<a class="btn btn-outline-blue btn-sm disabled" href="/application/refresh" disabled>Обновить таблицу заявок</a>
					<?php endif; ?>
					<a class="btn btn-outline-green btn-sm" href="/application/excel">Скачать в Excel</a>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<!-- Pagination controls moved here -->
		<div class="d-flex mt-3">
			<div class="d-flex align-items-center">
				<span style="width: 275px;">Показать записей:</span>
				<select id="rows-per-page" class="form-select form-select-sm ms-2">
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100" selected>100</option>
					<option value="500">500</option>
					<option value="999999">Все</option>
				</select>
			</div>
			<p class="m-0 text-muted ms-3" id="pagination-info"></p>
			<ul class="pagination m-0 ms-auto" id="pagination-controls">
				<!-- Pagination buttons will be populated by JavaScript -->
			</ul>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-hover card-table table-vcenter text-nowrap datatable">
			<thead>
				<tr>
					<th onclick="sortTable(0);" class="w-1"># <span class="sort-arrow" id="arrow-0"></span></th>
					<th onclick="sortTable(1);" class="w-1">ID <span class="sort-arrow" id="arrow-1"></span></th>
					<th onclick="sortTable(2);">Фамилия / Имя / Отчество <span class="sort-arrow" id="arrow-2"></span>
					</th>
					<th onclick="sortTable(3);">PINFL <span class="sort-arrow" id="arrow-3"></span></th>
					<th onclick="sortTable(4);">Сумма кредита <span class="sort-arrow" id="arrow-4"></span></th>
					<th onclick="sortTable(5);">Номер телефона <span class="sort-arrow" id="arrow-5"></span></th>
					<?php if (user()['type'] == '1'): ?>
					<th onclick="sortTable(6);">Код БХО <span class="sort-arrow" id="arrow-6"></span></th>
					<?php endif; ?>
					<th onclick="sortTable(7);">Создано <span class="sort-arrow" id="arrow-7"></span></th>
					<th onclick="sortTable(8);">Статус <span class="sort-arrow" id="arrow-8"></span></th>
				</tr>
			</thead>
			<tbody id="application-table">
				<!-- Table rows will be populated by JavaScript -->
			</tbody>
		</table>
	</div>
</div>

<!-- Modals -->
<style>
#applicationModalContent table {
	margin: 20px;
}

#applicationModalContent th {
	font-weight: 800;
	vertical-align: middle;
}

#applicationModalContent td {
	vertical-align: middle;
}
</style>

<div class="modal modal-blur fade" id="applicationModal" tabindex="-1">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Информация о заявке <span class="text-secondary">(#<span
							id="applicationId">0</span>)</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body container" id="applicationModalContent" style="padding-top: 20px;"></div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade" id="applicationHistoryModal" tabindex="-1">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">История изменения статуса заявки <span class="text-secondary">(#<span
							id="applicationIdHistory">0</span>)</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="applicationHistoryModalContent" style="padding-top: 20px;"></div>
		</div>
	</div>
</div>

<script>
const data = [
	<?php foreach ($applications as $application): ?>
	<?php echo json_encode($application, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG); ?>,
	<?php endforeach; ?>
];

const statusInfo = {
	'0': {
		name: 'В ожидании',
		color: 'bg-orange-lt'
	},
	'1': {
		name: 'Принято',
		color: 'bg-green-lt'
	},
	'2': {
		name: 'Отказано',
		color: 'bg-red-lt'
	},
	'3': {
		name: 'Кредит выдан',
		color: 'bg-blue-lt'
	},
	'4': {
		name: 'Отменена',
		color: 'bg-gray-lt'
	}
};

const userType = <?php echo json_encode(user()['type']); ?>;

let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById('rows-per-page').value);
let totalPages = Math.ceil(data.length / rowsPerPage);
let sortColumn = null;
let sortDirection = 'asc';

function getStatusId(statusCode) {
	const mapping = {
		'0': 'pending',
		'1': 'accepted',
		'2': 'rejected',
		'3': 'issued',
		'4': 'cancelled'
	};
	return mapping[statusCode] || 'unknown';
}

function updateStatusCounts() {
	const counts = {
		'0': 0,
		'1': 0,
		'2': 0,
		'3': 0,
		'4': 0
	};

	const filteredData = getFilteredData();
	filteredData.forEach(function(application) {
		const status = application.bxo_status;
		if (counts.hasOwnProperty(status)) {
			counts[status]++;
		}
	});

	for (const [statusCode, info] of Object.entries(statusInfo)) {
		const countElement = document.getElementById(`${getStatusId(statusCode)}-count`);
		if (countElement) {
			countElement.textContent = counts[statusCode];
		}
	}

	const allCountElement = document.getElementById('all-count');
	if (allCountElement) {
		allCountElement.textContent = filteredData.length;
	}
}

function filterTable() {
	currentPage = 1;
	renderTable();
}

function getFilteredData() {
	let filterText = document.getElementById('search-input').value.toLowerCase();
	let branchFilter = document.getElementById('branch-select').value;
	let statusFilter = document.getElementById('status-select').value;

	return data.filter(function(application) {
		let show = true;

		if (filterText !== '') {
			let text = JSON.stringify(application).toLowerCase();
			if (!text.includes(filterText)) {
				show = false;
			}
		}

		if (branchFilter !== '') {
			if (application.bank_branch_code !== branchFilter) {
				show = false;
			}
		}

		if (statusFilter !== '') {
			if (application.bxo_status !== statusFilter) {
				show = false;
			}
		}

		return show;
	});
}

function renderTable() {
	const tableBody = document.getElementById('application-table');
	tableBody.innerHTML = '';

	let filteredData = getFilteredData();

	rowsPerPage = parseInt(document.getElementById('rows-per-page').value);

	if (sortColumn !== null) {
		filteredData.sort(function(a, b) {
			let aValue = getColumnValue(a, sortColumn);
			let bValue = getColumnValue(b, sortColumn);

			if (sortDirection === 'asc') {
				return aValue > bValue ? 1 : -1;
			} else {
				return aValue < bValue ? 1 : -1;
			}
		});
	}

	totalPages = Math.ceil(filteredData.length / rowsPerPage);

	const start = (currentPage - 1) * rowsPerPage;
	const end = start + rowsPerPage;
	const pageData = filteredData.slice(start, end);

	pageData.forEach(function(application, index) {
		const row = document.createElement('tr');

		let created_at = new Date(application.mf_created_at);
		let now = new Date();
		let interval = Math.floor((now - created_at) / (1000 * 60 * 60 * 24));

		row.setAttribute('data-order', data.indexOf(application));
		row.setAttribute('data-branch-code', application.bank_branch_code);
		row.setAttribute('data-bxo-status', application.bxo_status);

		row.innerHTML = `
            <td>${start + index + 1}</td>
            <td>${application.student_mf_id}</td>
            <td>${application.lastname} ${application.firstname} ${application.fathername}</td>
            <td>${application.pinfl}</td>
            <td style="text-align: end;">${numberFormat(application.credit_sum)} сум</td>
            <td>${formatPhoneNumber(application.phone_number)}</td>
            ${<?php echo user()['type'] == '1' ? 'true' : 'false'; ?> ? `<td>${application.bank_branch_code}| ${application.bank_branch_name}</td>` : ''}
            <td>
                <span class="badge history-badge ${getIntervalBadgeClass(application.bxo_status, interval)}">
                    ${interval} дней назад
                </span>
            </td>
            <td>${getStatusBadge(application.bxo_status, application)}</td>
        `;

		row.addEventListener('click', onRowClick);

		// Add event listener to the badge to prevent event bubbling
		const badge = row.querySelector('.history-badge');
		if (badge) {
			badge.addEventListener('click', function(event) {
				event.stopPropagation(); // Prevent the event from bubbling up to the row
				showHistoryModal(data.indexOf(application));
			});
		}

		tableBody.appendChild(row);
	});

	updatePaginationControls();
	updateStatusCounts();
}

function showHistoryModal(index) {
	const application = data[index];

	document.getElementById('applicationIdHistory').textContent = application.student_mf_id;

	const status_names = {
		'0': 'В ожидании',
		'1': 'Принято',
		'2': 'Отказано',
		'3': 'Кредит выдан',
		'4': 'Отменена'
	};

	const modalContent = `
        <div class="datagrid text-xs">
            <div class="datagrid-item">
                <div class="datagrid-title">${status_names[0]}</div>
                <div class="datagrid-content">${application.created_at}</div>
            </div>
            ${application.status_history.map(function(status) {
                return `
                    <div class="datagrid-item">
                        <div class="datagrid-title">${status_names[status.status]}</div>
                        <div class="datagrid-content">${status.created_at}</div>
                    </div>
                `;
            }).join('')}
        </div>
    `;

	document.getElementById('applicationHistoryModalContent').innerHTML = modalContent;

	const historyModal = new bootstrap.Modal(document.getElementById('applicationHistoryModal'));
	historyModal.show();
}

function getColumnValue(application, columnIndex) {
	switch (columnIndex) {
		case 0:
			return application.id;
		case 1:
			return application.student_mf_id;
		case 2:
			return `${application.lastname} ${application.firstname} ${application.fathername}`;
		case 3:
			return application.pinfl;
		case 4:
			return parseFloat(application.credit_sum);
		case 5:
			return application.phone_number;
		case 6:
			return application.bank_branch_code;
		case 7:
			return application.mf_created_at;
		case 8:
			return application.bxo_status;
		default:
			return '';
	}
}

function updatePaginationControls() {
	const paginationControls = document.getElementById('pagination-controls');
	paginationControls.innerHTML = '';

	const paginationInfo = document.getElementById('pagination-info');
	const filteredData = getFilteredData();
	const totalItems = filteredData.length;
	const startItem = totalItems === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
	const endItem = Math.min(currentPage * rowsPerPage, totalItems);

	paginationInfo.textContent = `Показано с ${startItem} по ${endItem} из ${totalItems} заявок`;
	paginationInfo.classList.add('d-flex');
	paginationInfo.classList.add('align-items-center');

	// Previous button
	const prevLi = document.createElement('li');
	prevLi.className = 'page-item ' + (currentPage === 1 ? 'disabled' : '');
	const prevLink = document.createElement('a');
	prevLink.className = 'page-link';
	prevLink.href = '#';
	prevLink.tabIndex = '-1';
	prevLink.innerHTML = '&laquo;';
	prevLink.addEventListener('click', function(e) {
		e.preventDefault();
		if (currentPage > 1) {
			currentPage--;
			renderTable();
		}
	});
	prevLi.appendChild(prevLink);
	paginationControls.appendChild(prevLi);

	// Maximum number of pagination buttons to display
	const maxPageButtons = 7;
	let startPage, endPage;

	if (totalPages <= maxPageButtons) {
		// Show all pages
		startPage = 1;
		endPage = totalPages;
	} else {
		// Current page is near the start
		if (currentPage <= Math.ceil(maxPageButtons / 2)) {
			startPage = 1;
			endPage = maxPageButtons;
		}
		// Current page is near the end
		else if (currentPage + Math.floor(maxPageButtons / 2) >= totalPages) {
			startPage = totalPages - maxPageButtons + 1;
			endPage = totalPages;
		}
		// Current page is somewhere in the middle
		else {
			startPage = currentPage - Math.floor(maxPageButtons / 2);
			endPage = currentPage + Math.floor(maxPageButtons / 2);
		}
	}

	// First page button
	if (startPage > 1) {
		const firstLi = document.createElement('li');
		firstLi.className = 'page-item';
		const firstLink = document.createElement('a');
		firstLink.className = 'page-link';
		firstLink.href = '#';
		firstLink.textContent = '1';
		firstLink.addEventListener('click', function(e) {
			e.preventDefault();
			currentPage = 1;
			renderTable();
		});
		firstLi.appendChild(firstLink);
		paginationControls.appendChild(firstLi);

		if (startPage > 2) {
			// Ellipsis
			const ellipsisLi = document.createElement('li');
			ellipsisLi.className = 'page-item disabled';
			const ellipsisSpan = document.createElement('span');
			ellipsisSpan.className = 'page-link';
			ellipsisSpan.textContent = '...';
			ellipsisLi.appendChild(ellipsisSpan);
			paginationControls.appendChild(ellipsisLi);
		}
	}

	// Page number buttons
	for (let i = startPage; i <= endPage; i++) {
		const li = document.createElement('li');
		li.className = 'page-item ' + (currentPage === i ? 'active' : '');
		const link = document.createElement('a');
		link.className = 'page-link';
		link.href = '#';
		link.textContent = i;
		link.addEventListener('click', function(e) {
			e.preventDefault();
			currentPage = i;
			renderTable();
		});
		li.appendChild(link);
		paginationControls.appendChild(li);
	}

	// Last page button
	if (endPage < totalPages) {
		if (endPage < totalPages - 1) {
			// Ellipsis
			const ellipsisLi = document.createElement('li');
			ellipsisLi.className = 'page-item disabled';
			const ellipsisSpan = document.createElement('span');
			ellipsisSpan.className = 'page-link';
			ellipsisSpan.textContent = '...';
			ellipsisLi.appendChild(ellipsisSpan);
			paginationControls.appendChild(ellipsisLi);
		}

		const lastLi = document.createElement('li');
		lastLi.className = 'page-item';
		const lastLink = document.createElement('a');
		lastLink.className = 'page-link';
		lastLink.href = '#';
		lastLink.textContent = totalPages;
		lastLink.addEventListener('click', function(e) {
			e.preventDefault();
			currentPage = totalPages;
			renderTable();
		});
		lastLi.appendChild(lastLink);
		paginationControls.appendChild(lastLi);
	}

	// Next button
	const nextLi = document.createElement('li');
	nextLi.className = 'page-item ' + (currentPage === totalPages ? 'disabled' : '');
	const nextLink = document.createElement('a');
	nextLink.className = 'page-link';
	nextLink.href = '#';
	nextLink.innerHTML = '&raquo;';
	nextLink.addEventListener('click', function(e) {
		e.preventDefault();
		if (currentPage < totalPages) {
			currentPage++;
			renderTable();
		}
	});
	nextLi.appendChild(nextLink);
	paginationControls.appendChild(nextLi);
}

function numberFormat(value) {
	return new Intl.NumberFormat('ru-RU').format(value);
}

function formatPhoneNumber(phone) {
	phone = phone.replace(/[^0-9]/g, '');
	return "+998 (" + phone.substring(3, 5) + ") " + phone.substring(5, 8) + "-" + phone.substring(8, 10) + "-" + phone
		.substring(10, 12);
}

function getIntervalBadgeClass(status, interval) {
	if (status != '0') {
		return 'bg-blue text-white';
	} else if (interval > 4) {
		return 'bg-red-lt';
	} else if (interval > 2) {
		return 'bg-yellow-lt';
	} else {
		return '';
	}
}

function getStatusBadge(status, application) {
	let badgeHtml = '';
	switch (status) {
		case '0':
			badgeHtml = `<span class="badge bg-orange-lt mb-2">В ожидании</span>`;
			if (userType == '0') {
				badgeHtml += `
                    <div class="btn-list d-flex flex-nowrap">
                        <a class="btn btn-sm btn-outline-success"
                            href="/application/accept/${application.id}"
                            onclick="return confirm('Вы точно хотите принять заявку ${application.student_mf_id}?');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg> Принять
                        </a>
                        <a class="btn btn-sm btn-outline-danger"
                            href="/application/reject/${application.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg> Отказать
                        </a>
                    </div>
                `;
			}
			break;
		case '1':
			badgeHtml = `<span class="badge bg-green-lt mb-2">Принято</span>`;
			if (userType == '0') {
				badgeHtml += `
                    <div class="btn-list d-flex flex-nowrap">
                        <a class="btn btn-sm btn-outline-success"
                            href="/application/success/${application.id}"
                            onclick="return confirm('Вы точно хотите одобрить заявку ${application.student_mf_id}?');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg> Выдать кредит
                        </a>
                        <a class="btn btn-sm btn-outline-danger"
                            href="/application/cancel/${application.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg> Отменить
                        </a>
                    </div>
                `;
			}
			break;
		case '2':
			badgeHtml = `<span class="badge bg-red-lt">Отказано</span>`;
			break;
		case '3':
			badgeHtml = `<span class="badge bg-green-lt">Кредит выдан</span>`;
			break;
		case '4':
			badgeHtml = `<span class="badge bg-red-lt">Отменена</span>`;
			break;
		default:
			badgeHtml = `<span class="badge bg-gray-lt">Неизвестно</span>`;
	}
	return badgeHtml;
}

function sortTable(n) {
	if (sortColumn === n) {
		sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
	} else {
		sortColumn = n;
		sortDirection = 'asc';
	}
	resetArrows();
	updateArrow(n, sortDirection);
	renderTable();
}

function resetArrows() {
	const arrows = document.querySelectorAll('.sort-arrow');
	arrows.forEach(arrow => {
		arrow.innerHTML = '';
	});
}

function updateArrow(columnIndex, direction) {
	const arrowElement = document.getElementById(`arrow-${columnIndex}`);
	if (direction === 'asc') {
		arrowElement.innerHTML =
			`<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-up" width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><polyline points="6 15 12 9 18 15"/></svg>`;
	} else {
		arrowElement.innerHTML =
			`<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-down" width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><polyline points="6 9 12 15 18 9"/></svg>`;
	}
}

function copyToClipboard(text) {
	navigator.clipboard.writeText(text);
}

function control_content(str, status = false) {
	if (status === true) {
		if (str == '1') {
			return `<span class="badge bg-green-lt">Принято</span>`;
		} else if (str == '2') {
			return `<span class="badge bg-red-lt">Отказано</span>`;
		} else if (str == '3') {
			return `<span class="badge bg-green-lt">Кредит выдан</span>`;
		} else if (str == '4') {
			return `<span class="badge bg-red-lt">Отменена</span>`;
		} else {
			return `<span class="badge bg-orange-lt">В ожидании</span>`;
		}
	}
	if (str) {
		return `<span style="cursor: pointer;" onclick='copyToClipboard("${str}");'>${str}</span>`;
	} else {
		return '—';
	}
}

function onRowClick() {
	let applicationData = data[this.getAttribute('data-order')];

	document.getElementById('applicationId').innerHTML = applicationData.student_mf_id;

	let modalContent = `
        <div style="display: flex;">
        <table class="table table-sm">
            <tr>
                <th style="width: 50%;">Фамилия / Имя / Отчество</th>
                <td style="width: 50%;">${control_content(applicationData.lastname + ' ' + applicationData.firstname + ' ' + applicationData.fathername)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">ПИНФЛ</th>
                <td style="width: 50%;">${control_content(applicationData.pinfl)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Серия паспорта</th>
                <td style="width: 50%;">${control_content(applicationData.serial_number)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Дата рождения</th>
                <td style="width: 50%;">${control_content(applicationData.birthday)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Пол</th>
                <td style="width: 50%;">${control_content(applicationData.gender_name)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Номер телефона</th>
                <td style="width: 50%;">${control_content(applicationData.phone_number)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Регион</th>
                <td style="width: 50%;">${control_content(applicationData.region)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Район</th>
                <td style="width: 50%;">${control_content(applicationData.district)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Текущий адрес</th>
                <td style="width: 50%;">${control_content(applicationData.address_current)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Дата получения паспорта</th>
                <td style="width: 50%;">${control_content(applicationData.given_date)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">ID гражданства</th>
                <td style="width: 50%;">${control_content(applicationData.citizenship_id)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Код национальности</th>
                <td style="width: 50%;">${control_content(applicationData.nationality_code)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Название национальности</th>
                <td style="width: 50%;">${control_content(applicationData.nationality_name)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Средний балл (GPA)</th>
                <td style="width: 50%;">${control_content(applicationData.gpa)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Количество учебных лет</th>
                <td style="width: 50%;">${control_content(applicationData.edu_period)}</td>
            </tr>
        </table>

        <table class="table table-sm">
            <tr>
                <th style="width: 50%;">Сумма кредита</th>
                <td style="width: 50%;">${control_content(applicationData.credit_sum)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Договор о оплате</th>
                <td style="width: 50%;"><a target="_blank" href="${applicationData.pdf_link}">Посмотреть PDF</a></td>
            </tr>
            <tr>
                <th style="width: 50%;">Курс</th>
                <td style="width: 50%;">${control_content(applicationData.course)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Год обучения</th>
                <td style="width: 50%;">${control_content(applicationData.education_year)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Название филиала банка</th>
                <td style="width: 50%;">${control_content(applicationData.bank_branch_name)} | ${control_content(applicationData.bank_branch_code)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Единый реестр</th>
                <td style="width: 50%;">${control_content(applicationData.single_register)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Название университета</th>
                <td style="width: 50%;">${control_content(applicationData.university_name)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">ID комиссии</th>
                <td style="width: 50%;">${control_content(applicationData.com_sum_id)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Является резидентом</th>
                <td style="width: 50%;">${applicationData.is_resident ? 'Да' : 'Нет'}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Статус</th>
                <td style="width: 50%;">${getStatusBadge(applicationData.bxo_status, applicationData)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Пояснения статуса</th>
                <td style="width: 50%;">${control_content(applicationData.bxo_status_reason)}</td>
            </tr>
        </table>
        </div>

        <div style="display: flex;">
        <table class="table table-sm">
            <tr>
                <th style="width: 50%;">ПИНФЛ поручателя</th>
                <td style="width: 50%;">${control_content(applicationData.additional_person_pinfl)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Серия паспорта поручателя</th>
                <td style="width: 50%;">${control_content(applicationData.additional_person_series)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Номер паспорта поручателя</th>
                <td style="width: 50%;">${control_content(applicationData.additional_person_number)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Дата рождения поручателя</th>
                <td style="width: 50%;">${control_content(applicationData.additional_person_birth_date)}</td>
            </tr>
        </table>

        <table class="table table-sm">
            <tr>
                <th style="width: 50%;">ПИНФЛ совместного заёмщика</th>
                <td style="width: 50%;">${control_content(applicationData.co_borrower_pinfl)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Серия паспорта совместного заёмщика</th>
                <td style="width: 50%;">${control_content(applicationData.co_borrower_series)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Номер паспорта совместного заёмщика</th>
                <td style="width: 50%;">${control_content(applicationData.co_borrower_number)}</td>
            </tr>
            <tr>
                <th style="width: 50%;">Дата рождения совместного заёмщика</th>
                <td style="width: 50%;">${control_content(applicationData.co_borrower_birth_date)}</td>
            </tr>
        </table>
        </div>
    `;

	document.getElementById('applicationModalContent').innerHTML = modalContent;

	const applicationModal = new bootstrap.Modal(document.getElementById('applicationModal'));
	applicationModal.show();
}

document.getElementById('search-input').addEventListener('input', filterTable);
document.getElementById('branch-select').addEventListener('change', filterTable);
document.getElementById('status-select').addEventListener('change', filterTable);
document.getElementById('rows-per-page').addEventListener('change', function() {
	currentPage = 1;
	renderTable();
});

// Initial render
document.addEventListener('DOMContentLoaded', function() {
	renderTable();
});
</script>