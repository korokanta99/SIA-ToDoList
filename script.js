document.addEventListener("DOMContentLoaded", () => {
    // ----- DATA -----
    let categories = ["Work", "Personal"];
    let selectedTask = null; // currently selected <li> for the task menu
  
    // ----- DOM ELEMENTS -----
    const sidebar = document.getElementById("sidebar");
    const menuToggle = document.getElementById("menu-toggle");
    const body = document.querySelector("body");
  
    const addCategoryBtn = document.getElementById("add-category");
    const addTaskBtn = document.getElementById("add-task");
    const categoriesContainer = document.getElementById("categories-container");
    const completedList = document.getElementById("completed-list");
  
    // Task menu
    const taskMenu = document.getElementById("task-menu");
    const editTaskBtn = document.getElementById("edit-task");
    const deleteTaskBtn = document.getElementById("delete-task");
    const moveTaskBtn = document.getElementById("move-task");
  
    // Modals
    const addModal = document.getElementById("add-modal");
    const newTaskTitle = document.getElementById("new-task-title");
    const newTaskCategory = document.getElementById("new-task-category");
    const cancelAddBtn = document.getElementById("cancel-add");
    const saveAddBtn = document.getElementById("save-add");
  
    const editModal = document.getElementById("edit-modal");
    const editTaskTitle = document.getElementById("edit-task-title");
    const editTaskCategory = document.getElementById("edit-task-category");
    const cancelEditBtn = document.getElementById("cancel-edit");
    const saveEditBtn = document.getElementById("save-edit");
  
    const moveModal = document.getElementById("move-modal");
    const moveTaskCategory = document.getElementById("move-task-category");
    const cancelMoveBtn = document.getElementById("cancel-move");
    const confirmMoveBtn = document.getElementById("confirm-move");
  
    const addCategoryModal = document.getElementById("add-category-modal");
    const newCategoryName = document.getElementById("new-category-name");
    const cancelCategoryBtn = document.getElementById("cancel-category");
    const saveCategoryBtn = document.getElementById("save-category");
  
    // ----- SIDEBAR TOGGLE -----
    menuToggle.addEventListener("click", () => {
      // Toggle sidebar sliding
      if (sidebar.classList.contains("show")) {
        sidebar.classList.remove("show");
        body.classList.remove("sidebar-open");
      } else {
        sidebar.classList.add("show");
        body.classList.add("sidebar-open");
      }
    });
  
    // ----- INITIAL RENDER -----
    renderCategories();
  
    // ----- EVENT HANDLERS -----
    // Add category
    addCategoryBtn.addEventListener("click", () => {
      newCategoryName.value = "";
      addCategoryModal.classList.remove("hidden");
    });
  
    saveCategoryBtn.addEventListener("click", () => {
      const catName = newCategoryName.value.trim();
      if (catName && !categories.includes(catName)) {
        categories.push(catName);
        renderCategories();
      }
      closeModal(addCategoryModal);
    });
  
    cancelCategoryBtn.addEventListener("click", () => {
      closeModal(addCategoryModal);
    });
  
    // Add task
    addTaskBtn.addEventListener("click", () => {
      newTaskTitle.value = "";
      populateCategorySelect(newTaskCategory, categories);
      addModal.classList.remove("hidden");
    });
  
    saveAddBtn.addEventListener("click", () => {
      const title = newTaskTitle.value.trim();
      const cat = newTaskCategory.value;
      if (title && cat) {
        addTaskToCategory(title, cat);
      }
      closeModal(addModal);
    });
  
    cancelAddBtn.addEventListener("click", () => {
      closeModal(addModal);
    });
  
    // Edit task
    editTaskBtn.addEventListener("click", () => {
      if (!selectedTask) return;
      const textSpan = selectedTask.querySelector("span.task-text");
      editTaskTitle.value = textSpan.textContent;
      const categoryDiv = selectedTask.closest(".todo-category");
      const currentCat = categoryDiv.getAttribute("data-category");
      populateCategorySelect(editTaskCategory, categories, currentCat);
      editModal.classList.remove("hidden");
      hideTaskMenu();
    });
  
    saveEditBtn.addEventListener("click", () => {
      if (!selectedTask) return;
      const newText = editTaskTitle.value.trim();
      const newCat = editTaskCategory.value;
      if (newText) {
        const textSpan = selectedTask.querySelector("span.task-text");
        textSpan.textContent = newText;
        const oldCatDiv = selectedTask.closest(".todo-category");
        const oldCat = oldCatDiv.getAttribute("data-category");
        if (newCat !== oldCat) {
          moveTaskToCategory(selectedTask, newCat);
        }
      }
      closeModal(editModal);
    });
  
    cancelEditBtn.addEventListener("click", () => {
      closeModal(editModal);
    });
  
    // Delete task
    deleteTaskBtn.addEventListener("click", () => {
      if (selectedTask) {
        selectedTask.remove();
        hideTaskMenu();
      }
    });
  
    // Move task
    moveTaskBtn.addEventListener("click", () => {
      if (!selectedTask) return;
      populateCategorySelect(moveTaskCategory, categories);
      moveModal.classList.remove("hidden");
      hideTaskMenu();
    });
  
    confirmMoveBtn.addEventListener("click", () => {
      if (!selectedTask) return;
      const newCat = moveTaskCategory.value;
      moveTaskToCategory(selectedTask, newCat);
      closeModal(moveModal);
    });
  
    cancelMoveBtn.addEventListener("click", () => {
      closeModal(moveModal);
    });
  
    // Hide task menu if clicking outside
    document.addEventListener("click", (e) => {
      if (!taskMenu.contains(e.target) && !e.target.classList.contains("task-options")) {
        hideTaskMenu();
      }
    });
  
    // ----- FUNCTIONS -----
    function renderCategories() {
      categoriesContainer.innerHTML = "";
      categories.forEach(cat => {
        const catDiv = document.createElement("div");
        catDiv.classList.add("todo-category");
        catDiv.setAttribute("data-category", cat);
  
        const catHeader = document.createElement("div");
        catHeader.classList.add("category-header");
  
        const h2 = document.createElement("h2");
        h2.textContent = cat;
  
        const delBtn = document.createElement("button");
        delBtn.textContent = "Delete";
        delBtn.classList.add("delete-category");
        delBtn.addEventListener("click", () => {
          deleteCategory(cat);
        });
  
        catHeader.appendChild(h2);
        catHeader.appendChild(delBtn);
        catDiv.appendChild(catHeader);
  
        const ul = document.createElement("ul");
        catDiv.appendChild(ul);
  
        categoriesContainer.appendChild(catDiv);
      });
    }
  
    function deleteCategory(cat) {
      categories = categories.filter(c => c !== cat);
      const catDiv = document.querySelector(`.todo-category[data-category="${cat}"]`);
      if (catDiv) {
        const tasks = catDiv.querySelectorAll("li");
        tasks.forEach(t => {
          completedList.appendChild(t);
          const checkbox = t.querySelector("input[type='checkbox']");
          if (checkbox) checkbox.remove();
        });
        catDiv.remove();
      }
    }
  
    function addTaskToCategory(title, cat) {
      const catDiv = document.querySelector(`.todo-category[data-category="${cat}"]`);
      if (!catDiv) return;
      const ul = catDiv.querySelector("ul");
      if (!ul) return;
      const li = createTaskItem(title);
      ul.appendChild(li);
    }
  
    function createTaskItem(title) {
      const li = document.createElement("li");
  
      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.addEventListener("change", () => {
        if (checkbox.checked) moveToCompleted(li);
      });
  
      const span = document.createElement("span");
      span.textContent = title;
      span.classList.add("task-text");
  
      const menuBtn = document.createElement("span");
      menuBtn.textContent = "⋮";
      menuBtn.classList.add("task-options");
      menuBtn.addEventListener("click", (e) => {
        selectedTask = li;
        const rect = menuBtn.getBoundingClientRect();
        taskMenu.style.top = `${rect.bottom + window.scrollY}px`;
        taskMenu.style.left = `${rect.left + window.scrollX}px`;
        taskMenu.classList.remove("hidden");
      });
  
      li.appendChild(checkbox);
      li.appendChild(span);
      li.appendChild(menuBtn);
  
      return li;
    }


function setCookie(name, value, days) {
  let expires = "";
  if (days) {
      let date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + value + "; path=/" + expires;
}


function getCookie(name) {
  let nameEQ = name + "=";
  let cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
      let c = cookies[i].trim();
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
  }
  return null;
}


function checkUserVisit() {
  let visit = getCookie("user_visit");
  if (!visit) {
      alert("Welcome! This is your first time visiting.");
      setCookie("user_visit", "yes", 30); 
  } else {
      console.log("Welcome back!");
  }
}


document.addEventListener("DOMContentLoaded", checkUserVisit);

  
    function moveToCompleted(li) {
      completedList.appendChild(li);
      const checkbox = li.querySelector("input[type='checkbox']");
      if (checkbox) checkbox.remove();
    }
  
    function moveTaskToCategory(li, newCat) {
      const newCatDiv = document.querySelector(`.todo-category[data-category="${newCat}"]`);
      if (!newCatDiv) return;
      const ul = newCatDiv.querySelector("ul");
      ul.appendChild(li);
    }
  
    function hideTaskMenu() {
      taskMenu.classList.add("hidden");
    }
  
    function closeModal(modal) {
      modal.classList.add("hidden");
    }
  
    function populateCategorySelect(selectElem, catArray, selectedCat) {
      selectElem.innerHTML = "";
      catArray.forEach(c => {
        const opt = document.createElement("option");
        opt.value = c;
        opt.textContent = c;
        if (selectedCat && selectedCat === c) {
          opt.selected = true;
        }
        selectElem.appendChild(opt);
      });
    }
  });
  