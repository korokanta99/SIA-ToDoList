<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>To-Do List</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

<main>
    <header>
        <div class="header-left">
            <div class="profile-pic">
                <img src="pics/profile.jpg" alt="Profile Picture">
            </div>
            <button id="sign-out">Sign Out</button>
        </div>
        <div class="header-right">
            <button id="add-task">+ Add Task</button>
        </div>
    </header>

    <section id="tasks-container" class="tasks-layout">
        <div id="in-progress-container">
            <h2>In Progress</h2>
            <ul id="in-progress-list"></ul>
        </div>
        <div id="completed-container">
            <h2>Completed</h2>
            <ul id="completed-list"></ul>
        </div>
    </section>
</main>

<!-- Modals -->
<div id="add-modal" class="modal hidden">
    <div class="modal-content">
        <h2>Add New Task</h2>
        <form id="modalForm">
            <label for="new-task-title">Task Title:</label>
            <input type="text" id="new-task-title" name="new-task-title" required />

            <label for="new-task-desc">Task Description:</label>
            <textarea id="new-task-desc" name="new-task-desc"></textarea>

            <label for="new-task-deadline">Deadline:</label>
            <input type="date" id="new-task-deadline" name="new-task-deadline" required />

            <div class="modal-actions">
                <button id="cancel-add" type="button">Cancel</button>
                <button id="save-add" type="submit">Save</button>
            </div>
        </form>      
    </div>
</div>

<div id="edit-modal" class="modal hidden">
    <div class="modal-content">
        <h2>Edit Task</h2>
        <form id="editForm">
            <label for="edit-task-title">Task Title:</label>
            <input type="text" id="edit-task-title" name="edit-task-title" required />

            <label for="edit-task-desc">Task Description:</label>
            <textarea id="edit-task-desc" name="edit-task-desc"></textarea>

            <label for="edit-task-deadline">Deadline:</label>
            <input type="date" id="edit-task-deadline" name="edit-task-deadline" required />

            <div class="modal-actions">
                <button id="cancel-edit" type="button">Cancel</button>
                <button id="save-edit" type="submit">Save</button>
            </div>
        </form>      
    </div>
</div>

<li class="task-item hidden">
    <input type="checkbox" />
    <div class="task-texts">
        <span class="task-title">Complete project documentation</span>
        <span class="task-desc">Review and update all documentation files</span>
        <br>
        <span class="task-deadline"></span>
    </div>
</li>

<script src="script.js"></script>
</body>
</html>
