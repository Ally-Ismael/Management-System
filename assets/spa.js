(function(){
	'use strict';
	const appEl = document.getElementById('app');
	const navEl = document.getElementById('nav');

	// Storage helpers
	const store = {
		read(key, fallback){ try{ const v = localStorage.getItem(key); return v? JSON.parse(v): fallback; }catch(e){ return fallback; } },
		write(key, value){ localStorage.setItem(key, JSON.stringify(value)); }
	};

	// App state
	const state = {
		user: store.read('auth_user', null),
		laptopScans: store.read('laptop_scans', []),
		privateLaptopScans: store.read('private_laptop_scans', []),
		companyCarScans: store.read('company_car_scans', []),
		privateCarScans: store.read('private_car_scans', []),
		users: store.read('users', [])
	};

	function saveState(){
		store.write('auth_user', state.user);
		store.write('laptop_scans', state.laptopScans);
		store.write('private_laptop_scans', state.privateLaptopScans);
		store.write('company_car_scans', state.companyCarScans);
		store.write('private_car_scans', state.privateCarScans);
		store.write('users', state.users);
	}

	// Routing
	const routes = {};
	function route(path, render){ routes[path] = render; }
	function navigate(path){ history.pushState({}, '', path); render(); }
	window.addEventListener('popstate', render);

	function renderNav(){
		navEl.innerHTML = '';
		const add = (label, path) => {
			const a = document.createElement('a'); a.textContent = label; a.href = path; a.onclick = (e)=>{ e.preventDefault(); navigate(path); };
			navEl.appendChild(a);
		};
		if(state.user){
			add('Dashboard', '/');
			add('Scan Company Laptop', '/scan-company-laptop');
			add('Scan Private Laptop', '/scan-private-laptop');
			add('Scan Company Car', '/scan-company-car');
			add('Scan Private Car', '/scan-private-car');
			add('Reports', '/reports');
			add('SAP Forms', '/sap');
			const btn = document.createElement('button'); btn.textContent = 'Logout'; btn.onclick = ()=>{ state.user = null; saveState(); navigate('/login'); };
			navEl.appendChild(btn);
		}else{
			add('Login', '/login');
			add('Register', '/register');
			add('SAP Forms', '/sap');
		}
	}

	function layout(title, body){
		appEl.innerHTML = `<div class="card"><h2>${title}</h2>${body}</div>`;
	}
	function notice(ok, msg){ return `<div class="notice ${ok? 'ok':'err'}">${msg}</div>`; }
	function fmt(ts){ return new Date(ts).toLocaleString(); }

	// Views
	route('/', ()=>{
		if(!state.user){ navigate('/login'); return; }
		layout('Dashboard', `
			<div class="grid cols-2">
				<div class="card">
					<h3>Quick Actions</h3>
					<div class="toolbar">
						<a class="btn" href="/scan-company-laptop" onclick="event.preventDefault();history.pushState({},'',this.href);">Scan Company Laptop</a>
						<a class="btn" href="/scan-private-laptop" onclick="event.preventDefault();history.pushState({},'',this.href);">Scan Private Laptop</a>
						<a class="btn" href="/scan-company-car" onclick="event.preventDefault();history.pushState({},'',this.href);">Scan Company Car</a>
						<a class="btn" href="/scan-private-car" onclick="event.preventDefault();history.pushState({},'',this.href);">Scan Private Car</a>
					</div>
				</div>
				<div class="card">
					<h3>Digital SAP Authorization</h3>
					<p>Open the SAP forms portal.</p>
					<a class="btn" href="/sap" onclick="event.preventDefault();history.pushState({},'',this.href);">Open SAP Forms</a>
				</div>
			</div>
		`);
	});

	route('/login', ()=>{
		layout('Login', `
			<form id="loginForm">
				<div class="field"><label>Username</label><input name="username" required></div>
				<div class="field"><label>Password</label><input type="password" name="password" required></div>
				<div class="toolbar"><button class="btn" type="submit">Login</button><a class="btn secondary" href="/register" onclick="event.preventDefault();history.pushState({},'',this.href);">Register</a></div>
			</form>
		`);
		document.getElementById('loginForm').onsubmit = (e)=>{
			e.preventDefault();
			const fd = new FormData(e.target);
			const username = fd.get('username');
			const password = fd.get('password');
			const found = state.users.find(u=>u.username===username && u.password===password);
			if(!found){ appEl.querySelector('.card').insertAdjacentHTML('afterbegin', notice(false,'Invalid credentials')); return; }
			state.user = { username: found.username, role: found.role||'user' };
			saveState(); navigate('/');
		};
	});

	route('/register', ()=>{
		layout('Register', `
			<form id="registerForm">
				<div class="grid cols-2">
					<div class="field"><label>Username</label><input name="username" required></div>
					<div class="field"><label>Email</label><input type="email" name="email" required></div>
					<div class="field"><label>Password</label><input type="password" name="password" required></div>
					<div class="field"><label>Confirm Password</label><input type="password" name="confirm" required></div>
				</div>
				<div class="toolbar"><button class="btn" type="submit">Create Account</button><a class="btn secondary" href="/login" onclick="event.preventDefault();history.pushState({},'',this.href);">Back to Login</a></div>
			</form>
		`);
		document.getElementById('registerForm').onsubmit = (e)=>{
			e.preventDefault();
			const fd = new FormData(e.target);
			const u = fd.get('username'); const email = fd.get('email'); const p = fd.get('password'); const c = fd.get('confirm');
			if(p!==c){ appEl.querySelector('.card').insertAdjacentHTML('afterbegin', notice(false,'Passwords do not match')); return; }
			if(state.users.some(x=>x.username===u)){ appEl.querySelector('.card').insertAdjacentHTML('afterbegin', notice(false,'Username already exists')); return; }
			state.users.push({ username:u, email, password:p, role:'user', createdAt: Date.now() });
			saveState(); appEl.querySelector('.card').insertAdjacentHTML('afterbegin', notice(true,'Account created. You can log in.'));
		};
	});

	function scanForm(cfg){
		layout(cfg.title, `
			<form id="scanForm">
				${cfg.extraFields||''}
				<div class="field"><label>${cfg.label}</label><input name="value" required></div>
				<div class="field"><label>Action</label>
					<select name="action" required>
						<option value="in">Scan In</option>
						<option value="out">Scan Out</option>
					</select>
				</div>
				<div class="toolbar"><button class="btn" type="submit">Scan</button><button class="btn secondary" type="button" id="backBtn">Back</button></div>
			</form>
		`);
		document.getElementById('backBtn').onclick = ()=> navigate('/');
		document.getElementById('scanForm').onsubmit = (e)=>{
			e.preventDefault();
			const fd = new FormData(e.target);
			const entry = cfg.build(fd);
			cfg.push(entry);
			saveState();
			appEl.querySelector('.card').insertAdjacentHTML('afterbegin', notice(true, 'Scan saved.'));
			e.target.reset();
		};
	}

	route('/scan-company-laptop', ()=>{
		if(!state.user){ navigate('/login'); return; }
		scanForm({
			title:'Company Laptop Scan',
			label:'Laptop Number',
			push:(entry)=> state.laptopScans.unshift(entry),
			build:(fd)=> ({ laptopNumber: fd.get('value'), action: fd.get('action'), user: state.user.username, ts: Date.now() })
		});
	});

	route('/scan-private-laptop', ()=>{
		if(!state.user){ navigate('/login'); return; }
		scanForm({
			title:'Private Laptop Scan',
			label:'Laptop Serial Number',
			push:(entry)=> state.privateLaptopScans.unshift(entry),
			build:(fd)=> ({ serialNumber: fd.get('value'), action: fd.get('action'), user: state.user.username, ts: Date.now() })
		});
	});

	route('/scan-company-car', ()=>{
		if(!state.user){ navigate('/login'); return; }
		scanForm({
			title:'Company Car Scan',
			extraFields:'<div class="field"><label>Driver Name</label><input name="driver" required></div>',
			label:'Registration Number',
			push:(entry)=> state.companyCarScans.unshift(entry),
			build:(fd)=> ({ driver: fd.get('driver'), registration: fd.get('value'), action: fd.get('action'), user: state.user.username, ts: Date.now() })
		});
	});

	route('/scan-private-car', ()=>{
		if(!state.user){ navigate('/login'); return; }
		scanForm({
			title:'Private Car Scan',
			label:'Registration Number',
			push:(entry)=> state.privateCarScans.unshift(entry),
			build:(fd)=> ({ registration: fd.get('value'), action: fd.get('action'), user: state.user.username, ts: Date.now() })
		});
	});

	route('/reports', ()=>{
		if(!state.user){ navigate('/login'); return; }
		layout('Reports', `
			<div class="grid cols-2">
				<div class="card"><h3>Company Laptops</h3>${table(state.laptopScans, ['laptopNumber','action','user','ts'])}</div>
				<div class="card"><h3>Private Laptops</h3>${table(state.privateLaptopScans, ['serialNumber','action','user','ts'])}</div>
				<div class="card"><h3>Company Cars</h3>${table(state.companyCarScans, ['driver','registration','action','user','ts'])}</div>
				<div class="card"><h3>Private Cars</h3>${table(state.privateCarScans, ['registration','action','user','ts'])}</div>
			</div>
		`);
		function table(data, cols){
			if(!data.length) return '<p>No data.</p>';
			const head = cols.map(c=>`<th>${c}</th>`).join('');
			const rows = data.map(r=>`<tr>${cols.map(c=>`<td>${c==='ts'? fmt(r[c]): (r[c]??'')}</td>`).join('')}</tr>`).join('');
			return `<div class="toolbar"><button class="btn" data-export>Export CSV</button></div><table class="table"><thead><tr>${head}</tr></thead><tbody>${rows}</tbody></table>`;
		}
		const btns = appEl.querySelectorAll('[data-export]');
		btns.forEach((btn, idx)=>{
			btn.onclick = ()=>{
				const sets = [state.laptopScans, state.privateLaptopScans, state.companyCarScans, state.privateCarScans];
				const set = sets[idx];
				const csv = toCSV(set);
				download(`report_${idx+1}.csv`, csv);
			};
		});
	});

	route('/sap', ()=>{
		layout('Digital SAP Authorization System', `
			<p>Open the SAP forms portal in a new tab.</p>
			<div class="toolbar">
				<a class="btn" href="SAP%20forms.html" target="_blank" rel="noopener">Open SAP Forms</a>
			</div>
		`);
	});

	function toCSV(items){
		if(!items || !items.length) return '';
		const cols = Object.keys(items[0]);
		const lines = [cols.join(',')];
		for(const it of items){ lines.push(cols.map(c=>JSON.stringify(it[c]??'')).join(',')); }
		return lines.join('\n');
	}
	function download(filename, text){
		const a = document.createElement('a');
		a.href = URL.createObjectURL(new Blob([text], {type:'text/csv'}));
		a.download = filename; a.click(); URL.revokeObjectURL(a.href);
	}

	function render(){ renderNav(); const path = location.pathname || '/'; const view = routes[path] || routes['/']; view(); }

	// Seed an admin if none exists
	if(state.users.length === 0){
		state.users.push({ username:'admin', email:'admin@example.com', password:'admin', role:'admin', createdAt: Date.now() });
		saveState();
	}

	render();
})();