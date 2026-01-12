/***********************
  Admin scans - full JS
***********************/

/* UI elements */
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const closeSidebar = document.getElementById('close-sidebar');

function toggleSidebar() {
    sidebar?.classList.toggle('-translate-x-full');
    overlay?.classList.toggle('hidden');
}

mobileMenuBtn?.addEventListener('click', toggleSidebar);
closeSidebar?.addEventListener('click', toggleSidebar);
overlay?.addEventListener('click', toggleSidebar);

/* Global store */
let bottleRequests = [];

/* Helpers */
function getField(obj, ...keys) {
    for (const k of keys) {
        if (obj == null) continue;
        if (obj[k] !== undefined && obj[k] !== null) return obj[k];
    }
    return '';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date)) return dateString;
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getStatusBadge(status) {
    switch ((status || '').toString().toLowerCase()) {
        case 'pending':
            return '<span class="status-pending px-3 py-1 rounded-full text-xs font-medium">Pending</span>';
        case 'confirmed':
            return '<span class="bg-green-500/30 border border-green-500/50 text-green-400 px-3 py-1 rounded-full text-xs font-medium">Confirmed</span>';
        case 'denied':
        case 'rejected':
            return '<span class="bg-red-500/30 border border-red-500/50 text-red-400 px-3 py-1 rounded-full text-xs font-medium">Denied</span>';
        default:
            return '<span class="status-pending px-3 py-1 rounded-full text-xs font-medium">Unknown</span>';
    }
}

/* Fetch pending scans from backend (handles different response shapes) */
async function fetchPendingScans() {
    try {
        const res = await fetch('backend/get_pending_scans.php', { cache: 'no-store' });
        if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);
        const json = await res.json();

        // Accept either an array (direct) or { success: true, data: [...] } or { data: [...] }
        let dataArray = [];
        if (Array.isArray(json)) {
            dataArray = json;
        } else if (json && Array.isArray(json.data)) {
            dataArray = json.data;
        } else if (json && json.success && Array.isArray(json.data)) {
            dataArray = json.data;
        } else {
            // Fallback: find the first array in the object
            const firstArr = Object.values(json).find(v => Array.isArray(v));
            if (firstArr) dataArray = firstArr;
        }

        // Ensure it's an array
        bottleRequests = Array.isArray(dataArray) ? dataArray : [];
        updateDisplay();
    } catch (err) {
        console.error('fetchPendingScans error:', err);
        showNotification('Error loading scans', 'error');
        bottleRequests = [];
        updateDisplay();
    }
}

/* Render desktop table (expects an array) */
function renderTable(data) {
    const tableBody = document.getElementById('requests-table-body');
    if (!tableBody) return;
    tableBody.innerHTML = '';

    data.forEach(request => {
        // const scanId = getField(request, 'scan_id', 'id', 'scanId');
        // const bottleName = getField(request, 'bottle_name', 'bottleName', 'name');
        // const bottleCode = getField(request, 'bottle_code', 'bottleCode', 'code', '');
        // const userName = getField(request, 'user_name', 'userName', 'name');
        // const userEmail = getField(request, 'user_email', 'userEmail', 'email');
        // const qty = getField(request, 'quantity', 'qty', 'quantity');
        // const scanDate = getField(request, 'scan_date', 'scanDate', 'created_at', '');
        // const status = getField(request, 'status', 'state', '');

        const row = document.createElement('tr');
        row.className = 'border-b border-white/10 hover:bg-white/5 transition-colors';

        row.innerHTML = `
            <td class="p-4">
                <div>
                    <div class="font-medium text-white">${escapeHtml(request.bottle_name)}</div>
                    <div class="text-sm text-gray-400 mt-1">${escapeHtml(request.bottle_code)}</div>
                </div>
            </td>
            <td class="p-4">
                <div>
                    <div class="font-medium text-white">${escapeHtml(request.user_name)}</div>
                    <div class="text-sm text-gray-400 mt-1">${escapeHtml(request.user_email)}</div>
                </div>
            </td>
            <td class="p-4">
                <span class="text-2xl font-bold text-green-400">${escapeHtml(request.quantity)}</span>
            </td>
            <td class="p-4">
                <span class="text-gray-300">${formatDate(request.scanned_at)}</span>
            </td>
            <td class="p-4">
                ${getStatusBadge(request.status)}
            </td>
            <td class="p-4">
                <div class="flex items-center justify-center space-x-2">
                    <button class="btn-view px-3 py-2 rounded-lg text-white text-xs font-medium transition-all" onclick="viewImage('${request.image_path}', '${request.bottle_name}', '${request.user_name}', '${request.scanned_at}')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-confirm px-3 py-2 rounded-lg text-white text-xs font-medium transition-all" onclick="confirmRequest('${request.scan_id}')">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn-deny px-3 py-2 rounded-lg text-white text-xs font-medium transition-all" onclick="denyRequest('${request.scan_id}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </td>
        `;

        tableBody.appendChild(row);
    });
}

/* Render mobile cards */
function renderMobileCards(data) {
    const mobileContainer = document.getElementById('mobile-cards');
    if (!mobileContainer) return;
    mobileContainer.innerHTML = '';

    data.forEach(request => {
        console.log(request.image_path);
        

        const card = document.createElement('div');
        card.className = 'glassmorphism rounded-xl p-4 space-y-4';

        card.innerHTML = `
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="font-medium text-white">${escapeHtml(request.bottle_name)}</h4>
                    <p class="text-sm text-gray-400 mt-1">${escapeHtml(request.bottle_code)}</p>
                </div>
                ${getStatusBadge(request.status)}
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">User:</span>
                    <p class="text-white font-medium">${escapeHtml(request.user_name)}</p>
                    <p class="text-gray-400 text-xs">${escapeHtml(request.user_email)}</p>
                </div>
                <div>
                    <span class="text-gray-400">Quantity:</span>
                    <p class="text-2xl font-bold text-green-400">${escapeHtml(request.quantity)}</p>
                </div>
            </div>
            
            <div class="text-sm">
                <span class="text-gray-400">Scan Date:</span>
                <p class="text-white">${formatDate(request.scan_date)}</p>
            </div>
            
            <div class="flex items-center space-x-3 pt-2">
                <button class="btn-view flex-1 py-2 px-4 rounded-lg text-white text-sm font-medium transition-all" onclick="viewImage('${request.image_path}', '${request.bottle_name}', '${request.user_name}', '${request.scanned_at}')">
                    <i class="fas fa-eye mr-2"></i>View Image
                </button>
                <button class="btn-confirm p-2 rounded-lg text-white transition-all" onclick="confirmRequest('${request.scan_id}')">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn-deny p-2 rounded-lg text-white transition-all" onclick="denyRequest('${request.scan_id}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        mobileContainer.appendChild(card);
    });
}

/* View image modal */
function viewImage(imagePath,bottleName , userName , scanDate) {
    // console.log(imagePath,bottleName , userName , scanDate);
   
    // const scanId = getField(request, 'scan_id', 'id', 'scanId');
    // const bottleName = getField(request, 'bottle_name', 'bottleName', 'name');
    // const userName = getField(request, 'user_name', 'userName', 'name');
    // const scanDate = getField(request, 'scanned_at', 'scanDate', 'created_at', '');

    const modalImg = document.getElementById('modal-image');
    const details = document.getElementById('image-details');
    const modal = document.getElementById('image-modal');
    modalImg.src = '';

    if (modalImg) modalImg.src = '../backend/' + imagePath || '';
    if (details) details.textContent =  bottleName +"- Submitted by " + userName + " on " + formatDate(scanDate);
    if (modal) modal.classList.remove('hidden');
}

async function updateStatus(scanId, action) {
    try {
        // Simple client-side guard
        const ok = confirm(action === 'confirm' ? 'Confirm this bottle request?' : 'Reject this bottle request?');
        if (!ok) return;

        const formData = new FormData();
        formData.append('id', scanId);
        formData.append('action', action); // 'confirm' or 'reject'

        const res = await fetch('backend/update_scan_status.php', {
            method: 'POST',
            body: formData
        });

        if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);
        const json = await res.json();
        // console.log(json);
        if (json.success || json.success === true) {
            showNotification(action === 'confirm' ? 'Request confirmed!' : 'Request rejected', action === 'confirm' ? 'success' : 'error');
            // reload data
            await fetchPendingScans();
        } else {
            const err = json.error || 'Unknown error';
            throw new Error(err);
        }
    } catch (err) {
        console.error('updateStatus error:', err);
        showNotification('Error updating status', 'error');
    }
}

function confirmRequest(scanId) { return updateStatus(scanId, 'confirm'); }
function denyRequest(scanId) { return updateStatus(scanId, 'reject'); }

/* Notification */
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl glassmorphism text-white transition-all transform translate-x-full`;
    notification.style.maxWidth = '320px';

    if (type === 'success') {
        notification.classList.add('border', 'border-green-500/50');
        notification.innerHTML = `<i class="fas fa-check-circle text-green-400 mr-2"></i>${escapeHtml(message)}`;
    } else {
        notification.classList.add('border', 'border-red-500/50');
        notification.innerHTML = `<i class="fas fa-exclamation-circle text-red-400 mr-2"></i>${escapeHtml(message)}`;
    }

    document.body.appendChild(notification);

    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/* Filter / search / update */
function getFilteredData() {
    const searchInput = document.getElementById('search-input');
    const filterSelect = document.getElementById('filter-select');
    const searchTerm = (searchInput?.value || '').toLowerCase();
    const statusFilter = filterSelect?.value || 'all';

    return bottleRequests.filter(request => {
        const bottleName = (getField(request, 'bottle_name', 'bottleName') || '').toString().toLowerCase();
        const userName = (getField(request, 'user_name', 'userName') || '').toString().toLowerCase();
        const userEmail = (getField(request, 'user_email', 'userEmail') || '').toString().toLowerCase();

        const matchesSearch = bottleName.includes(searchTerm) || userName.includes(searchTerm) || userEmail.includes(searchTerm);
        const matchesStatus = statusFilter === 'all' || (getField(request, 'status', 'state') || '').toString().toLowerCase() === statusFilter.toString().toLowerCase();

        return matchesSearch && matchesStatus;
    });
}

function updatePendingCount() {
    const pendingCount = bottleRequests.filter(r => ((getField(r, 'status','state')||'').toString().toLowerCase() === 'pending')).length;
    const el = document.getElementById('pending-count');
    if (el) el.textContent = pendingCount;
}

function updateDisplay() {
    const filteredData = getFilteredData();
    renderTable(filteredData);
    renderMobileCards(filteredData);
    updatePendingCount();
}


document.getElementById('close-modal')?.addEventListener('click', () => document.getElementById('image-modal')?.classList.add('hidden'));
document.getElementById('image-modal')?.addEventListener('click', e => { if (e.target === e.currentTarget) e.currentTarget.classList.add('hidden'); });


document.getElementById('search-input')?.addEventListener('input', updateDisplay);
document.getElementById('filter-select')?.addEventListener('change', updateDisplay);

function escapeHtml(unsafe) {
    if (unsafe === undefined || unsafe === null) return '';
    return String(unsafe)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', () => {
    fetchPendingScans();
});
