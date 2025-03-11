document.addEventListener("DOMContentLoaded", () => {

    fetchTasks();

    const addTaskBtn = document.getElementById("add-task");
    const signOutBtn = document.getElementById("sign-out");

    const addModal = document.getElementById("add-modal");
    const newTaskTitle = document.getElementById("new-task-title");
    const newTaskDesc = document.getElementById("new-task-desc");
    const newTaskDeadline = document.getElementById("new-task-deadline");
    const cancelAddBtn = document.getElementById("cancel-add");


    document.getElementById("cancel-edit").addEventListener("click", () => {
        document.getElementById("edit-modal").classList.add("hidden");
    })

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

        const editBtn = document.createElement('button');
        editBtn.textContent = 'Edit';
        editBtn.classList.add('edit-btn');

        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.classList.add('delete-btn');

        deleteBtn.addEventListener('click', () => deleteTask(task.task_id));
        editBtn.addEventListener('click', () => editTask(task));

        
        taskElement.appendChild(deleteBtn);
        taskElement.appendChild(editBtn);
        

        return taskElement;
    }
    
    function editTask(task) {
        const editModal = document.getElementById("edit-modal");
        const editTitle = document.getElementById("edit-task-title");
        const editDesc = document.getElementById("edit-task-desc");
        const editDeadline = document.getElementById("edit-task-deadline");
    
        editTitle.value = task.task_name;
        editDesc.value = task.description || "";
        editDeadline.value = task.deadline ? task.deadline.split("T")[0] : "";
    
        editModal.classList.remove("hidden");
    
        document.getElementById("editForm").onsubmit = async function(e) {
            e.preventDefault();
            await updateTask(task.task_id);
            
        };

    }

    async function updateTask(taskId) {
        const editTitle = document.getElementById("edit-task-title").value;
        const editDesc = document.getElementById("edit-task-desc").value;
        const editDeadline = document.getElementById("edit-task-deadline").value;
    
        const formData = new FormData();
        formData.append("task_id", taskId);
        formData.append("task_name", editTitle);
        formData.append("description", editDesc);
        formData.append("deadline", editDeadline);
    
        try {
            const response = await fetch('./src/php/edit.php', {
                method: 'POST',
                body: formData
            });
    
            const result = await response.json();
            alert(result.message);
    
            if (result.status === 200) {
                fetchTasks();
                document.getElementById("edit-modal").classList.add("hidden");
            }
        } catch (error) {
            console.error('Error updating task:', error);
            alert('Failed to update task.');
        }
    }

    function formatDate(dateString) {
        if (!dateString) return 'No deadline';
        const date = new Date(dateString);
        return `Due ${date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}`;
    }


    async function deleteTask(taskId) {
        if (!confirm("Are you sure you want to delete this task?")) {
            return;
        }
        
        console.log("Deleting task with ID:", taskId);

        try {
            const response = await fetch('./src/php/delete.php', {
                method: 'POST',
                body: new URLSearchParams({ 'task_id': taskId })
            });
    
            const result = await response.json();
            alert(result.message);
    
            if (result.status === 200) {
                fetchTasks(); 
            }
        } catch (error) {
            alert('Failed to delete task.');
        }
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
