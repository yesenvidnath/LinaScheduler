const tokenKey = 'lina_auth_token';
const userKey = 'lina_user';

const token = localStorage.getItem(tokenKey);
const currentUser = JSON.parse(localStorage.getItem(userKey) || 'null');

if (!token || !currentUser?.is_admin) {
    window.location.href = '/';
}

const resources = {
    branches: {
        label: 'Branches',
        icon: 'fa-code-branch',
        endpoint: '/api/admin/branches',
        id: 'Branch_ID',
        display: ['Branch_Name', 'Status'],
        fields: [
            { name: 'Branch_Name', label: 'Branch name', required: true },
            { name: 'Branch_Discription', label: 'Description', type: 'textarea', required: true },
            { name: 'Status', label: 'Status', type: 'select', options: ['1', '0', '1*'], required: true }
        ]
    },
    flows: {
        label: 'Flows',
        icon: 'fa-layer-group',
        endpoint: '/api/admin/flows',
        id: 'Fl_ID',
        display: ['Fl_Name', 'Branch_ID'],
        fields: [
            { name: 'Branch_ID', label: 'Branch ID', type: 'number', required: true },
            { name: 'Fl_Name', label: 'Flow name', required: true },
            { name: 'Fl_Discription', label: 'Description', type: 'textarea', required: true }
        ]
    },
    rooms: {
        label: 'Rooms',
        icon: 'fa-door-open',
        endpoint: '/api/admin/rooms',
        id: 'Room_ID',
        display: ['Room_Number', 'Room_Type', 'Max_Student_Count'],
        fields: [
            { name: 'Fl_ID', label: 'Flow ID', type: 'number', required: true },
            { name: 'Room_Number', label: 'Room number', required: true },
            { name: 'Room_Type', label: 'Room type', type: 'select', options: ['Library', 'Class', 'Laboratory', 'StudyArea'], required: true },
            { name: 'Room_Availability', label: 'Availability', type: 'select', options: ['1', '0', '1*'], required: true },
            { name: 'Max_Student_Count', label: 'Max students', type: 'number', required: true },
            { name: 'Max_Chair_Count', label: 'Max chairs', type: 'number', required: true },
            { name: 'Max_Power_Outlets', label: 'Power outlets', type: 'number', required: true },
            { name: 'Max_Table_Count', label: 'Tables', type: 'number', required: true },
            { name: 'Is_WhiteBoard_Avilable', label: 'Whiteboard', type: 'checkbox' },
            { name: 'Is_Projector_Avilable', label: 'Projector', type: 'checkbox' },
            { name: 'Is_Smart_board_Avilable', label: 'Smart board', type: 'checkbox' },
            { name: 'Room_Discrption', label: 'Description', type: 'textarea', required: true }
        ]
    },
    classes: {
        label: 'Classes',
        icon: 'fa-chalkboard',
        endpoint: '/api/admin/rooms/roomtypes/classes',
        id: 'Cls_ID',
        display: ['Cls_Number', 'Room_ID'],
        fields: [
            { name: 'Room_ID', label: 'Room ID', type: 'number', required: true },
            { name: 'Cls_Number', label: 'Class number', required: true },
            { name: 'Cls_Discription', label: 'Description', type: 'textarea', required: true }
        ]
    },
    studyRooms: {
        label: 'Study Rooms',
        icon: 'fa-book-open-reader',
        endpoint: '/api/admin/rooms/roomtypes/studyroom',
        id: 'Study_ID',
        display: ['Study_Number', 'Room_ID'],
        fields: [
            { name: 'Room_ID', label: 'Room ID', type: 'number', required: true },
            { name: 'Study_Number', label: 'Study room number', required: true },
            { name: 'Study_Discription', label: 'Description', type: 'textarea', required: true }
        ]
    },
    libraryRooms: {
        label: 'Libraries',
        icon: 'fa-book',
        endpoint: '/api/admin/rooms/roomtypes/libraryroom',
        id: 'Lib_ID',
        display: ['Lib_Number', 'Room_ID'],
        fields: [
            { name: 'Room_ID', label: 'Room ID', type: 'number', required: true },
            { name: 'Lib_Number', label: 'Library number', required: true },
            { name: 'Lib_Discription', label: 'Description', type: 'textarea', required: true }
        ]
    },
    laboratoryTypes: {
        label: 'Lab Types',
        icon: 'fa-flask',
        endpoint: '/api/admin/rooms/roomtypes/laboratory_types',
        id: 'Lab_Type_ID',
        display: ['Lab_Type'],
        fields: [
            { name: 'Lab_Type', label: 'Laboratory type', required: true },
            { name: 'Lab_Type_Discription', label: 'Description', type: 'textarea', required: true }
        ]
    },
    laboratories: {
        label: 'Laboratories',
        icon: 'fa-microscope',
        endpoint: '/api/admin/rooms/roomtypes/laboratoriesroom',
        id: 'Lab_ID',
        display: ['Lab_Number', 'Room_ID', 'Lab_Type_ID'],
        fields: [
            { name: 'Room_ID', label: 'Room ID', type: 'number', required: true },
            { name: 'Lab_Type_ID', label: 'Lab type ID', type: 'number', required: true },
            { name: 'Lab_Number', label: 'Lab number', required: true },
            { name: 'Lab_Equipment_Count', label: 'Equipment count', type: 'number', required: true },
            { name: 'Lab_Discription', label: 'Description', type: 'textarea', required: true }
        ]
    },
    courses: {
        label: 'Courses',
        icon: 'fa-graduation-cap',
        endpoint: '/api/admin/courses',
        id: 'Course_ID',
        display: ['Course_Name', 'Status'],
        fields: [
            { name: 'Course_Name', label: 'Course name', required: true },
            { name: 'Course_Discription', label: 'Description', type: 'textarea', required: true },
            { name: 'Status', label: 'Status', type: 'select', options: ['1', '0', '1*'], required: true }
        ]
    },
    batches: {
        label: 'Batches',
        icon: 'fa-people-group',
        endpoint: '/api/admin/batches',
        id: 'Batch_ID',
        display: ['Batch_Name', 'Batch_Student_Count', 'Status'],
        fields: [
            { name: 'Batch_Name', label: 'Batch name', required: true },
            { name: 'Batch_Student_Count', label: 'Student count', type: 'number', required: true },
            { name: 'Batch_Discription', label: 'Description', type: 'textarea', required: true },
            { name: 'Status', label: 'Status', type: 'select', options: ['1', '0', '1*'], required: true }
        ]
    },
    users: {
        label: 'Users',
        icon: 'fa-users',
        endpoint: '/api/admin/users',
        id: 'User_ID',
        display: ['First_Name', 'Last_Name', 'Email', 'UD_ID'],
        fields: [
            { name: 'UD_ID', label: 'Designation ID', type: 'number', required: true },
            { name: 'Honorifics_ID', label: 'Honorific ID', type: 'number', required: true },
            { name: 'First_Name', label: 'First name', required: true },
            { name: 'Last_Name', label: 'Last name', required: true },
            { name: 'Email', label: 'Email', type: 'email', required: true },
            { name: 'User_Discrption', label: 'Description', type: 'textarea', required: true },
            { name: 'Status', label: 'Status', type: 'select', options: ['1', '0', '1*'], required: true },
            { name: 'password', label: 'Password', type: 'password' }
        ]
    },
    honorifics: {
        label: 'Honorifics',
        icon: 'fa-id-badge',
        endpoint: '/api/admin/users/honorifics',
        id: 'Honorifics_ID',
        display: ['Honorific'],
        fields: [
            { name: 'Honorific', label: 'Honorific', required: true }
        ]
    },
    equipmentTypes: {
        label: 'Equipment Types',
        icon: 'fa-screwdriver-wrench',
        endpoint: '/api/admin/Equipments/equipmenttypes',
        id: 'Equip_Type_ID',
        display: ['Equip_Type'],
        fields: [
            { name: 'Equip_Type', label: 'Equipment type', required: true },
            { name: 'Equip_Type_Discrption', label: 'Description', type: 'textarea', required: true }
        ]
    },
    equipment: {
        label: 'Equipment',
        icon: 'fa-computer',
        endpoint: '/api/admin/Equipments',
        id: 'Equip_ID',
        display: ['Equip_Type_ID', 'Equip_Userbility_Status', 'Is_Booked'],
        fields: [
            { name: 'Equip_Type_ID', label: 'Equipment type ID', type: 'number', required: true },
            { name: 'Equip_Userbility_Status', label: 'Usability', type: 'select', options: ['1', '0', '1*'], required: true },
            { name: 'Is_Booked', label: 'Booked', type: 'select', options: ['0', '1', '1*'], required: true },
            { name: 'Equip_Discrption', label: 'Description', type: 'textarea', required: true }
        ]
    },
    bookingRequests: {
        label: 'Booking Requests',
        icon: 'fa-calendar-days',
        endpoint: '/api/admin/scheduling/booking-requests',
        id: 'BookRequest_ID',
        display: ['Course_ID', 'Batch_ID', 'User_ID', 'Class_Type', 'Status'],
        fields: [
            { name: 'Course_ID', label: 'Course ID', type: 'number', required: true },
            { name: 'Batch_ID', label: 'Batch ID', type: 'number', required: true },
            { name: 'User_ID', label: 'Lecturer/User ID', type: 'number', required: true },
            { name: 'ERL_ID', label: 'Equipment request ID', type: 'number' },
            { name: 'Class_Type', label: 'Class type', type: 'select', options: ['Theory', 'Practical', 'Lesson'], required: true },
            { name: 'Expected_Student_Count', label: 'Expected students', type: 'number', required: true },
            { name: 'Class_Start_Time', label: 'Start time', type: 'datetime-local', required: true },
            { name: 'Class_End_Time', label: 'End time', type: 'datetime-local', required: true },
            { name: 'Status', label: 'Status', type: 'select', options: ['Pending', 'Confirmed', 'Rejected'] }
        ]
    },
    classRoomBookings: {
        label: 'Room Bookings',
        icon: 'fa-calendar-check',
        endpoint: '/api/admin/scheduling/class-room-bookings',
        id: 'CRB_ID',
        display: ['Room_ID', 'BookRequest_ID'],
        fields: [
            { name: 'Room_ID', label: 'Room ID', type: 'number', required: true },
            { name: 'BookRequest_ID', label: 'Booking request ID', type: 'number', required: true },
            { name: 'CRB_Discription', label: 'Description', type: 'textarea' }
        ],
        noUpdate: true,
        noRecover: true,
        noDeleted: true
    }
};

let activeResource = null;
let editingId = null;
const recordsCache = {};

const nav = document.getElementById('adminNav');
const overviewPanel = document.getElementById('overviewPanel');
const resourcePanel = document.getElementById('resourcePanel');
const resourceForm = document.getElementById('resourceForm');
const resourceTable = document.getElementById('resourceTable');

function authHeaders(extra = {}) {
    return {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`,
        ...extra
    };
}

async function apiRequest(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: authHeaders(options.headers || {})
    });

    const data = await response.json().catch(() => ({}));

    if (response.status === 401 || response.status === 403) {
        localStorage.removeItem(tokenKey);
        localStorage.removeItem(userKey);
        window.location.href = '/';
        return null;
    }

    if (!response.ok) {
        const errors = data.errors ? Object.values(data.errors).flat().join(' ') : null;
        throw new Error(errors || data.message || 'Request failed');
    }

    return data;
}

function showAlert(message, type = 'danger') {
    const alert = document.getElementById('adminAlert');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    window.setTimeout(() => alert.classList.add('d-none'), 5000);
}

function normalizeRows(data) {
    if (!data) {
        return [];
    }
    if (Array.isArray(data)) {
        return data;
    }
    if (data.uploaded) {
        return data.uploaded;
    }
    return [data];
}

function valueFor(row, key) {
    const value = row?.[key];
    if (value === true) {
        return 'Yes';
    }
    if (value === false) {
        return 'No';
    }
    if (value === null || value === undefined || value === '') {
        return '-';
    }
    return String(value);
}

function renderNav() {
    nav.innerHTML = `
        <button class="nav-item active" data-view="overview">
            <i class="fa-solid fa-chart-line"></i><span>Dashboard</span>
        </button>
        ${Object.entries(resources).map(([key, resource]) => `
            <button class="nav-item" data-resource="${key}">
                <i class="fa-solid ${resource.icon}"></i><span>${resource.label}</span>
            </button>
        `).join('')}
    `;
}

function setActiveNav(selector) {
    document.querySelectorAll('.nav-item').forEach((item) => item.classList.remove('active'));
    document.querySelector(selector)?.classList.add('active');
}

function inputHtml(field) {
    const required = field.required ? 'required' : '';

    if (field.type === 'textarea') {
        return `<textarea class="form-control" name="${field.name}" rows="3" ${required}></textarea>`;
    }

    if (field.type === 'select') {
        return `
            <select class="form-control" name="${field.name}" ${required}>
                <option value="">Select</option>
                ${field.options.map((option) => `<option value="${option}">${option}</option>`).join('')}
            </select>
        `;
    }

    if (field.type === 'checkbox') {
        return `
            <input type="hidden" name="${field.name}" value="0">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="${field.name}" value="1">
            </div>
        `;
    }

    return `<input class="form-control" type="${field.type || 'text'}" name="${field.name}" ${required}>`;
}

function renderForm(resourceKey, row = null) {
    const resource = resources[resourceKey];
    editingId = row?.[resource.id] || null;
    document.getElementById('formTitle').textContent = editingId ? `Edit ${resource.label}` : `Create ${resource.label}`;

    resourceForm.innerHTML = `
        ${resource.fields.map((field) => `
            <label class="field-label">
                <span>${field.label}</span>
                ${inputHtml(field)}
            </label>
        `).join('')}
        <button type="submit" class="btn btn-primary">${editingId ? 'Update' : 'Create'}</button>
    `;

    if (row) {
        resource.fields.forEach((field) => {
            const inputs = resourceForm.querySelectorAll(`[name="${field.name}"]`);
            inputs.forEach((input) => {
                if (input.type === 'checkbox') {
                    input.checked = row[field.name] === true || row[field.name] === 1 || row[field.name] === '1';
                } else if (input.type !== 'hidden') {
                    input.value = formatInputValue(field, row[field.name]);
                }
            });
        });
    }
}

function formatInputValue(field, value) {
    if (!value) {
        return '';
    }
    if (field.type === 'datetime-local') {
        return String(value).slice(0, 16);
    }
    return value;
}

function renderTable(resourceKey, rows) {
    const resource = resources[resourceKey];
    const columns = [resource.id, ...resource.display];

    recordsCache[resourceKey] = rows;
    document.getElementById('tableTitle').textContent = `${resource.label} Records`;

    if (!rows.length) {
        resourceTable.innerHTML = '<tbody><tr><td class="empty-cell">No records found</td></tr></tbody>';
        return;
    }

    resourceTable.innerHTML = `
        <thead>
            <tr>
                ${columns.map((column) => `<th>${column}</th>`).join('')}
                <th class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody>
            ${rows.map((row) => `
                <tr>
                    ${columns.map((column) => `<td>${valueFor(row, column)}</td>`).join('')}
                    <td class="actions-column">
                        <button class="icon-button small" title="Edit" data-edit-id="${row[resource.id]}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="icon-button small danger" title="Delete" data-delete-id="${row[resource.id]}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        ${resource.noRecover ? '' : `
                            <button class="icon-button small" title="Recover" data-recover-id="${row[resource.id]}">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        `}
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
}

async function loadResource(resourceKey, mode = 'index', param = '*') {
    const resource = resources[resourceKey];
    activeResource = resourceKey;
    document.getElementById('pageTitle').textContent = resource.label;
    overviewPanel.classList.add('d-none');
    resourcePanel.classList.remove('d-none');
    setActiveNav(`[data-resource="${resourceKey}"]`);
    renderForm(resourceKey);

    let url = resource.endpoint;
    if (mode === 'show') {
        url = `${resource.endpoint}/show/${encodeURIComponent(param || '*')}`;
    }
    if (mode === 'deleted') {
        if (resource.noDeleted) {
            showAlert('This resource does not expose deleted records.', 'warning');
            return;
        }
        url = `${resource.endpoint}/deleted/${encodeURIComponent(param || '*')}`;
    }

    try {
        const data = await apiRequest(url);
        renderTable(resourceKey, normalizeRows(data));
    } catch (error) {
        renderTable(resourceKey, []);
        showAlert(error.message, 'warning');
    }
}

async function loadOverview() {
    activeResource = null;
    document.getElementById('pageTitle').textContent = 'Dashboard';
    resourcePanel.classList.add('d-none');
    overviewPanel.classList.remove('d-none');
    setActiveNav('[data-view="overview"]');

    const metricKeys = ['rooms', 'courses', 'batches', 'users', 'equipment', 'bookingRequests'];
    const results = await Promise.all(metricKeys.map(async (key) => {
        try {
            const data = await apiRequest(resources[key].endpoint);
            return [key, normalizeRows(data)];
        } catch {
            return [key, []];
        }
    }));

    const map = Object.fromEntries(results);
    document.getElementById('metricGrid').innerHTML = metricKeys.map((key) => `
        <button class="metric-card" data-target-resource="${key}">
            <span>${resources[key].label}</span>
            <strong>${map[key].length}</strong>
        </button>
    `).join('');

    renderMiniList('pendingRequests', map.bookingRequests.filter((row) => row.Status === 'Pending'), 'BookRequest_ID', ['Class_Type', 'Class_Start_Time']);

    try {
        const assignments = normalizeRows(await apiRequest(resources.classRoomBookings.endpoint));
        renderMiniList('assignmentList', assignments, 'CRB_ID', ['Room_ID', 'BookRequest_ID']);
    } catch {
        renderMiniList('assignmentList', [], 'CRB_ID', []);
    }
}

function renderMiniList(id, rows, primaryKey, columns) {
    const element = document.getElementById(id);
    if (!rows.length) {
        element.innerHTML = '<p class="empty-note">No records to show.</p>';
        return;
    }

    element.innerHTML = rows.slice(0, 6).map((row) => `
        <div class="mini-row">
            <strong>#${valueFor(row, primaryKey)}</strong>
            <span>${columns.map((column) => valueFor(row, column)).join(' · ')}</span>
        </div>
    `).join('');
}

resourceForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (!activeResource) {
        return;
    }

    const resource = resources[activeResource];
    const payload = Object.fromEntries(new FormData(resourceForm));

    resource.fields.forEach((field) => {
        if (field.type === 'checkbox') {
            payload[field.name] = resourceForm.querySelector(`input[type="checkbox"][name="${field.name}"]`)?.checked ? 1 : 0;
        }
        if (!field.required && payload[field.name] === '') {
            delete payload[field.name];
        }
    });

    if (editingId && resource.noUpdate) {
        showAlert('This resource does not expose an update endpoint.', 'warning');
        return;
    }

    const url = editingId ? `${resource.endpoint}/update/${editingId}` : `${resource.endpoint}/create`;
    const method = editingId ? 'PUT' : 'POST';

    try {
        await apiRequest(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        showAlert(editingId ? 'Record updated.' : 'Record created.', 'success');
        await loadResource(activeResource);
    } catch (error) {
        showAlert(error.message);
    }
});

resourceTable.addEventListener('click', async (event) => {
    const editButton = event.target.closest('[data-edit-id]');
    const deleteButton = event.target.closest('[data-delete-id]');
    const recoverButton = event.target.closest('[data-recover-id]');

    if (!activeResource) {
        return;
    }

    const resource = resources[activeResource];

    if (editButton) {
        const row = recordsCache[activeResource].find((item) => String(item[resource.id]) === editButton.dataset.editId);
        renderForm(activeResource, row);
    }

    if (deleteButton) {
        if (!confirm('Delete this record?')) {
            return;
        }
        try {
            await apiRequest(`${resource.endpoint}/destroy/${deleteButton.dataset.deleteId}`, { method: 'DELETE' });
            showAlert('Record deleted.', 'success');
            await loadResource(activeResource);
        } catch (error) {
            showAlert(error.message);
        }
    }

    if (recoverButton) {
        try {
            await apiRequest(`${resource.endpoint}/recover/${recoverButton.dataset.recoverId}`, { method: 'PUT' });
            showAlert('Record recovered.', 'success');
            await loadResource(activeResource);
        } catch (error) {
            showAlert(error.message);
        }
    }
});

document.getElementById('clearFormButton').addEventListener('click', () => {
    if (activeResource) {
        renderForm(activeResource);
    }
});

document.getElementById('showParamButton').addEventListener('click', () => {
    if (activeResource) {
        loadResource(activeResource, 'show', document.getElementById('paramInput').value || '*');
    }
});

document.getElementById('deletedParamButton').addEventListener('click', () => {
    if (activeResource) {
        loadResource(activeResource, 'deleted', document.getElementById('paramInput').value || '*');
    }
});

document.getElementById('refreshButton').addEventListener('click', () => {
    activeResource ? loadResource(activeResource) : loadOverview();
});

document.getElementById('logoutButton').addEventListener('click', () => {
    localStorage.removeItem(tokenKey);
    localStorage.removeItem(userKey);
    window.location.href = '/';
});

document.addEventListener('click', (event) => {
    const targetButton = event.target.closest('[data-target-resource]');
    if (targetButton) {
        loadResource(targetButton.dataset.targetResource);
    }
});

nav.addEventListener('click', (event) => {
    const button = event.target.closest('button');
    if (!button) {
        return;
    }

    if (button.dataset.view === 'overview') {
        loadOverview();
    }

    if (button.dataset.resource) {
        loadResource(button.dataset.resource);
    }
});

document.getElementById('userLabel').textContent = `${currentUser.First_Name || 'Admin'} ${currentUser.Last_Name || ''}`.trim();
document.getElementById('roleLabel').textContent = currentUser.role || 'Admin';

renderNav();
loadOverview();
