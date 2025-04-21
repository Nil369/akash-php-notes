const baseURL = "http://localhost/akash-php-notes/concepts/10_REST_API/api";
let users = [];

async function fetchUsers() {
  const res = await fetch(`${baseURL}/api-get-all.php`);
  users = await res.json();
  renderUsers(users);
}

function renderUsers(data) {
  const tbody = document.getElementById("userTableBody");
  tbody.innerHTML = data.map(user => `
    <tr>
      <td>${user.id}</td>
      <td>${user.name}</td>
      <td>${user.email}</td>
      <td>${user.created_at}</td>
      <td>
        <button class="btn btn-sm btn-warning" onclick="openEditModal(${user.id})">Edit</button>
        <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${user.id})">Delete</button>
      </td>
    </tr>
  `).join('');
}

function searchUsers() {
  const query = document.getElementById("searchInput").value.toLowerCase();
  const filtered = users.filter(u => u.name.toLowerCase().includes(query));
  renderUsers(filtered);
}

function openEditModal(id) {
  const user = users.find(u => u.id == id);
  document.getElementById("editId").value = user.id;
  document.getElementById("editName").value = user.name;
  document.getElementById("editEmail").value = user.email;
  new bootstrap.Modal(document.getElementById('editModal')).show();
}

async function updateUser(event) {
  event.preventDefault();
  const id = document.getElementById("editId").value;
  const name = document.getElementById("editName").value;
  const email = document.getElementById("editEmail").value;

  await fetch(`${baseURL}/api-update.php`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, name, email })
  });

  bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
  fetchUsers();
}

function openDeleteModal(id) {
  document.getElementById("deleteId").value = id;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

async function deleteUser(event) {
  event.preventDefault();
  const id = document.getElementById("deleteId").value;
  await fetch(`${baseURL}/api-delete.php`, {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
  fetchUsers();
}

// Load users on page load
window.onload = fetchUsers;