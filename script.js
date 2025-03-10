document.addEventListener("DOMContentLoaded", () => {

    fetchTasks();

    const addTaskBtn = document.getElementById("add-task");
    const signOutBtn = document.getElementById("sign-out");

    const addModal = document.getElementById("add-modal");
    const newTaskTitle = document.getElementById("new-task-title");
    const newTaskDesc = document.getElementById("new-task-desc");
    const newTaskDeadline = document.getElementById("new-task-deadline");
    const cancelAddBtn = document.getElementById("cancel-add");

    const modalForm = document.getElementById("modalForm");

    modalForm.addEventListener("submit", function(e) {
        addContact(e);
    });

    addTaskBtn.addEventListener("click", () => {
        newTaskTitle.value = "";
        newTaskDesc.value = "";
        newTaskDeadline.value = "";
        addModal.classList.remove("hidden");
    });

    cancelAddBtn.addEventListener("click", () => {
        addModal.classList.add("hidden");
    });

    async function fetchTasks() {
        try {
            const response = await fetch('./src/php/read.php');
            const data = await response.json();
            const taskList = document.getElementById('in-progress-list');

            taskList.innerHTML = '';

            if (data.status === 200 && Array.isArray(data.data)) {
                data.data.forEach(task => {
                    const taskElement = createTaskElement(task);
                    taskList.appendChild(taskElement);
                });
            } else {
                alert(data.message);
            }
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
        const deadlineSpan = taskElement.querySelector('.task-deadline');
        
        titleSpan.textContent = task.task_name;
        descSpan.textContent = task.description || 'No Description';
        deadlineSpan.textContent = formatDate(task.deadline);
    
        return taskElement;
    }
    

    function formatDate(dateString) {
        if (!dateString) return 'No deadline';
        const date = new Date(dateString);
        return `Due ${date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}`;
    }

    function addContact(e) {
        e.preventDefault();
    
        const formData = new FormData(modalForm);
    
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        fetch('./src/php/add.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(res => {
            alert(res.message);
            if (res.status === 200) {
                fetchTasks();
                addModal.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error adding task:', error);
            alert('Failed to add task. Please check the console for details.');
        });
        
    }
    

});
