// Asset Management System - Main Application
class AssetManagementSystem {
    constructor() {
        this.users = JSON.parse(localStorage.getItem('users')) || [];
        this.assets = JSON.parse(localStorage.getItem('assets')) || [];
        this.transactions = JSON.parse(localStorage.getItem('transactions')) || [];
        this.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkAuthStatus();
        this.loadDashboardData();
    }

    setupEventListeners() {
        // Global event listeners
        document.addEventListener('DOMContentLoaded', () => {
            this.handleNavigation();
            this.setupFormSubmissions();
        });
    }

    handleNavigation() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    }

    setupFormSubmissions() {
        // Login form
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }

        // Registration form
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegistration(e));
        }

        // Asset scan form
        const scanForm = document.getElementById('scanForm');
        if (scanForm) {
            scanForm.addEventListener('submit', (e) => this.handleAssetScan(e));
        }

        // Add asset form
        const addAssetForm = document.getElementById('addAssetForm');
        if (addAssetForm) {
            addAssetForm.addEventListener('submit', (e) => this.handleAddAsset(e));
        }

        // Logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => this.logout());
        }
    }

    // User Management
    handleRegistration(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const userData = {
            id: Date.now(),
            username: formData.get('username'),
            password: formData.get('password'),
            confirmPassword: formData.get('confirmPassword'),
            surname: formData.get('surname'),
            firstname: formData.get('firstname'),
            employeeNo: formData.get('employeeNo'),
            gender: formData.get('gender'),
            phoneNumber: formData.get('phoneNumber'),
            email: formData.get('email'),
            role: 'user',
            createdAt: new Date().toISOString()
        };

        // Validation
        if (userData.password !== userData.confirmPassword) {
            this.showNotification('Passwords do not match', 'error');
            return;
        }

        if (this.users.find(user => user.username === userData.username)) {
            this.showNotification('Username already exists', 'error');
            return;
        }

        // Hash password (simple hash for demo)
        userData.password = this.hashPassword(userData.password);
        delete userData.confirmPassword;

        this.users.push(userData);
        this.saveUsers();
        
        this.showNotification('Registration successful! Please login.', 'success');
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 2000);
    }

    handleLogin(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const username = formData.get('username');
        const password = this.hashPassword(formData.get('password'));

        const user = this.users.find(u => u.username === username && u.password === password);
        
        if (user) {
            this.currentUser = user;
            localStorage.setItem('currentUser', JSON.stringify(user));
            this.showNotification('Login successful!', 'success');
            setTimeout(() => {
                window.location.href = 'dashboard.html';
            }, 1000);
        } else {
            this.showNotification('Invalid username or password', 'error');
        }
    }

    logout() {
        this.currentUser = null;
        localStorage.removeItem('currentUser');
        this.showNotification('Logged out successfully', 'success');
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1000);
    }

    // Asset Management
    handleAddAsset(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const assetData = {
            id: Date.now(),
            assetNumber: formData.get('assetNumber'),
            type: formData.get('assetType'),
            description: formData.get('description'),
            assignedTo: formData.get('assignedTo') || null,
            status: 'available',
            location: formData.get('location'),
            purchaseDate: formData.get('purchaseDate'),
            createdAt: new Date().toISOString(),
            createdBy: this.currentUser?.id
        };

        this.assets.push(assetData);
        this.saveAssets();
        
        this.showNotification('Asset added successfully!', 'success');
        e.target.reset();
        this.loadDashboardData();
    }

    handleAssetScan(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const assetNumber = formData.get('assetNumber');
        const action = formData.get('action');
        
        const asset = this.assets.find(a => a.assetNumber === assetNumber);
        
        if (!asset) {
            this.showNotification('Asset not found', 'error');
            return;
        }

        const transaction = {
            id: Date.now(),
            assetId: asset.id,
            assetNumber: asset.assetNumber,
            action: action,
            userId: this.currentUser?.id,
            userName: this.currentUser?.username,
            timestamp: new Date().toISOString(),
            location: formData.get('location') || 'Main Office'
        };

        // Update asset status
        if (action === 'in') {
            asset.status = 'available';
            asset.assignedTo = null;
        } else if (action === 'out') {
            asset.status = 'checked_out';
            asset.assignedTo = this.currentUser?.id;
        }

        this.transactions.push(transaction);
        this.saveTransactions();
        this.saveAssets();
        
        this.showNotification(`Asset ${action === 'in' ? 'checked in' : 'checked out'} successfully!`, 'success');
        e.target.reset();
        this.loadDashboardData();
    }

    // Data Management
    saveUsers() {
        localStorage.setItem('users', JSON.stringify(this.users));
    }

    saveAssets() {
        localStorage.setItem('assets', JSON.stringify(this.assets));
    }

    saveTransactions() {
        localStorage.setItem('transactions', JSON.stringify(this.transactions));
    }

    // Dashboard
    loadDashboardData() {
        if (!this.currentUser) return;

        const dashboard = document.getElementById('dashboard');
        if (!dashboard) return;

        const stats = this.getDashboardStats();
        this.updateDashboardStats(stats);
        this.loadRecentTransactions();
        this.loadAssetList();
    }

    getDashboardStats() {
        const totalAssets = this.assets.length;
        const availableAssets = this.assets.filter(a => a.status === 'available').length;
        const checkedOutAssets = this.assets.filter(a => a.status === 'checked_out').length;
        const totalTransactions = this.transactions.length;
        const todayTransactions = this.transactions.filter(t => {
            const today = new Date().toDateString();
            return new Date(t.timestamp).toDateString() === today;
        }).length;

        return {
            totalAssets,
            availableAssets,
            checkedOutAssets,
            totalTransactions,
            todayTransactions
        };
    }

    updateDashboardStats(stats) {
        const statsContainer = document.getElementById('dashboardStats');
        if (!statsContainer) return;

        statsContainer.innerHTML = `
            <div class="dashboard-card">
                <h3>Total Assets</h3>
                <p class="stat-number">${stats.totalAssets}</p>
            </div>
            <div class="dashboard-card">
                <h3>Available Assets</h3>
                <p class="stat-number">${stats.availableAssets}</p>
            </div>
            <div class="dashboard-card">
                <h3>Checked Out</h3>
                <p class="stat-number">${stats.checkedOutAssets}</p>
            </div>
            <div class="dashboard-card">
                <h3>Today's Transactions</h3>
                <p class="stat-number">${stats.todayTransactions}</p>
            </div>
        `;
    }

    loadRecentTransactions() {
        const container = document.getElementById('recentTransactions');
        if (!container) return;

        const recentTransactions = this.transactions
            .sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp))
            .slice(0, 10);

        if (recentTransactions.length === 0) {
            container.innerHTML = '<p>No transactions found</p>';
            return;
        }

        const tableHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Action</th>
                        <th>User</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    ${recentTransactions.map(t => `
                        <tr>
                            <td>${t.assetNumber}</td>
                            <td><span class="badge ${t.action === 'in' ? 'success' : 'warning'}">${t.action.toUpperCase()}</span></td>
                            <td>${t.userName}</td>
                            <td>${new Date(t.timestamp).toLocaleDateString()}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        container.innerHTML = tableHTML;
    }

    loadAssetList() {
        const container = document.getElementById('assetList');
        if (!container) return;

        if (this.assets.length === 0) {
            container.innerHTML = '<p>No assets found</p>';
            return;
        }

        const tableHTML = `
            <table>
                <thead>
                    <tr>
                        <th>Asset Number</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${this.assets.map(asset => `
                        <tr>
                            <td>${asset.assetNumber}</td>
                            <td>${asset.type}</td>
                            <td><span class="badge ${asset.status === 'available' ? 'success' : 'warning'}">${asset.status}</span></td>
                            <td>${asset.location}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="app.scanAsset('${asset.assetNumber}')">Scan</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;

        container.innerHTML = tableHTML;
    }

    // Utility functions
    hashPassword(password) {
        // Simple hash for demo purposes
        return btoa(password);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    checkAuthStatus() {
        if (this.currentUser) {
            // User is logged in
            const loginLink = document.querySelector('a[href="login.html"]');
            const registerLink = document.querySelector('a[href="register.html"]');
            
            if (loginLink) loginLink.style.display = 'none';
            if (registerLink) registerLink.style.display = 'none';
        } else {
            // User is not logged in
            const dashboardLink = document.querySelector('a[href="dashboard.html"]');
            if (dashboardLink) dashboardLink.style.display = 'none';
        }
    }

    scanAsset(assetNumber) {
        // Pre-fill scan form
        const assetNumberInput = document.getElementById('assetNumber');
        if (assetNumberInput) {
            assetNumberInput.value = assetNumber;
        }
        
        // Navigate to scan page
        window.location.href = 'scan.html';
    }
}

// Initialize the application
const app = new AssetManagementSystem();

// Global functions for HTML onclick handlers
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const eye = input.nextElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.textContent = '🙈';
    } else {
        input.type = 'password';
        eye.textContent = '👁';
    }
}