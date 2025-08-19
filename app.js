// Asset Management System - Main Application
class AssetManagementSystem {
    constructor() {
        this.users = JSON.parse(localStorage.getItem('users')) || [];
        this.assets = JSON.parse(localStorage.getItem('assets')) || [];
        this.transactions = JSON.parse(localStorage.getItem('transactions')) || [];
        this.logs = JSON.parse(localStorage.getItem('logs')) || [];
        this.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
        
        this.ensureSeedUsers();
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

        // Change Password form
        const changePasswordForm = document.getElementById('changePasswordForm');
        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', (e) => this.handleChangePassword(e));
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

        // Issue Asset form
        const issueAssetForm = document.getElementById('issueAssetForm');
        if (issueAssetForm) {
            issueAssetForm.addEventListener('submit', (e) => this.handleIssueAsset(e));
        }

        // Loan Asset form
        const loanAssetForm = document.getElementById('loanAssetForm');
        if (loanAssetForm) {
            loanAssetForm.addEventListener('submit', (e) => this.handleLoanAsset(e));
        }

        // Return Loan form
        const returnAssetForm = document.getElementById('returnAssetForm');
        if (returnAssetForm) {
            returnAssetForm.addEventListener('submit', (e) => this.handleReturnLoan(e));
        }

        // Logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => this.logout());
        }
    }

    // User Management
    ensureSeedUsers() {
        const hasSeedFlag = localStorage.getItem('seededDefaultUsers');
        if (this.users.length === 0 || !hasSeedFlag) {
            const defaultUsers = [
                {
                    id: 1,
                    username: 'louwS@namwater.com.na',
                    password: this.hashPassword('ICT12345678'),
                    firstname: 'Shadie',
                    surname: 'Louw',
                    email: 'louwS@namwater.com.na',
                    role: 'admin',
                    createdAt: new Date().toISOString()
                },
                {
                    id: 2,
                    username: 'tjivikuaM@namwater.com.na',
                    password: this.hashPassword('ICT87654321'),
                    firstname: 'Tjivikua',
                    surname: 'Mc-Claude',
                    email: 'tjivikuaM@namwater.com.na',
                    role: 'admin',
                    createdAt: new Date().toISOString()
                },
                {
                    id: 3,
                    username: 'AssetmanagementAD',
                    password: this.hashPassword('NamwaterassetM@654321'),
                    firstname: 'Asset',
                    surname: 'Management',
                    email: 'assetmanagement@namwater.com.na',
                    role: 'admin',
                    createdAt: new Date().toISOString()
                }
            ];

            // Preserve any existing users that match allowed usernames, otherwise reset to defaults
            const allowedUsernames = new Set(defaultUsers.map(u => u.username));
            const existingAllowed = this.users.filter(u => allowedUsernames.has(u.username));
            if (existingAllowed.length > 0) {
                // Merge: keep existing passwords but ensure names/emails/roles exist
                const merged = defaultUsers.map(def => {
                    const existing = existingAllowed.find(u => u.username === def.username);
                    return existing ? { ...def, ...existing } : def;
                });
                this.users = merged;
            } else {
                this.users = defaultUsers;
            }
            this.saveUsers();
            localStorage.setItem('seededDefaultUsers', 'true');
        }
    }

    handleLogin(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const username = formData.get('username');
        const password = this.hashPassword(formData.get('password'));

        // Restrict to allowed usernames only
        const allowedUsernames = [
            'louwS@namwater.com.na',
            'tjivikuaM@namwater.com.na',
            'AssetmanagementAD'
        ];
        const user = this.users.find(u => allowedUsernames.includes(u.username) && u.username === username && u.password === password);
        
        if (user) {
            this.currentUser = user;
            localStorage.setItem('currentUser', JSON.stringify(user));
            this.showNotification('Login successful!', 'success');
            this.addLog('login', `User ${user.username} logged in`);
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
            assetNumber: this.normalizeAssetNumber(formData.get('assetNumber')),
            type: formData.get('assetType'),
            description: formData.get('description'),
            assignedTo: formData.get('assignedTo') || null,
            status: 'available',
            location: formData.get('location'),
            purchaseDate: formData.get('purchaseDate'),
            serialNumber: formData.get('serialNumber') || '',
            moduleOrMac: formData.get('moduleOrMac') || '',
            createdAt: new Date().toISOString(),
            createdBy: this.currentUser?.id
        };

        this.assets.push(assetData);
        this.saveAssets();
        this.addLog('asset_add', `Asset ${assetData.assetNumber} added by ${this.currentUser?.username || 'system'}`);
        
        this.showNotification('Asset added successfully!', 'success');
        e.target.reset();
        this.loadDashboardData();
    }

    handleAssetScan(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const assetNumber = this.normalizeAssetNumber(formData.get('assetNumber'));
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
        this.addLog('asset_scan', `Asset ${asset.assetNumber} ${action}`);
        
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

    saveLogs() {
        localStorage.setItem('logs', JSON.stringify(this.logs));
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

        // Update welcome message with user name
        const welcomeName = document.getElementById('welcomeName');
        if (welcomeName) {
            const displayName = this.currentUser.firstname || this.currentUser.surname ? `${this.currentUser.firstname || ''} ${this.currentUser.surname || ''}`.trim() : this.currentUser.username;
            welcomeName.textContent = displayName;
        }
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

    normalizeAssetNumber(raw) {
        if (!raw) return '';
        const trimmed = String(raw).trim();
        return trimmed.startsWith('NWC') ? trimmed : `NWC${trimmed}`;
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
            
            if (loginLink) loginLink.style.display = 'none';
            // Hide register links anywhere
            document.querySelectorAll('a[href="register.html"]').forEach(a => a.style.display = 'none');
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

    // Change password
    handleChangePassword(e) {
        e.preventDefault();
        if (!this.currentUser) return;
        const formData = new FormData(e.target);
        const currentPassword = this.hashPassword(formData.get('currentPassword'));
        const newPassword = formData.get('newPassword');
        const confirmPassword = formData.get('confirmPassword');

        if (this.currentUser.password !== currentPassword) {
            this.showNotification('Current password is incorrect', 'error');
            return;
        }
        if (!newPassword || newPassword !== confirmPassword) {
            this.showNotification('New passwords do not match', 'error');
            return;
        }

        const users = this.users.map(u => {
            if (u.id === this.currentUser.id) {
                return { ...u, password: this.hashPassword(newPassword) };
            }
            return u;
        });
        this.users = users;
        this.saveUsers();

        // Update current user in storage
        const updatedCurrent = this.users.find(u => u.id === this.currentUser.id);
        this.currentUser = updatedCurrent;
        localStorage.setItem('currentUser', JSON.stringify(this.currentUser));
        this.addLog('password_change', `User ${this.currentUser.username} changed password`);
        this.showNotification('Password changed successfully', 'success');
        e.target.reset();
    }

    // Issue asset to user
    handleIssueAsset(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const assetNumberRaw = formData.get('assetNumber');
        const assetNumber = this.normalizeAssetNumber(assetNumberRaw);
        const issuedTo = formData.get('issuedTo');
        const issuedBy = this.currentUser?.username || 'system';
        const location = formData.get('location');
        const condition = formData.get('condition');
        const reason = formData.get('reason');
        const issuedAt = formData.get('issuedAt') || new Date().toISOString();

        const asset = this.assets.find(a => a.assetNumber === assetNumber);
        if (!asset) {
            this.showNotification('Asset not found', 'error');
            return;
        }

        asset.status = 'issued';
        asset.assignedTo = Number(issuedTo) || issuedTo || null;
        this.saveAssets();

        const transaction = {
            id: Date.now(),
            assetId: asset.id,
            assetNumber: asset.assetNumber,
            action: 'issued',
            userId: this.currentUser?.id,
            userName: issuedBy,
            timestamp: issuedAt,
            location,
            meta: { condition, reason, issuedTo }
        };
        this.transactions.push(transaction);
        this.saveTransactions();
        this.addLog('asset_issue', `Asset ${asset.assetNumber} issued to ${issuedTo}`);
        this.showNotification('Asset issued successfully', 'success');
        e.target.reset();
    }

    // Loan / Borrow asset
    handleLoanAsset(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const assetNumber = this.normalizeAssetNumber(formData.get('assetNumber'));
        const deviceName = formData.get('deviceName');
        const serialNumber = formData.get('serialNumber');
        const location = formData.get('location');
        const condition = formData.get('condition');
        const issuedTo = formData.get('issuedTo');
        const issuedBy = this.currentUser?.username || 'system';
        const reason = formData.get('reason');
        const issuedAt = formData.get('issuedAt') || new Date().toISOString();
        const until = formData.get('until');

        const asset = this.assets.find(a => a.assetNumber === assetNumber);
        if (!asset) {
            this.showNotification('Asset not found', 'error');
            return;
        }

        asset.status = 'loaned';
        asset.assignedTo = Number(issuedTo) || issuedTo || null;
        this.saveAssets();

        const transaction = {
            id: Date.now(),
            assetId: asset.id,
            assetNumber: asset.assetNumber,
            action: 'loaned',
            userId: this.currentUser?.id,
            userName: issuedBy,
            timestamp: issuedAt,
            location,
            meta: { deviceName, serialNumber, condition, issuedTo, reason, until }
        };
        this.transactions.push(transaction);
        this.saveTransactions();
        this.addLog('asset_loan', `Asset ${asset.assetNumber} loaned to ${issuedTo}`);
        this.showNotification('Asset loan recorded successfully', 'success');
        e.target.reset();
    }

    // Return loaned/borrowed asset
    handleReturnLoan(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const assetNumber = this.normalizeAssetNumber(formData.get('assetNumber'));
        const returnedAt = formData.get('returnedAt') || new Date().toISOString();
        const condition = formData.get('returnCondition');

        const asset = this.assets.find(a => a.assetNumber === assetNumber);
        if (!asset) {
            this.showNotification('Asset not found', 'error');
            return;
        }

        asset.status = 'available';
        asset.assignedTo = null;
        this.saveAssets();

        const transaction = {
            id: Date.now(),
            assetId: asset.id,
            assetNumber: asset.assetNumber,
            action: 'returned',
            userId: this.currentUser?.id,
            userName: this.currentUser?.username,
            timestamp: returnedAt,
            location: asset.location,
            meta: { condition }
        };
        this.transactions.push(transaction);
        this.saveTransactions();
        this.addLog('asset_return', `Asset ${asset.assetNumber} returned`);
        this.showNotification('Asset returned successfully', 'success');
        e.target.reset();
    }

    // Logs
    addLog(action, message) {
        const logEntry = {
            id: Date.now(),
            action,
            message,
            user: this.currentUser?.username || 'system',
            timestamp: new Date().toISOString()
        };
        this.logs.push(logEntry);
        this.saveLogs();
    }

    // Mock AD search (replace with real AD integration as needed)
    searchActiveDirectory(query) {
        const directory = [
            { id: 101, name: 'Shadie Louw', email: 'louwS@namwater.com.na' },
            { id: 102, name: 'Tjivikua Mc-Claude', email: 'tjivikuaM@namwater.com.na' },
            { id: 103, name: 'Asset Management', email: 'assetmanagement@namwater.com.na' },
        ];
        const term = String(query || '').toLowerCase();
        return directory.filter(e => e.name.toLowerCase().includes(term) || e.email.toLowerCase().includes(term));
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