body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 20px;
}

h1 {
    margin-bottom: 10px;
}

a {
    color: #155eef;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    border: 1px solid #999;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.success {
    padding: 10px;
    background-color: #e8f7e8;
    color: #176b17;
}

form {
    display: inline;
}

label {
    display: inline-block;
    margin-bottom: 5px;
    font-weight: bold;
}

input,
select {
    width: 100%;
    box-sizing: border-box;
    padding: 9px;
}

input[type="checkbox"] {
    width: auto;
    padding: 0;
    margin-right: 6px;
}

button,
.btn {
    padding: 8px 14px;
    background-color: #f0f0f0;
    border: 1px solid #999;
    cursor: pointer;
    font-family: Arial, sans-serif;
    font-size: 14px;
    color: #000;
    text-decoration: none;
    display: inline-block;
}

button:hover,
.btn:hover {
    background-color: #e0e0e0;
}

/* Tata letak tombol aksi di seluruh tabel */
.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.action-buttons form {
    display: inline-flex;
    align-items: center;
    margin: 0;
}

.action-buttons .btn,
.action-buttons button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    min-height: 38px;
    margin: 0;
    padding: 8px 14px;
    white-space: nowrap;
}

.errors {
    padding: 10px 10px 10px 30px;
    background-color: #ffecec;
    color: #a40000;
}

/* Navigasi utama */
.main-nav {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
    margin-bottom: 16px;
}

.nav-links a {
    margin-right: 14px;
    text-decoration: none;
}

.nav-links a:hover {
    text-decoration: underline;
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-info {
    font-size: 14px;
    color: #333;
}

/* Pagination sederhana */
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 20px 0 0;
    gap: 6px;
    flex-wrap: wrap;
}

.pagination li span,
.pagination li a {
    display: inline-block;
    padding: 6px 12px;
    border: 1px solid #999;
    text-decoration: none;
    color: #000;
    background-color: #f0f0f0;
}

.pagination li a:hover {
    background-color: #e0e0e0;
}

.pagination li.active span {
    background-color: #155eef;
    border-color: #155eef;
    color: #fff;
    font-weight: bold;
}

.pagination li.disabled span {
    color: #999;
    background-color: #f7f7f7;
    cursor: not-allowed;
}

/* Upload dan tampilan foto mahasiswa */
input[type="file"] {
    width: 100%;
    box-sizing: border-box;
    padding: 8px;
    border: 1px solid #999;
    background-color: #fff;
}

.foto-thumb {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 6px;
    display: block;
}

.foto-preview {
    width: 160px;
    height: 160px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin: 8px 0;
}

.hint {
    display: block;
    font-size: 13px;
    color: #666;
    margin-top: 5px;
}

.login-note {
    margin: 15px 0;
    padding: 12px 15px;
    background-color: #fff8db;
    border: 1px solid #e5d38a;
    border-radius: 6px;
    font-size: 14px;
}

.login-note p {
    margin: 5px 0;
}

.login-note hr {
    border: 0;
    border-top: 1px solid #e5d38a;
    margin: 10px 0;
}

.login-note small {
    color: #666;
}

