<?php
ob_start();
?>
<!-- Dragula css -->
<link rel="stylesheet" href="<?= App\Helpers\Network::home_url("assets/libs/dragula/dragula.min.css") ?>" />
<?php
$additionCss = ob_get_clean();
ob_start();
?>
<div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
    <div class="bg-white w-100 p-4 pb-0">
        <div class="p-3 bg-light rounded mb-4">
            <div class="row g-2">
                <div class="col-lg-auto">
                    <select class="form-control" data-choices data-choices-search-false name="choices-select-sortlist"
                        id="choices-select-sortlist">
                        <option value="">Sort</option>
                        <option value="By ID">By ID</option>
                        <option value="By Name">By Name</option>
                    </select>
                </div>
                <div class="col-lg-auto">
                    <select class="form-control" data-choices data-choices-search-false name="choices-select-status"
                        id="choices-select-status">
                        <option value="">All Tasks</option>
                        <option value="Completed">Completed</option>
                        <option value="Inprogress">Inprogress</option>
                        <option value="Pending">Pending</option>
                        <option value="New">New</option>
                    </select>
                </div>
                <div class="col-lg">
                    <div class="search-box">
                        <input type="text" id="searchTaskList" class="form-control search"
                            placeholder="Search task name">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <div class="col-lg-auto">
                    <button class="btn btn-primary createTask" type="button" data-bs-toggle="modal"
                        data-bs-target="#createTask">
                        <i class="ri-add-fill align-bottom"></i> Add Tasks
                    </button>
                </div>
            </div>
        </div>

        <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
            <div id="elmLoader">
                <div class="spinner-border text-primary avatar-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="todo-task" id="todo-task">
                <div class="table-responsive">
                    <table class="table align-middle position-relative table-nowrap">
                        <thead class="table-active">
                            <tr>
                                <th scope="col">Task Name</th>
                                <th scope="col">Assigned</th>
                                <th scope="col">Due Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Priority</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>

                        <tbody id="task-list"></tbody>
                    </table>
                </div>
            </div>
            <div class="py-4 mt-4 text-center" id="noresult" style="display: none;">
                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                    colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                <h5 class="mt-4">Sorry! No Result Found</h5>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="createTaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="createTaskLabel">Create Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="createTaskBtn-close"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="task-error-msg" class="alert alert-danger py-2"></div>
                <form autocomplete="off" action="" id="creattask-form">
                    <input type="hidden" id="taskid-input" class="form-control">
                    <div class="mb-3">
                        <label for="task-title-input" class="form-label">Task Title</label>
                        <input type="text" id="task-title-input" class="form-control" placeholder="Enter task title">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="task-assign-input" class="form-label">Assigned To</label>

                        <div class="avatar-group justify-content-center" id="assignee-member"></div>
                        <div class="select-element">
                            <button class="btn btn-light w-100 d-flex justify-content-between" type="button"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <span>Assigned To<b id="total-assignee" class="mx-1">0</b>Members</span> <i
                                    class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu w-100">
                                <div data-simplebar style="max-height: 141px">
                                    <ul class="list-unstyled mb-0">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">James Forbes</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-3.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">John Robles</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-4.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Mary Gant</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Curtis Saenz</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-5.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Virgie Price</div>
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-10.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Anthony Mills</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-6.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Marian Angel</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-7.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Johnnie Walton</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-8.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Donna Weston</div>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="avatar-xxs flex-shrink-0 me-2">
                                                    <img src="assets/images/users/avatar-9.jpg" alt=""
                                                        class="img-fluid rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">Diego Norris</div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mb-3">
                        <div class="col-lg-6">
                            <label for="task-status" class="form-label">Status</label>
                            <select class="form-control" data-choices data-choices-search-false id="task-status-input">
                                <option value="">Status</option>
                                <option value="New" selected>New</option>
                                <option value="Inprogress">Inprogress</option>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <!--end col-->
                        <div class="col-lg-6">
                            <label for="priority-field" class="form-label">Priority</label>
                            <select class="form-control" data-choices data-choices-search-false id="priority-field">
                                <option value="">Priority</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <!--end col-->
                    </div>
                    <div class="mb-4">
                        <label for="task-duedate-input" class="form-label">Due Date:</label>
                        <input type="date" id="task-duedate-input" class="form-control" placeholder="Due date">
                    </div>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-ghost-success" data-bs-dismiss="modal"><i
                                class="ri-close-fill align-bottom"></i> Close</button>
                        <button type="submit" class="btn btn-primary" id="addNewTodo">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end create taks-->

<!-- removeFileItemModal -->
<div id="removeTaskItemModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-removetodomodal"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this task ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="remove-todoitem">Yes, Delete It!</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--end delete modal -->

<?php
$pageContent = ob_get_clean();

ob_start();
?>
<!-- dragula init js -->
<script src="<?= App\Helpers\Network::home_url("assets/libs/dragula/dragula.min.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/libs/dom-autoscroller/dom-autoscroller.min.js") ?>"></script>
<script src="<?= App\Helpers\Network::home_url("assets/js/pages/todo.init.js") ?>"></script>
<?php
$additionJs = ob_get_clean();

include_once LAYOUTS_PATH . 'dashboard.php';
