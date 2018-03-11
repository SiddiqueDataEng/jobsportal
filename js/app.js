const API_BASE = 'api/';
let authToken = localStorage.getItem('authToken');
let currentUser = JSON.parse(localStorage.getItem('currentUser') || 'null');
let currentJobId = null;
let currentTab = 'browse';

// Check authentication on load
window.addEventListener('DOMContentLoaded', () => {
    if (authToken && currentUser) {
        showDashboard();
        loadAvailableJobs();
    }
});

// Auth Functions
function showLogin() {
    document.getElementById('loginForm').classList.remove('hidden');
    document.getElementById('registerForm').classList.add('hidden');
    document.getElementById('loginTab').classList.add('border-blue-600', 'text-blue-600');
    document.getElementById('loginTab').classList.remove('text-gray-600');
    document.getElementById('registerTab').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('registerTab').classList.add('text-gray-600');
}

function showRegister() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('registerForm').classList.remove('hidden');
    document.getElementById('registerTab').classList.add('border-blue-600', 'text-blue-600');
    document.getElementById('registerTab').classList.remove('text-gray-600');
    document.getElementById('loginTab').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('loginTab').classList.add('text-gray-600');
}

async function handleLogin(e) {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    try {
        const response = await fetch(API_BASE + 'auth/login.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({email, password})
        });
        
        const data = await response.json();
        
        if (data.success) {
            authToken = data.token;
            currentUser = data.user;
            localStorage.setItem('authToken', authToken);
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            showMessage('Login successful!', 'success');
            setTimeout(() => {
                showDashboard();
                loadDashboardData();
            }, 1000);
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Login failed. Please try again.', 'error');
    }
}

async function handleRegister(e) {
    e.preventDefault();
    const full_name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const phone = document.getElementById('registerPhone').value;
    
    try {
        const response = await fetch(API_BASE + 'auth/register.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({full_name, email, password, phone})
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('Registration successful! Please login.', 'success');
            setTimeout(() => showLogin(), 2000);
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Registration failed. Please try again.', 'error');
    }
}

function handleLogout() {
    localStorage.removeItem('authToken');
    localStorage.removeItem('currentUser');
    authToken = null;
    currentUser = null;
    document.getElementById('dashboardPage').classList.add('hidden');
    document.getElementById('loginPage').classList.remove('hidden');
}

function showDashboard() {
    document.getElementById('loginPage').classList.add('hidden');
    document.getElementById('dashboardPage').classList.remove('hidden');
    document.getElementById('userName').textContent = currentUser.full_name;
    showJobSeekerTab('browse');
}

// Tab Management for Job Seekers
function showJobSeekerTab(tabName) {
    currentTab = tabName;
    
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    
    // Remove active state from all tab buttons
    document.getElementById('browseTab').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('browseTab').classList.add('text-gray-600');
    document.getElementById('applicationsTabBtn').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('applicationsTabBtn').classList.add('text-gray-600');
    
    // Show selected tab
    if (tabName === 'browse') {
        document.getElementById('browseJobsContent').classList.remove('hidden');
        document.getElementById('browseTab').classList.add('border-blue-600', 'text-blue-600');
        document.getElementById('browseTab').classList.remove('text-gray-600');
        loadAvailableJobs();
    } else if (tabName === 'applications') {
        document.getElementById('myApplicationsContent').classList.remove('hidden');
        document.getElementById('applicationsTabBtn').classList.add('border-blue-600', 'text-blue-600');
        document.getElementById('applicationsTabBtn').classList.remove('text-gray-600');
        loadDashboardData();
    }
}

// Load Available Jobs
async function loadAvailableJobs() {
    const search = document.getElementById('jobSearchKeyword').value;
    const jobType = document.getElementById('jobTypeFilter').value;
    
    let url = API_BASE + 'jobs/list.php?status=Active';
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (jobType) url += `&job_type=${encodeURIComponent(jobType)}`;
    
    try {
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success) {
            displayAvailableJobs(result.data);
        }
    } catch (error) {
        console.error('Failed to load jobs:', error);
    }
}

function displayAvailableJobs(jobs) {
    const container = document.getElementById('availableJobsList');
    const countEl = document.getElementById('availableJobCount');
    
    countEl.textContent = `(${jobs.length} jobs)`;
    
    if (jobs.length === 0) {
        container.innerHTML = '<div class="col-span-3 text-center py-12 text-gray-500">No jobs available</div>';
        return;
    }
    
    container.innerHTML = jobs.map(job => `
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">${job.position}</h3>
                    <p class="text-lg text-blue-600 font-semibold">${job.company_name}</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    ${job.job_type}
                </span>
            </div>
            <div class="space-y-2 mb-4">
                <p class="text-gray-600"><i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>${job.location}</p>
                <p class="text-gray-600"><i class="fas fa-dollar-sign text-blue-600 mr-2"></i>${job.salary_range || 'Competitive'}</p>
                <p class="text-gray-600"><i class="fas fa-calendar text-blue-600 mr-2"></i>${formatDate(job.posted_date)}</p>
            </div>
            <p class="text-gray-700 mb-4 line-clamp-2">${job.description.substring(0, 100)}...</p>
            <button onclick="showJobDetails(${job.id})" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                <i class="fas fa-eye mr-2"></i>View & Apply
            </button>
        </div>
    `).join('');
}

function searchAvailableJobs() {
    loadAvailableJobs();
}

// Job Details Modal
async function showJobDetails(jobId) {
    currentJobId = jobId;
    
    try {
        const response = await fetch(API_BASE + `jobs/get.php?id=${jobId}`);
        const result = await response.json();
        
        if (result.success) {
            const job = result.data;
            
            document.getElementById('jobModalTitle').textContent = job.position;
            document.getElementById('jobModalCompany').textContent = job.company_name;
            document.getElementById('jobModalLocation').textContent = job.location;
            document.getElementById('jobModalType').textContent = job.job_type;
            document.getElementById('jobModalSalary').textContent = job.salary_range || 'Competitive';
            document.getElementById('jobModalPosted').textContent = 'Posted ' + formatDate(job.posted_date);
            document.getElementById('jobModalDescription').textContent = job.description;
            document.getElementById('jobModalRequirements').textContent = job.requirements;
            document.getElementById('jobModalContact').textContent = job.contact_person;
            document.getElementById('jobModalEmail').textContent = job.contact_email;
            document.getElementById('jobModalPhone').textContent = job.contact_phone;
            
            document.getElementById('jobDetailsModal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Failed to load job details:', error);
    }
}

function closeJobDetailsModal() {
    document.getElementById('jobDetailsModal').classList.add('hidden');
    currentJobId = null;
}

// Apply to Job
async function applyToSelectedJob() {
    if (!currentJobId) return;
    
    const notes = prompt('Add a note with your application (optional):');
    
    try {
        const response = await fetch(API_BASE + 'jobs/apply.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + authToken
            },
            body: JSON.stringify({
                job_posting_id: currentJobId,
                notes: notes || ''
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Application submitted successfully!');
            closeJobDetailsModal();
            loadAvailableJobs();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Failed to submit application. Please try again.');
    }
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('authMessage');
    messageDiv.textContent = message;
    messageDiv.className = `mt-4 text-center ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
}

// Dashboard Functions (My Applications)
async function loadDashboardData() {
    await loadStats();
    await loadApplications();
}

async function loadStats() {
    try {
        const response = await fetch(API_BASE + 'dashboard/stats.php', {
            headers: {'Authorization': 'Bearer ' + authToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            document.getElementById('totalApps').textContent = data.total;
            
            const statusCounts = {};
            data.statusCounts.forEach(item => {
                statusCounts[item.status] = item.count;
            });
            
            document.getElementById('appliedCount').textContent = statusCounts['Applied'] || 0;
            document.getElementById('interviewCount').textContent = statusCounts['Interview'] || 0;
            document.getElementById('offerCount').textContent = statusCounts['Offer'] || 0;
        }
    } catch (error) {
        console.error('Failed to load stats:', error);
    }
}

async function loadApplications() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    let url = API_BASE + 'applications/list.php?';
    if (status) url += `status=${status}&`;
    if (search) url += `search=${search}`;
    
    try {
        const response = await fetch(url, {
            headers: {'Authorization': 'Bearer ' + authToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            displayApplications(result.data);
        }
    } catch (error) {
        console.error('Failed to load applications:', error);
    }
}

function displayApplications(applications) {
    const tbody = document.getElementById('applicationsTable');
    
    if (applications.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No applications found</td></tr>';
        return;
    }
    
    tbody.innerHTML = applications.map(app => `
        <tr class="border-t hover:bg-gray-50">
            <td class="px-6 py-4">${app.company_name}</td>
            <td class="px-6 py-4">${app.position}</td>
            <td class="px-6 py-4">${app.date_applied}</td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-sm ${getStatusColor(app.status)}">
                    ${app.status}
                </span>
            </td>
            <td class="px-6 py-4">
                <button onclick="editApplication(${app.id})" class="text-blue-600 hover:text-blue-800 mr-3">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteApplication(${app.id})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function getStatusColor(status) {
    const colors = {
        'Applied': 'bg-yellow-100 text-yellow-800',
        'Shortlisted': 'bg-cyan-100 text-cyan-800',
        'Screening': 'bg-blue-100 text-blue-800',
        'Interview': 'bg-purple-100 text-purple-800',
        'Offer': 'bg-green-100 text-green-800',
        'Rejected': 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

// Modal Functions (Removed - no longer adding applications manually)
function showAddModal() {
    alert('To add an application, browse available jobs and click Apply!');
    showJobSeekerTab('browse');
}

function closeModal() {
    document.getElementById('applicationModal').classList.add('hidden');
}

// Note: Job seekers can no longer manually add applications
// They must apply through available jobs
async function handleSubmitApplication(e) {
    e.preventDefault();
    alert('Please use the "Browse Jobs" tab to apply to available positions.');
    closeModal();
}

async function editApplication(id) {
    try {
        const response = await fetch(API_BASE + `applications/list.php`, {
            headers: {'Authorization': 'Bearer ' + authToken}
        });
        
        const result = await response.json();
        const app = result.data.find(a => a.id == id);
        
        if (app) {
            document.getElementById('modalTitle').textContent = 'Edit Application';
            document.getElementById('appId').value = app.id;
            document.getElementById('companyName').value = app.company_name;
            document.getElementById('position').value = app.position;
            document.getElementById('dateApplied').value = app.date_applied;
            document.getElementById('status').value = app.status;
            document.getElementById('jobLink').value = app.job_link || '';
            document.getElementById('contactPerson').value = app.contact_person || '';
            document.getElementById('contactEmail').value = app.contact_email || '';
            document.getElementById('notes').value = app.notes || '';
            document.getElementById('applicationModal').classList.remove('hidden');
        }
    } catch (error) {
        alert('Failed to load application');
    }
}

async function deleteApplication(id) {
    if (!confirm('Are you sure you want to delete this application?')) return;
    
    try {
        const response = await fetch(API_BASE + `applications/delete.php?id=${id}`, {
            method: 'DELETE',
            headers: {'Authorization': 'Bearer ' + authToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadDashboardData();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Failed to delete application');
    }
}

// Search functionality
let searchTimeout;
document.getElementById('searchInput')?.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadApplications(), 500);
});

document.getElementById('jobSearchKeyword')?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') searchAvailableJobs();
});

// Utility Functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}
