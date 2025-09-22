<?php
$pageTitle = "Wallet";

ob_start();
?>

<div class="row">
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0  me-2">Recent Activity</h4>
                <div class="flex-shrink-0 ms-auto">
                    <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#today" role="tab">
                                Today
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#weekly" role="tab">
                                Weekly
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#monthly" role="tab">
                                Monthly
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="today" role="tabpanel">
                        <div class="profile-timeline">
                            <div class="accordion accordion-flush" id="todayExample">
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="headingOne">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Jacqueline Steve
                                                    </h6>
                                                    <small class="text-muted">We has changed 2 attributes on 05:16PM</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            In an awareness campaign, it is vital for people to begin put 2 and 2 together and begin to recognize your cause. Too much or too little spacing, as in the example below, can make things unpleasant for the reader. The goal is to make your text as comfortable to read as possible. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="headingTwo">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-light text-success rounded-circle">
                                                        M
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Megan Elmore
                                                    </h6>
                                                    <small class="text-muted">Adding a new event with attachments - 04:45PM</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            <div class="row g-2">
                                                <div class="col-auto">
                                                    <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-image-2-line fs-17 text-danger"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6>
                                                                <a href="javascript:void(0);" class="stretched-link">Business Template - UI/UX design</a>
                                                            </h6>
                                                            <small>685 KB</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-file-zip-line fs-17 text-info"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-0">
                                                                <a href="javascript:void(0);" class="stretched-link">Bank Management System - PSD</a>
                                                            </h6>
                                                            <small>8.78 MB</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="headingThree">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapsethree" aria-expanded="false">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-5.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1"> New ticket received</h6>
                                                    <small class="text-muted mb-2">User <span class="text-secondary">Erica245</span> submitted a ticket - 02:33PM</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="headingFour">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFour" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                                        <i class="ri-user-3-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Nancy Martino
                                                    </h6>
                                                    <small class="text-muted">Commented on 12:57PM</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5 fst-italic">
                                            " A wonderful serenity has
                                            taken possession of my
                                            entire soul, like these
                                            sweet mornings of spring
                                            which I enjoy with my whole
                                            heart. Each design is a new,
                                            unique piece of art birthed
                                            into this world, and while
                                            you have the opportunity to
                                            be creative and make your
                                            own style choices. "
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="headingFive">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseFive" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-7.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Lewis Arnold
                                                    </h6>
                                                    <small class="text-muted">Create new project buildng product - 10:05AM</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            <p class="text-muted mb-2"> Every team project can have a velzon. Use the velzon to share information with your team to understand and contribute to your project.</p>
                                            <div class="avatar-group">
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Christi">
                                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle avatar-xs">
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Frank Hook">
                                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle avatar-xs">
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title=" Ruby">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                            R
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="more">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle">
                                                            2+
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end accordion-->
                        </div>
                    </div>
                    <div class="tab-pane" id="weekly" role="tabpanel">
                        <div class="profile-timeline">
                            <div class="accordion accordion-flush" id="weeklyExample">
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading6">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse6" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Joseph Parker
                                                    </h6>
                                                    <small class="text-muted">New people joined with our company - Yesterday</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse6" class="accordion-collapse collapse show" aria-labelledby="heading6" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            It makes a statement, it’s
                                            impressive graphic design.
                                            Increase or decrease the
                                            letter spacing depending on
                                            the situation and try, try
                                            again until it looks right,
                                            and each letter has the
                                            perfect spot of its own.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading7">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse7" aria-expanded="false">
                                            <div class="d-flex">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title rounded-circle bg-light text-danger">
                                                        <i class="ri-shopping-bag-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Your order is placed <span class="badge bg-success-subtle text-success align-middle">Completed</span>
                                                    </h6>
                                                    <small class="text-muted">These customers can rest assured their order has been placed - 1 week Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading8">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse8" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-light text-success rounded-circle">
                                                        <i class="ri-home-3-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Velzon admin dashboard templates layout upload
                                                    </h6>
                                                    <small class="text-muted">We talked about a project on linkedin - 1 week Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse8" class="accordion-collapse collapse show" aria-labelledby="heading8" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5 fst-italic">
                                            Powerful, clean & modern
                                            responsive bootstrap 5 admin
                                            template. The maximum file
                                            size for uploads in this demo :
                                            <div class="row mt-2">
                                                <div class="col-xxl-6">
                                                    <div class="row border border-dashed gx-2 p-2">
                                                        <div class="col-3">
                                                            <img src="assets/images/small/img-3.jpg" alt="" class="img-fluid rounded" />
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-3">
                                                            <img src="assets/images/small/img-5.jpg" alt="" class="img-fluid rounded" />
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-3">
                                                            <img src="assets/images/small/img-7.jpg" alt="" class="img-fluid rounded" />
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-3">
                                                            <img src="assets/images/small/img-9.jpg" alt="" class="img-fluid rounded" />
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading9">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse9" aria-expanded="false">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-6.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        New ticket created <span class="badge bg-info-subtle text-info align-middle">Inprogress</span>
                                                    </h6>
                                                    <small class="text-muted mb-2">User <span class="text-secondary">Jack365</span> submitted a ticket - 2 week Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading10">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse10" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-5.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Jennifer Carter
                                                    </h6>
                                                    <small class="text-muted">Commented - 4 week Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse10" class="accordion-collapse collapse show" aria-labelledby="heading10" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            <p class="text-muted fst-italic mb-2">
                                                " This is an awesome
                                                admin dashboard
                                                template. It is
                                                extremely well
                                                structured and uses
                                                state of the art
                                                components (e.g. one of
                                                the only templates using
                                                boostrap 5.1.3 so far).
                                                I integrated it into a
                                                Rails 6 project. Needs
                                                manual integration work
                                                of course but the
                                                template structure made
                                                it easy. "</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end accordion-->
                        </div>
                    </div>
                    <div class="tab-pane" id="monthly" role="tabpanel">
                        <div class="profile-timeline">
                            <div class="accordion accordion-flush" id="monthlyExample">
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading11">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse11" aria-expanded="false">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-light text-success rounded-circle">
                                                        M
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Megan Elmore
                                                    </h6>
                                                    <small class="text-muted">Adding a new event with attachments - 1 month Ago.</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse11" class="accordion-collapse collapse show" aria-labelledby="heading11" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            <div class="row g-2">
                                                <div class="col-auto">
                                                    <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-image-2-line fs-17 text-danger"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-0">
                                                                <a href="javascript:void(0);" class="stretched-link">Business Template - UI/UX design</a>
                                                            </h6>
                                                            <small>685 KB</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-file-zip-line fs-17 text-info"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-0">
                                                                <a href="javascript:void(0);" class="stretched-link">Bank Management System - PSD</a>
                                                            </h6>
                                                            <small>8.78 MB</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="d-flex border border-dashed p-2 rounded position-relative">
                                                        <div class="flex-shrink-0">
                                                            <i class="ri-file-zip-line fs-17 text-info"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="mb-0">
                                                                <a href="javascript:void(0);" class="stretched-link">Bank Management System - PSD</a>
                                                            </h6>
                                                            <small>8.78 MB</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading12">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse12" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Jacqueline Steve
                                                    </h6>
                                                    <small class="text-muted">We has changed 2 attributes on 3 month Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse12" class="accordion-collapse collapse show" aria-labelledby="heading12" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            In an awareness campaign, it
                                            is vital for people to begin
                                            put 2 and 2 together and
                                            begin to recognize your
                                            cause. Too much or too
                                            little spacing, as in the
                                            example below, can make
                                            things unpleasant for the
                                            reader. The goal is to make
                                            your text as comfortable to
                                            read as possible. A
                                            wonderful serenity has taken
                                            possession of my entire
                                            soul, like these sweet
                                            mornings of spring which I
                                            enjoy with my whole heart.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading13">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse13" aria-expanded="false">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-5.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        New ticket received
                                                    </h6>
                                                    <small class="text-muted mb-2">User <span class="text-secondary">Erica245</span> submitted a ticket - 5 month Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading14">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse14" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                                        <i class="ri-user-3-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Nancy Martino
                                                    </h6>
                                                    <small class="text-muted">Commented on 24 Nov, 2021.</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse14" class="accordion-collapse collapse show" aria-labelledby="heading14" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5 fst-italic">
                                            " A wonderful serenity has
                                            taken possession of my
                                            entire soul, like these
                                            sweet mornings of spring
                                            which I enjoy with my whole
                                            heart. Each design is a new,
                                            unique piece of art birthed
                                            into this world, and while
                                            you have the opportunity to
                                            be creative and make your
                                            own style choices. "
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <div class="accordion-header" id="heading15">
                                        <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapse15" aria-expanded="true">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-7.jpg" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="fs-14 mb-1">
                                                        Lewis Arnold
                                                    </h6>
                                                    <small class="text-muted">Create new project buildng product - 8 month Ago</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="collapse15" class="accordion-collapse collapse show" aria-labelledby="heading15" data-bs-parent="#accordionExample">
                                        <div class="accordion-body ms-2 ps-5">
                                            <p class="text-muted mb-2">
                                                Every team project can
                                                have a velzon. Use the
                                                velzon to share
                                                information with your
                                                team to understand and
                                                contribute to your
                                                project.</p>
                                            <div class="avatar-group">
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Christi">
                                                    <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle avatar-xs">
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="Frank Hook">
                                                    <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle avatar-xs">
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title=" Ruby">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                            R
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="" data-bs-original-title="more">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle">
                                                            2+
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end accordion-->
                        </div>
                    </div>
                </div>
            </div><!-- end card body -->
        </div>

    </div>
    <!--end col-->

    <div class="col-xxl-3">
        <div class="card overflow-hidden">
            <div class="card-body bg-warning-subtle">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="fs-13 mb-3">My Portfolio</h5>
                        <h2>$61,91,967<small class="text-muted fs-14">.29</small></h2>
                        <p class="text-muted mb-0">$25,10,974 <small class="badge bg-success-subtle text-success"><i class="ri-arrow-right-up-line fs-13 align-bottom"></i>4.37%</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-wallet-outline text-primary h1"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="fs-13 mb-3">Today's Profit</h5>
                        <h2>$2,74,365<small class="text-muted fs-14">.84</small></h2>
                        <p class="text-muted mb-0">$9,10,564 <small class="badge bg-success-subtle text-success"><i class="ri-arrow-right-up-line fs-13 align-bottom"></i>1.25%</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-hand-coin-line text-primary h1"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="fs-13 mb-3">Overall Profit</h5>
                        <h2>$32,67,120<small class="text-muted fs-14">.42</small></h2>
                        <p class="text-muted mb-0">$18,22,730 <small class="badge bg-success-subtle text-success"><i class="ri-arrow-right-up-line fs-13 align-bottom"></i>8.34%</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-line-chart-line text-primary h1"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--end card-->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Transaction</h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        <img src="assets/images/svg/crypto-icons/btc.svg" alt="" class="avatar-xxs" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Bitcoin (BTC)</h6>
                        <p class="text-muted mb-0">Today</p>
                    </div>
                    <div>
                        <h6 class="text-danger mb-0">- $422.89</h6>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        <img src="assets/images/svg/crypto-icons/ltc.svg" alt="" class="avatar-xxs" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Litecoin (LTC)</h6>
                        <p class="text-muted mb-0">Yesterday</p>
                    </div>
                    <div>
                        <h6 class="text-success mb-0">+ $784.20</h6>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        <img src="assets/images/svg/crypto-icons/xmr.svg" alt="" class="avatar-xxs" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Monero (XMR)</h6>
                        <p class="text-muted mb-0">01 Jan, 2022</p>
                    </div>
                    <div>
                        <h6 class="text-danger mb-0">- $356.74</h6>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        <img src="assets/images/svg/crypto-icons/fil.svg" alt="" class="avatar-xxs" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Filecoin (FIL)</h6>
                        <p class="text-muted mb-0">30 Dec, 2021</p>
                    </div>
                    <div>
                        <h6 class="text-success mb-0">+ $1,247.00</h6>
                    </div>
                </div>
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0">
                        <img src="assets/images/svg/crypto-icons/dot.svg" alt="" class="avatar-xxs" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Polkadot (DOT)</h6>
                        <p class="text-muted mb-0">27 Dec, 2021</p>
                    </div>
                    <div>
                        <h6 class="text-success btn mb-0">+ $7,365.80</h6>
                    </div>
                </div>
                <div>
                    <a href="apps-crypto-transactions.html" class="btn btn-soft-info w-100">View All Transactions <i class="ri-arrow-right-line align-bottom"></i></a>
                </div>
            </div>
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>

<?php
$pageContent = ob_get_clean();
