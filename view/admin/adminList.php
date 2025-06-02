<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Salon - Daftar Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #7e57c2;
      --primary-dark: #5e35b1;
      --primary-light: #b39ddb;
      --accent: #ff9800;
      --danger: #f44336;
      --success: #4caf50;
      --warning: #ffb300;
      --text: #333;
      --text-light: #666;
      --bg: #f5f5f5;
      --card-bg: #fff;
      --sidebar-bg: linear-gradient(135deg, #673ab7 0%, #512da8 100%);
      --shadow: 0 10px 20px rgba(0,0,0,0.1);
      --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    * {
      margin: 0; 
      padding: 0; 
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-color: var(--bg);
      color: var(--text);
    }

    .sidebar {
      width: 280px;
      background: var(--sidebar-bg);
      color: #fff;
      flex-shrink: 0;
      position: fixed;
      height: 100vh;
      overflow: auto;
      padding-top: 20px;
      transition: var(--transition);
      z-index: 100;
    }

    .sidebar h2 {
      text-align: center;
      padding: 1.5rem;
      font-size: 1.5rem;
      font-weight: 600;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .sidebar h2 i {
      margin-right: 10px;
      color: var(--accent);
    }

    .sidebar ul {
      list-style: none;
      padding: 1rem 0;
    }

    .sidebar ul li {
      padding: 15px 30px;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      font-weight: 500;
      margin: 0 10px;
      border-radius: 8px;
    }

    .sidebar ul li i {
      margin-right: 12px;
      width: 20px;
      text-align: center;
      font-size: 1.1rem;
    }

    .sidebar ul li:hover {
      background-color: rgba(255,255,255,0.1);
    }

    .sidebar ul li.active {
      background-color: rgba(255,255,255,0.2);
      position: relative;
    }

    .sidebar ul li.active::after {
      content: '';
      position: absolute;
      right: -10px;
      top: 50%;
      transform: translateY(-50%);
      width: 4px;
      height: 60%;
      background-color: var(--accent);
      border-radius: 4px;
    }

    .main {
      margin-left: 280px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      transition: var(--transition);
    }

    .navbar {
      background-color: var(--card-bg);
      padding: 1.2rem 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .navbar h1 {
      font-size: 1.5rem;
      color: var(--primary-dark);
      font-weight: 600;
      display: flex;
      align-items: center;
    }

    .navbar h1 i {
      margin-right: 10px;
      color: var(--accent);
    }

    .user-profile {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--primary-light);
    }

    .user-profile .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-profile .user-name {
      font-weight: 500;
      font-size: 0.9rem;
    }

    .user-profile .user-role {
      font-size: 0.8rem;
      color: var(--text-light);
    }

    .navbar button {
      padding: 10px 20px;
      border: none;
      background-color: var(--primary);
      color: white;
      cursor: pointer;
      border-radius: 8px;
      transition: var(--transition);
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .navbar button:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .content {
      padding: 2rem;
      flex: 1;
    }

    .card {
      background: var(--card-bg);
      padding: 2rem;
      border-radius: 16px;
      box-shadow: var(--shadow);
      margin-bottom: 2rem;
    }

    h2 {
      color: var(--primary-dark);
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    h2 i {
      color: var(--accent);
    }

    .button {
      background-color: var(--primary);
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-bottom: 1.5rem;
      transition: var(--transition);
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .button:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin-top: 1.5rem;
    }

    th, td {
      padding: 16px 20px;
      text-align: left;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    th {
      background-color: var(--primary);
      color: white;
      font-weight: 500;
      position: sticky;
      top: 0;
    }

    tr:nth-child(even) {
      background-color: rgba(0,0,0,0.02);
    }

    tr:hover {
      background-color: rgba(0,0,0,0.03);
    }

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .action-buttons button {
      padding: 8px 12px;
      font-size: 14px;
      border-radius: 6px;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .action-buttons button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .edit-btn {
      background-color: var(--warning);
      color: white;
    }

    .edit-btn:hover {
      background-color: #ffa000;
    }

    .delete-btn {
      background-color: var(--danger);
      color: white;
    }

    .delete-btn:hover {
      background-color: #d32f2f;
    }

    .form-container {
      display: none;
      margin-top: 2rem;
      animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-container form {
      margin-top: 1.5rem;
      background: var(--card-bg);
      padding: 2rem;
      border-radius: 12px;
      box-shadow: var(--shadow);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: var(--text);
      font-weight: 500;
    }

    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: var(--transition);
      font-size: 1rem;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(126, 87, 194, 0.2);
    }

    .form-actions {
      margin-top: 2rem;
      display: flex;
      gap: 12px;
    }

    .form-actions button {
      flex: 1;
    }

    .cancel-btn {
      background-color: #757575;
    }

    .cancel-btn:hover {
      background-color: #616161;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
    }

    .active-badge {
      background-color: rgba(76, 175, 80, 0.2);
      color: var(--success);
    }

    .inactive-badge {
      background-color: rgba(244, 67, 54, 0.2);
      color: var(--danger);
    }

    .search-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .search-box {
      position: relative;
      width: 300px;
    }

    .search-box input {
      width: 100%;
      padding: 12px 16px 12px 40px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: var(--transition);
    }

    .search-box input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(126, 87, 194, 0.2);
    }

    .search-box i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
    }

    .toggle-btn {
      display: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--primary-dark);
      transition: var(--transition);
    }

    .toggle-btn:hover {
      color: var(--primary);
    }

    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        width: 260px;
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .main {
        margin-left: 0;
      }

      .toggle-btn {
        display: block;
      }

      .search-box {
        width: 200px;
      }
    }

    @media (max-width: 768px) {
      .content {
        padding: 1.5rem;
      }

      .card {
        padding: 1.5rem;
      }

      .form-actions {
        flex-direction: column;
      }

      .search-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }

      .search-box {
        width: 100%;
      }

      th, td {
        padding: 12px 15px;
      }

      .action-buttons {
        flex-direction: column;
        gap: 6px;
      }
    }

    /* Animations */
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    .pulse {
      animation: pulse 2s infinite;
    }
  </style>
</head>
<body>

  <div class="sidebar" id="sidebar">
    <h2><i class="fas fa-spa"></i>BeautyCare</h2>
    <ul>
      <li onclick="location.href='#'"><i class="fas fa-tachometer-alt"></i> Dashboard</li>
      <li class="active"><i class="fas fa-user-shield"></i> Admin</li>
      <li onclick="location.href='#'"><i class="fas fa-users"></i> Pegawai</li>
      <li onclick="location.href='#'"><i class="fas fa-cut"></i> Layanan</li>
      <li onclick="location.href='#'"><i class="fas fa-calendar-check"></i> Booking</li>
      <li onclick="location.href='#'"><i class="fas fa-chart-bar"></i> Laporan</li>
    </ul>
  </div>

  <div class="main">
    <div class="navbar">
      <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
      <h1><i class="fas fa-spa"></i> BeautyCare Admin</h1>
      <div class="user-profile">
        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User Profile">
        <div class="user-info">
          <span class="user-name">Admin Cantik</span>
          <span class="user-role">Super Admin</span>
        </div>
        <button onclick="alert('Logout sukses')"><i class="fas fa-sign-out-alt"></i> Logout</button>
      </div>
    </div>

    <div class="content">
      <div class="card">
        <h2><i class="fas fa-user-shield"></i> Daftar Admin</h2>
        
        <div class="search-container">
          <button class="button pulse" onclick="showForm('add')"><i class="fas fa-plus"></i> Tambah Admin</button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari admin...">
          </div>
        </div>

        <div style="overflow-x: auto;">
          <table>
            <thead>
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="adminTable">
              <tr>
                <td>Rina Mulyani</td>
                <td>rina@salon.com</td>
                <td>08123456789</td>
                <td><span class="status-badge active-badge"><i class="fas fa-check-circle"></i> Aktif</span></td>
                <td class="action-buttons">
                  <button class="edit-btn" onclick="showForm('edit', this)"><i class="fas fa-pen"></i> Edit</button>
                  <button class="delete-btn" onclick="deleteRow(this)"><i class="fas fa-trash"></i> Hapus</button>
                </td>
              </tr>
              <tr>
                <td>Budi Santoso</td>
                <td>budi@salon.com</td>
                <td>08234567890</td>
                <td><span class="status-badge active-badge"><i class="fas fa-check-circle"></i> Aktif</span></td>
                <td class="action-buttons">
                  <button class="edit-btn" onclick="showForm('edit', this)"><i class="fas fa-pen"></i> Edit</button>
                  <button class="delete-btn" onclick="deleteRow(this)"><i class="fas fa-trash"></i> Hapus</button>
                </td>
              </tr>
              <tr>
                <td>Siti Rahayu</td>
                <td>siti@salon.com</td>
                <td>08345678901</td>
                <td><span class="status-badge inactive-badge"><i class="fas fa-times-circle"></i> Nonaktif</span></td>
                <td class="action-buttons">
                  <button class="edit-btn" onclick="showForm('edit', this)"><i class="fas fa-pen"></i> Edit</button>
                  <button class="delete-btn" onclick="deleteRow(this)"><i class="fas fa-trash"></i> Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="form-container" id="formContainer">
          <h3 id="formTitle"><i class="fas fa-user-plus"></i> Tambah Admin</h3>
          <form>
            <div class="form-group">
              <label for="nama">Nama Lengkap</label>
              <input type="text" id="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
              <label for="email">Alamat Email</label>
              <input type="email" id="email" placeholder="Masukkan alamat email" required>
            </div>
            <div class="form-group">
              <label for="telepon">Nomor Telepon</label>
              <input type="text" id="telepon" placeholder="Masukkan nomor telepon" required>
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" style="width: 100%; padding: 12px 16px; border-radius: 8px; border: 1px solid #ddd;">
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
              </select>
            </div>
            <div class="form-actions">
              <button type="button" class="button" onclick="submitForm()"><i class="fas fa-save"></i> Simpan</button>
              <button type="button" class="button cancel-btn" onclick="hideForm()"><i class="fas fa-times"></i> Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('show');
    }

    let editMode = false;
    let editingRow = null;

    function showForm(mode, btn = null) {
      const formContainer = document.getElementById('formContainer');
      const title = document.getElementById('formTitle');
      formContainer.style.display = 'block';

      if (mode === 'edit' && btn) {
        title.innerHTML = '<i class="fas fa-user-edit"></i> Edit Admin';
        editMode = true;
        editingRow = btn.closest('tr');
        
        // Get data from the row
        const cells = editingRow.cells;
        document.getElementById('nama').value = cells[0].innerText;
        document.getElementById('email').value = cells[1].innerText;
        document.getElementById('telepon').value = cells[2].innerText;
        
        // Set status based on badge
        const statusBadge = cells[3].querySelector('.status-badge');
        document.getElementById('status').value = 
          statusBadge.classList.contains('active-badge') ? 'active' : 'inactive';
      } else {
        title.innerHTML = '<i class="fas fa-user-plus"></i> Tambah Admin';
        editMode = false;
        editingRow = null;
        document.getElementById('nama').value = '';
        document.getElementById('email').value = '';
        document.getElementById('telepon').value = '';
        document.getElementById('status').value = 'active';
      }
      
      // Scroll to form
      formContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function hideForm() {
      document.getElementById('formContainer').style.display = 'none';
    }

    function submitForm() {
      const nama = document.getElementById('nama').value;
      const email = document.getElementById('email').value;
      const telepon = document.getElementById('telepon').value;
      const status = document.getElementById('status').value;
      
      const statusBadge = status === 'active' ? 
        '<span class="status-badge active-badge"><i class="fas fa-check-circle"></i> Aktif</span>' : 
        '<span class="status-badge inactive-badge"><i class="fas fa-times-circle"></i> Nonaktif</span>';

      if (editMode && editingRow) {
        // Update existing row
        editingRow.cells[0].innerText = nama;
        editingRow.cells[1].innerText = email;
        editingRow.cells[2].innerText = telepon;
        editingRow.cells[3].innerHTML = statusBadge;
        
        // Show success message
        showAlert('Admin berhasil diperbarui!', 'success');
      } else {
        // Add new row
        const table = document.getElementById('adminTable');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td>${nama}</td>
          <td>${email}</td>
          <td>${telepon}</td>
          <td>${statusBadge}</td>
          <td class="action-buttons">
            <button class="edit-btn" onclick="showForm('edit', this)"><i class="fas fa-pen"></i> Edit</button>
            <button class="delete-btn" onclick="deleteRow(this)"><i class="fas fa-trash"></i> Hapus</button>
          </td>
        `;
        table.appendChild(newRow);
        
        // Show success message
        showAlert('Admin baru berhasil ditambahkan!', 'success');
      }

      hideForm();
    }

    function deleteRow(btn) {
      const row = btn.closest('tr');
      const nama = row.cells[0].innerText;
      
      if (confirm(`Yakin ingin menghapus admin ${nama}?`)) {
        row.classList.add('deleting');
        setTimeout(() => {
          row.remove();
          showAlert('Admin berhasil dihapus!', 'danger');
        }, 300);
      }
    }
    
    function showAlert(message, type) {
      // Create alert element
      const alert = document.createElement('div');
      alert.className = `alert alert-${type}`;
      alert.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
      `;
      alert.style.position = 'fixed';
      alert.style.top = '20px';
      alert.style.right = '20px';
      alert.style.padding = '15px 20px';
      alert.style.backgroundColor = type === 'success' ? '#4CAF50' : '#F44336';
      alert.style.color = 'white';
      alert.style.borderRadius = '8px';
      alert.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
      alert.style.display = 'flex';
      alert.style.justifyContent = 'space-between';
      alert.style.alignItems = 'center';
      alert.style.zIndex = '1000';
      alert.style.animation = 'fadeIn 0.3s ease-out';
      alert.style.maxWidth = '400px';
      
      // Add close button style
      alert.querySelector('button').style.background = 'none';
      alert.querySelector('button').style.border = 'none';
      alert.querySelector('button').style.color = 'white';
      alert.querySelector('button').style.fontSize = '1.2rem';
      alert.querySelector('button').style.cursor = 'pointer';
      alert.querySelector('button').style.marginLeft = '15px';
      
      document.body.appendChild(alert);
      
      // Remove alert after 5 seconds
      setTimeout(() => {
        alert.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => alert.remove(), 300);
      }, 5000);
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      const sidebar = document.getElementById('sidebar');
      const toggleBtn = document.querySelector('.toggle-btn');
      
      if (window.innerWidth <= 992 && 
          !sidebar.contains(event.target) && 
          event.target !== toggleBtn && 
          !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('show');
      }
    });
  </script>
</body>
</html>