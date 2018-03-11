const API_BASE = 'api/';
let adminToken = localStorage.getItem('adminToken');
let currentAdmin = JSON.parse(localStorage.getItem('currentAdmin') || 'null');

// Check authentication on load
window.addEventListener('DOMContentLoaded', () => {
    if (adminToken && currentAdmin) {
        showAdminDashboard();
        loadAdminData();
    }
});

// Admin Login
async function handleAdminLogin(e) {
    e.preventDefault();
    const email = document.getElementById('adminEmail').value;
    const password = document.getElementById('adminPassword').value;
    
    try {
        const response = await fetch(API_BASE + 'auth/login.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({email, password})
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Check if user is admin
            if (email.includes('admin')) {
                adminToken = data.token;
                currentAdmin = data.user;
                localStorage.setItem('adminToken', adminToken);
                localStorage.setItem('currentAdmin', JSON.stringify(currentAdmin));
                showAdminMessage('Login successful!', 'success');
                setTimeout(() => {
                    showAdminDashboard();
                    loadAdminData();
                }, 1000);
            } else {
                showAdminMessage('Access denied. Admin credentials required.', 'error');
            }
        } else {
            showAdminMessage(data.message, 'error');
        }
    } catch (error) {
        showAdminMessage('Login failed. Please try again.', 'error');
    }
}

function handleAdminLogout() {
    localStorage.removeItem('adminToken');
    localStorage.removeItem('currentAdmin');
    adminToken = null;
    currentAdmin = null;
    document.getElementById('adminDashboard').classList.add('hidden');
    document.getElementById('adminLoginPage').classList.remove('hidden');
}

function showAdminDashboard() {
    document.getElementById('adminLoginPage').classList.add('hidden');
    document.getElementById('adminDashboard').classList.remove('hidden');
    document.getElementById('adminName').textContent = currentAdmin.full_name;
}

function showAdminMessage(message, type) {
    const messageDiv = document.getElementById('adminLoginMessage');
    messageDiv.textContent = message;
    messageDiv.className = `mt-4 text-center ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
}

// Tab Management
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    
    // Remove active state from all tab buttons
    document.querySelectorAll('[id$="Tab"]').forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('text-gray-600');
    });
    
    // Show selected tab
    document.getElementById(tabName + 'Content').classList.remove('hidden');
    
    // Add active state to selected tab button
    const activeTab = document.getElementById(tabName + 'Tab');
    activeTab.classList.add('border-blue-600', 'text-blue-600');
    activeTab.classList.remove('text-gray-600');
    
    // Load data for specific tabs
    if (tabName === 'jobs') loadJobPostings();
    if (tabName === 'users') loadUsers();
    if (tabName === 'applications') loadAllApplications();
    if (tabName === 'analytics') loadAnalytics();
}

// Load Admin Data
async function loadAdminData() {
    await loadOverviewStats();
    await loadRecentUsers();
    await loadStatusChart();
}

async function loadOverviewStats() {
    try {
        const response = await fetch(API_BASE + 'admin/stats.php', {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            document.getElementById('totalUsers').textContent = data.totalUsers || 0;
            document.getElementById('totalApplications').textContent = data.totalApplications || 0;
            document.getElementById('activeToday').textContent = data.activeToday || 0;
            document.getElementById('successRate').textContent = (data.successRate || 0) + '%';
        }
        
        // Load active jobs count
        const jobsResponse = await fetch(API_BASE + 'jobs/list.php?status=Active');
        const jobsResult = await jobsResponse.json();
        if (jobsResult.success) {
            document.getElementById('activeJobs').textContent = jobsResult.data.length;
        }
    } catch (error) {
        console.error('Failed to load stats:', error);
    }
}

async function loadRecentUsers() {
    try {
        const response = await fetch(API_BASE + 'admin/users.php?limit=5', {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            const container = document.getElementById('recentUsers');
            container.innerHTML = result.data.map(user => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            ${user.full_name.charAt(0)}
                        </div>
                        <div>
                            <p class="font-semibold">${user.full_name}</p>
                            <p class="text-sm text-gray-600">${user.email}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">${formatDate(user.created_at)}</span>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Failed to load recent users:', error);
    }
}

async function loadStatusChart() {
    try {
        const response = await fetch(API_BASE + 'admin/stats.php', {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success && result.data.statusCounts) {
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: result.data.statusCounts.map(s => s.status),
                    datasets: [{
                        data: result.data.statusCounts.map(s => s.count),
                        backgroundColor: [
                            '#FCD34D', // Applied - Yellow
                            '#60A5FA', // Screening - Blue
                            '#A78BFA', // Interview - Purple
                            '#34D399', // Offer - Green
                            '#F87171'  // Rejected - Red
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Failed to load status chart:', error);
    }
}

async function loadUsers() {
    try {
        const response = await fetch(API_BASE + 'admin/users.php', {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            const tbody = document.getElementById('usersTable');
            tbody.innerHTML = result.data.map(user => `
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">${user.id}</td>
                    <td class="px-6 py-4 font-semibold">${user.full_name}</td>
                    <td class="px-6 py-4">${user.email}</td>
                    <td class="px-6 py-4">${user.phone || 'N/A'}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            ${user.application_count || 0}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">${formatDate(user.created_at)}</td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Failed to load users:', error);
    }
}

async function loadAllApplications() {
    const status = document.getElementById('adminStatusFilter').value;
    let url = API_BASE + 'admin/applications.php';
    if (status) url += `?status=${status}`;
    
    try {
        const response = await fetch(url, {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            const tbody = document.getElementById('allApplicationsTable');
            tbody.innerHTML = result.data.map(app => `
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold">${app.user_name}</td>
                    <td class="px-6 py-4">${app.company_name}</td>
                    <td class="px-6 py-4">${app.position}</td>
                    <td class="px-6 py-4">${app.date_applied}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm ${getStatusColor(app.status)}">
                            ${app.status}
                        </span>
                    </td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Failed to load applications:', error);
    }
}

async function loadAnalytics() {
    try {
        const response = await fetch(API_BASE + 'admin/analytics.php', {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Company Chart
            if (result.data.byCompany) {
                const ctx1 = document.getElementById('companyChart').getContext('2d');
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: result.data.byCompany.map(c => c.company_name),
                        datasets: [{
                            label: 'Applications',
                            data: result.data.byCompany.map(c => c.count),
                            backgroundColor: '#3B82F6'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
            
            // User Activity Chart
            if (result.data.byUser) {
                const ctx2 = document.getElementById('userActivityChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: result.data.byUser.map(u => u.full_name),
                        datasets: [{
                            label: 'Applications',
                            data: result.data.byUser.map(u => u.count),
                            backgroundColor: '#10B981'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y'
                    }
                });
            }
        }
    } catch (error) {
        console.error('Failed to load analytics:', error);
    }
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

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}


// Job Postings Management
async function loadJobPostings() {
    try {
        const response = await fetch(API_BASE + 'jobs/list.php?status=');
        const result = await response.json();
        
        if (result.success) {
            displayJobPostings(result.data);
        }
    } catch (error) {
        console.error('Failed to load job postings:', error);
    }
}

function displayJobPostings(jobs) {
    const tbody = document.getElementById('jobPostingsTable');
    
    if (jobs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">No job postings yet</td></tr>';
        return;
    }
    
    tbody.innerHTML = jobs.map(job => `
        <tr class="border-t hover:bg-gray-50">
            <td class="px-6 py-4 font-semibold">${job.company_name}</td>
            <td class="px-6 py-4">${job.position}</td>
            <td class="px-6 py-4">${job.location}</td>
            <td class="px-6 py-4">${job.job_type}</td>
            <td class="px-6 py-4">${formatDate(job.posted_date)}</td>
            <td class="px-6 py-4">
                <button onclick="viewApplicants(${job.id})" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition cursor-pointer">
                    <i class="fas fa-users mr-1"></i>${job.application_count || 0}
                </button>
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-sm ${getJobStatusColor(job.status)}">
                    ${job.status}
                </span>
            </td>
            <td class="px-6 py-4">
                <button onclick="editJob(${job.id})" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit Job">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteJob(${job.id})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function getJobStatusColor(status) {
    const colors = {
        'Active': 'bg-green-100 text-green-800',
        'Closed': 'bg-red-100 text-red-800',
        'Draft': 'bg-gray-100 text-gray-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function showPostJobModal() {
    document.getElementById('jobModalTitle').textContent = 'Post New Job';
    document.getElementById('postJobForm').reset();
    document.getElementById('jobId').value = '';
    document.getElementById('postJobModal').classList.remove('hidden');
}

function closePostJobModal() {
    document.getElementById('postJobModal').classList.add('hidden');
}

async function handlePostJob(e) {
    e.preventDefault();
    
    const jobId = document.getElementById('jobId').value;
    const data = {
        company_name: document.getElementById('jobCompany').value,
        position: document.getElementById('jobPosition').value,
        location: document.getElementById('jobLocation').value,
        job_type: document.getElementById('jobType').value,
        salary_range: document.getElementById('jobSalary').value,
        job_link: document.getElementById('jobLink').value,
        description: document.getElementById('jobDescription').value,
        requirements: document.getElementById('jobRequirements').value,
        contact_person: document.getElementById('jobContact').value,
        contact_email: document.getElementById('jobEmail').value,
        contact_phone: document.getElementById('jobPhone').value,
        status: document.getElementById('jobStatus').value,
        posted_date: new Date().toISOString().split('T')[0],
        deadline: document.getElementById('jobDeadline').value
    };
    
    const url = jobId ? API_BASE + 'jobs/update.php' : API_BASE + 'jobs/create.php';
    const method = jobId ? 'PUT' : 'POST';
    
    if (jobId) data.id = jobId;
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + adminToken
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(jobId ? 'Job updated successfully!' : 'Job posted successfully!');
            closePostJobModal();
            loadJobPostings();
            loadOverviewStats();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Failed to save job. Please try again.');
    }
}

async function editJob(jobId) {
    try {
        const response = await fetch(API_BASE + `jobs/get.php?id=${jobId}`);
        const result = await response.json();
        
        if (result.success) {
            const job = result.data;
            
            document.getElementById('jobModalTitle').textContent = 'Edit Job';
            document.getElementById('jobId').value = job.id;
            document.getElementById('jobCompany').value = job.company_name;
            document.getElementById('jobPosition').value = job.position;
            document.getElementById('jobLocation').value = job.location;
            document.getElementById('jobType').value = job.job_type;
            document.getElementById('jobSalary').value = job.salary_range || '';
            document.getElementById('jobLink').value = job.job_link || '';
            document.getElementById('jobDescription').value = job.description;
            document.getElementById('jobRequirements').value = job.requirements;
            document.getElementById('jobContact').value = job.contact_person;
            document.getElementById('jobEmail').value = job.contact_email;
            document.getElementById('jobPhone').value = job.contact_phone || '';
            document.getElementById('jobStatus').value = job.status;
            document.getElementById('jobDeadline').value = job.deadline || '';
            
            document.getElementById('postJobModal').classList.remove('hidden');
        }
    } catch (error) {
        alert('Failed to load job details');
    }
}

async function deleteJob(jobId) {
    if (!confirm('Are you sure you want to delete this job posting? All applications will remain but won\'t be linked to this job.')) return;
    
    try {
        const response = await fetch(API_BASE + `jobs/delete.php?id=${jobId}`, {
            method: 'DELETE',
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Job deleted successfully!');
            loadJobPostings();
            loadOverviewStats();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Failed to delete job');
    }
}


// View Applicants for a Job
async function viewApplicants(jobId) {
    try {
        const response = await fetch(API_BASE + `jobs/applicants.php?job_id=${jobId}`, {
            headers: {'Authorization': 'Bearer ' + adminToken}
        });
        
        const result = await response.json();
        
        if (result.success) {
            displayApplicantsModal(result);
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Failed to load applicants:', error);
        alert('Failed to load applicants');
    }
}

function displayApplicantsModal(data) {
    const { job, applicants, stats } = data;
    
    // Set job details
    document.getElementById('applicantsJobTitle').textContent = job.position;
    document.getElementById('applicantsJobCompany').textContent = job.company_name + ' - ' + job.location;
    
    // Set statistics
    document.getElementById('statTotal').textContent = stats.total;
    document.getElementById('statApplied').textContent = stats.applied;
    document.getElementById('statShortlisted').textContent = stats.shortlisted || 0;
    document.getElementById('statScreening').textContent = stats.screening;
    document.getElementById('statInterview').textContent = stats.interview;
    document.getElementById('statOffer').textContent = stats.offer;
    document.getElementById('statRejected').textContent = stats.rejected;
    
    // Display applicants table
    const tbody = document.getElementById('applicantsTableBody');
    
    if (applicants.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No applicants yet</td></tr>';
    } else {
        tbody.innerHTML = applicants.map(app => `
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            ${app.full_name.charAt(0)}
                        </div>
                        <span class="font-semibold">${app.full_name}</span>
                    </div>
                </td>
                <td class="px-4 py-3">${app.email}</td>
                <td class="px-4 py-3">${app.phone || 'N/A'}</td>
                <td class="px-4 py-3">${formatDate(app.date_applied)}</td>
                <td class="px-4 py-3">
                    <select onchange="updateApplicationStatus(${app.id}, this.value)" 
                            class="px-3 py-1 rounded-full text-sm border-2 ${getStatusBorderColor(app.status)} ${getStatusColor(app.status)} font-semibold cursor-pointer">
                        <option value="Applied" ${app.status === 'Applied' ? 'selected' : ''}>Applied</option>
                        <option value="Shortlisted" ${app.status === 'Shortlisted' ? 'selected' : ''}>Shortlisted</option>
                        <option value="Screening" ${app.status === 'Screening' ? 'selected' : ''}>Screening</option>
                        <option value="Interview" ${app.status === 'Interview' ? 'selected' : ''}>Interview</option>
                        <option value="Offer" ${app.status === 'Offer' ? 'selected' : ''}>Offer</option>
                        <option value="Rejected" ${app.status === 'Rejected' ? 'selected' : ''}>Rejected</option>
                    </select>
                </td>
                <td class="px-4 py-3">
                    <button onclick="viewApplicantDetails(${app.id})" class="text-blue-600 hover:text-blue-800" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }
    
    // Show modal
    document.getElementById('applicantsModal').classList.remove('hidden');
}

function closeApplicantsModal() {
    document.getElementById('applicantsModal').classList.add('hidden');
}

function getStatusBorderColor(status) {
    const colors = {
        'Applied': 'border-yellow-400',
        'Shortlisted': 'border-cyan-400',
        'Screening': 'border-blue-400',
        'Interview': 'border-purple-400',
        'Offer': 'border-green-400',
        'Rejected': 'border-red-400'
    };
    return colors[status] || 'border-gray-400';
}

// Update Application Status
async function updateApplicationStatus(applicationId, newStatus) {
    if (!confirm(`Change status to "${newStatus}"?`)) {
        // Reload to reset the dropdown
        location.reload();
        return;
    }
    
    try {
        const response = await fetch(API_BASE + 'applications/update_status.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + adminToken
            },
            body: JSON.stringify({
                application_id: applicationId,
                status: newStatus
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message
            showNotification('Status updated successfully!', 'success');
            
            // Reload the applicants modal to refresh stats
            const jobId = new URLSearchParams(window.location.search).get('job_id');
            if (jobId) {
                viewApplicants(jobId);
            } else {
                // If we can't get job_id, just close and reload
                closeApplicantsModal();
                loadJobPostings();
            }
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error('Failed to update status:', error);
        alert('Failed to update status');
    }
}

function viewApplicantDetails(applicationId) {
    alert('Applicant details view - Coming soon!\nApplication ID: ' + applicationId);
    // TODO: Implement detailed view with notes, documents, etc.
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white font-semibold`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
