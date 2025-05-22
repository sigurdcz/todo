const listURL = `/todo/list/${TODO_HASH}`;

document.getElementById('addForm').addEventListener('submit', async e => {
  e.preventDefault();
  const input = document.getElementById('description');
  const text = input.value.trim();
  if (!text) return;
  await fetch(`${listURL}/task`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ description: text })
  });
  input.value = '';
  loadTasks();
});

async function loadTasks() {
  try {
    const res = await fetch(listURL);
    const data = await res.json();
    if (!res.ok) return alert(data.error || 'Chyba při načítání úkolů.');
    renderTasks(data.tasks || []);
  } catch (err) {
    alert('Nepodařilo se načíst úkoly.');
  }
}

function renderTasks(tasks) {
  const list = document.getElementById('tasks');
  list.innerHTML = '';
  tasks.forEach(({ id, description, completed }) => {
    const li = document.createElement('li');

    const span = document.createElement('span');
    span.textContent = description;
    if (completed) span.classList.add('completed');

    const toggleBtn = document.createElement('button');
    toggleBtn.classList.add('toggle');
    toggleBtn.textContent = completed ? 'Nehotovo' : 'Hotovo';
    toggleBtn.onclick = () => updateTask(id, { description, completed: completed ? 0 : 1 });

    const delBtn = document.createElement('button');
    delBtn.textContent = 'Smazat';
    delBtn.onclick = () => deleteTask(id);

    li.append(span, toggleBtn, delBtn);
    list.appendChild(li);
  });
}

async function updateTask(id, data) {
  await fetch(`${listURL}/task/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  loadTasks();
}

async function deleteTask(id) {
  await fetch(`${listURL}/task/${id}`, { method: 'DELETE' });
  loadTasks();
}

loadTasks();
