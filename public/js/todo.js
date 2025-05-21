const listEndpoint = `/todo/list/${TODO_HASH}`;

async function fetchList() {
  const res = await fetch(listEndpoint);
  const data = await res.json();
  if (!res.ok) return alert(data.error);
  renderTasks(data.tasks);
}

function renderTasks(tasks) {
  const ul = document.getElementById('tasks');
  ul.innerHTML = '';
  tasks.forEach(task => {
    const li = document.createElement('li');
    const desc = document.createElement('span');
    desc.textContent = task.description;
    if (task.completed) desc.style.textDecoration = 'line-through';

    const toggle = document.createElement('button');
    toggle.textContent = task.completed ? 'Nehotovo' : 'Hotovo';
    toggle.onclick = () => updateTask(task.id, {
      description: task.description,
      completed: task.completed ? 0 : 1
    });

    const del = document.createElement('button');
    del.textContent = 'Smazat';
    del.onclick = () => deleteTask(task.id);

    li.append(desc, toggle, del);
    ul.appendChild(li);
  });
}

async function updateTask(id, data) {
  await fetch(`/todo/list/${TODO_HASH}/task/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  fetchList();
}

async function deleteTask(id) {
  await fetch(`/todo/list/${TODO_HASH}/task/${id}`, { method: 'DELETE' });
  fetchList();
}

document.getElementById('addForm').onsubmit = async e => {
  e.preventDefault();
  const desc = document.getElementById('description').value;
  await fetch(`/todo/list/${TODO_HASH}/task`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ description: desc })
  });
  document.getElementById('description').value = '';
  fetchList();
};

fetchList();
