document.addEventListener('DOMContentLoaded', () => {
  const pathParts = window.location.pathname.split('/');

  if (!(pathParts.length === 3 && pathParts[1] === 'todo')) {
    return;
  }

  const TODO_HASH = pathParts[2];
  const listURL = `/todo/list/${TODO_HASH}`;

  const addForm = document.getElementById('addForm');
  const tasksContainer = document.getElementById('tasks');

  if (!addForm || !tasksContainer) {
    console.warn("[TODO.js] Nebyl nalezen formulář nebo kontejner pro úkoly. Ukončuji skript.");
    return;
  }

  addForm.addEventListener('submit', async e => {
    e.preventDefault();
    const input = document.getElementById('description');
    const text = input.value.trim();

    if (!text) return;

    try {
      const res = await fetch(`${listURL}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ description: text })
      });

      if (!res.ok) {
        const errData = await res.json();
        console.error("[TODO.js] Chyba při přidávání úkolu:", errData);
        alert("Chyba při přidávání úkolu.");
        return;
      }

      input.value = '';
      loadTasks();
    } catch (err) {
      console.error("[TODO.js] Výjimka při přidávání úkolu:", err);
    }
  });

  async function loadTasks() {
    try {
      const res = await fetch(listURL);
      const data = await res.json();

      if (!res.ok) {
        console.error("[TODO.js] Chyba při načítání úkolů:", data.error);
        alert(data.error || 'Chyba při načítání úkolů.');
        return;
      }

      renderTasks(data.tasks || []);
    } catch (err) {
      console.error("[TODO.js] Výjimka při načítání úkolů:", err);
      alert('Nepodařilo se načíst úkoly.');
    }
  }

  function renderTasks(tasks) {
    tasksContainer.innerHTML = '';

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
      tasksContainer.appendChild(li);
    });
  }

  async function updateTask(id, data) {
    try {
      const res = await fetch(`${listURL}/task/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      if (!res.ok) {
        const errData = await res.json();
        console.error("[TODO.js] Chyba při aktualizaci úkolu:", errData);
      } else {
        loadTasks();
      }
    } catch (err) {
      console.error("[TODO.js] Výjimka při aktualizaci úkolu:", err);
    }
  }

  async function deleteTask(id) {
    try {
      const res = await fetch(`${listURL}/task/${id}`, { method: 'DELETE' });

      if (!res.ok) {
        const errData = await res.json();
        console.error("[TODO.js] Chyba při mazání úkolu:", errData);
      } else {
        loadTasks();
      }
    } catch (err) {
      console.error("[TODO.js] Výjimka při mazání úkolu:", err);
    }
  }

  loadTasks();
});
