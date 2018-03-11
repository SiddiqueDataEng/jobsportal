const API_BASE = 'api/';
let authToken = localStorage.getItem('authToken');
let currentUser = JSON.parse(localStorage.getItem('currentUser') || 'null');
let currentJobId = null;
let allJobs = [];

// Check authentication on load
window.addEventListener('DOMContentLoaded', () => {
    if (authToken && currentUser) {
        showUserNav();
    }
    loadJobs();
});

function showUserNav() {
    document.getElementById('guestNav').classList.add('hidden');
    document.getElementById('userNav').classList.remove('hidden');
    document.getElementById('userName').textContent = currentUser.full_name;
}

function showGuestNav() {
    document.getElementById('guestNav').classList.remove('hidden');
    document.getElementById('userNav').classList.add('hidden');
}

// Load Jobs
async function loadJobs() {
    const search = document.getElementById('searchKeyword').value;
    const jobType = document.getElementById('filterJobType').value;
    
    let url = API_BASE + 'jobs/list.php?status=Active';
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (jobType) url += `&job_type=${encodeURIComponent(jobType)}`;
    
    try {
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success) {
            allJobs = result.data;
            displayJobs(result.data);
        }
    } catch (error) {
        console.error('Failed to load jobs:', error);
    }
}

function displayJobs(jobs) {
    const container = document.getElementById('jobListings');
    const noJobs = document.getElementById('noJobs');
    const jobCount = document.getElementById('jobCount');
    
    jobCount.textContent = `${jobs.length} jobs found`;
    
    if (jobs.length === 0) {
        container.classList.add('hidden');
        noJobs.classList.remove('hidden');
        return;
    }
    
    container.classList.remove('hidden');
    noJobs.classList.add('hidden');
    
    container.innerHTML = jobs.map(job => `
        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-6 cursor-pointer" onclick="showJobDetails(${job.id})">
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
                <p class="text-gray-600">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>${job.location}
                </p>
                <p class="text-gray-600">
                    <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>${job.salary_range || 'Competitive'}
                </p>
                <p class="text-gray-600">
                    <i class="fas fa-calendar text-blue-600 mr-2"></i>Posted: ${formatDate(job.posted_date)}
                </p>
            </div>
            
            <p class="text-gray-700 mb-4 line-clamp-3">${job.description.substring(0, 150)}...</p>
            
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">
                    <i class="fas fa-users mr-1"></i>${job.application_count || 0} applicants
                </span>
                <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    View Details
                </button>
            </div>
        </div>
    `).join('');
}

function searchJobs() {
    loadJobs();
}

// Job Details Modal
async function showJobDetails(jobId) {
    currentJobId = jobId;
    
    try {
        const response = await fetch(API_BASE + `jobs/get.php?id=${jobId}`);
        const result = await response.json();
        
        if (result.success) {
            const job = result.data;
            
            document.getElementById('modalJobTitle').textContent = job.position;
            document.getElementById('modalCompany').textContent = job.company_name;
            document.getElementById('modalLocation').textContent = job.location;
            document.getElementById('modalJobType').textContent = job.job_type;
            document.getElementById('modalSalary').textContent = job.salary_range || 'Competitive';
            document.getElementById('modalPosted').textContent = 'Posted ' + formatDate(job.posted_date);
            document.getElementById('modalDescription').textContent = job.description;
            document.getElementById('modalRequirements').textContent = job.requirements;
            document.getElementById('modalContact').textContent = job.contact_person;
            document.getElementById('modalEmail').textContent = job.contact_email;
            document.getElementById('modalPhone').textContent = job.contact_phone;
            
            document.getElementById('jobModal').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Failed to load job details:', error);
    }
}

function closeJobModal() {
    document.getElementById('jobModal').classList.add('hidden');
    currentJobId = null;
}

// Apply to Job
async function applyToJob() {
    if (!authToken) {
        closeJobModal();
        showLoginModal();
        alert('Please login to apply for jobs');
        return;
    }
    
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
            alert('Application submitted successfully! You can track your application in the dashboard.');
            closeJobModal();
            loadJobs(); // Refresh to update applicant count
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert('Failed to submit application. Please try again.');
    }
}

// Authentication
function showLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
}

function showRegisterModal() {
    document.getElementById('registerModal').classList.remove('hidden');
}

function closeModals() {
    document.getElementById('loginModal').classList.add('hidden');
    document.getElementById('registerModal').classList.add('hidden');
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
            showMessage('loginMessage', 'Login successful!', 'success');
            setTimeout(() => {
                closeModals();
                showUserNav();
            }, 1000);
        } else {
            showMessage('loginMessage', data.message, 'error');
        }
    } catch (error) {
        showMessage('loginMessage', 'Login failed. Please try again.', 'error');
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
            showMessage('registerMessage', 'Registration successful! Please login.', 'success');
            setTimeout(() => {
                closeModals();
                showLoginModal();
            }, 2000);
        } else {
            showMessage('registerMessage', data.message, 'error');
        }
    } catch (error) {
        showMessage('registerMessage', 'Registration failed. Please try again.', 'error');
    }
}

function handleLogout() {
    localStorage.removeItem('authToken');
    localStorage.removeItem('currentUser');
    authToken = null;
    currentUser = null;
    showGuestNav();
    alert('Logged out successfully');
}

function showDashboard() {
    window.location.href = 'index.html';
}

function showMessage(elementId, message, type) {
    const messageDiv = document.getElementById(elementId);
    messageDiv.textContent = message;
    messageDiv.className = `mt-4 text-center ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

// Search on Enter key
document.getElementById('searchKeyword')?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') searchJobs();
});
