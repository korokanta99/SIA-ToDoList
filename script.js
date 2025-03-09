document.addEventListener("DOMContentLoaded", () => {

  fetchTasks();

  const addTaskBtn = document.getElementById("add-task");
  const signOutBtn = document.getElementById("sign-out");

  const addModal = document.getElementById("add-modal");
  const newTaskTitle = document.getElementById("new-task-title");
  const newTaskDesc = document.getElementById("new-task-desc");
  const newTaskDeadline = document.getElementById("new-task-deadline");
  const cancelAddBtn = document.getElementById("cancel-add");
  const saveAddBtn = document.getElementById("save-add");

  // Open Add Task Modal
  addTaskBtn.addEventListener("click", () => {
      newTaskTitle.value = "";
      newTaskDesc.value = "";
      newTaskDeadline.value = "";
      addModal.classList.remove("hidden");
  });

  // Close Modal (Cancel)
  cancelAddBtn.addEventListener("click", () => {
      addModal.classList.add("hidden");
  });

  // Close Modal (Save — No actual saving)
  saveAddBtn.addEventListener("click", () => {
      addModal.classList.add("hidden");
  });

  signOutBtn.addEventListener("click", () => {
      alert("Sign Out clicked!");
  });

  async function fetchTasks() {
    try {
        const response = await fetch('src/php/read.php');
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! Status: ${response.status}\n${errorText}`);
        }

        const data = await response.json();
        
        if (data.status === 401) {
            alert(data.message);
            return;
        }

        const inProgressList = document.getElementById('in-progress-list');
        inProgressList.innerHTML = '';
        
        data.data.forEach(task => {
            const taskElement = createTaskElement(task);
            inProgressList.appendChild(taskElement);
        });
    } catch (error) {
        console.error('Error fetching tasks:', error);
        alert('Error loading tasks. Please try again later.');
    }
}

function createTaskElement(task) {
    const template = document.querySelector('.task-item.hidden');
    const taskElement = template.cloneNode(true);
    taskElement.classList.remove('hidden');
    
    const titleSpan = taskElement.querySelector('.task-title');
    const descSpan = taskElement.querySelector('.task-desc');
    
    titleSpan.textContent = task.task_name;
    descSpan.textContent = task.description;
    
    return taskElement;
}

});
