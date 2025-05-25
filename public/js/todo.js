document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const TODO_HASH = params.get('hash');

  if (!TODO_HASH) {
    alert("Chybí hash v URL!");
    return;
  }

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
    console.log("Load tasks");
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

    const unfinished = tasks.filter(t => !t.completed);
    const finished = tasks.filter(t => t.completed);
    const sorted = [...unfinished, ...finished];

    sorted.forEach(({ id, description, completed }) => {
      const li = document.createElement('li');
      li.classList.toggle('completed', completed);

      const span = document.createElement('span');
      span.textContent = description;
      span.className = completed ? 'completed' : '';

      li.onclick = e => {
        if (e.target.classList.contains('delete-btn')) return;
        updateTask(id, { description, completed: completed ? 0 : 1 });
      };

      const delBtn = document.createElement('button');
      delBtn.className = 'delete-btn';
      delBtn.textContent = '✕';
      delBtn.title = 'Smazat úkol';
      delBtn.onclick = e => {
        e.stopPropagation();
        if (confirm('Opravdu chceš smazat tento úkol?')) {
          deleteTask(id);
        }
      };

      li.append(span, delBtn);
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
  console.log("TODO JS Loaded...");
});
