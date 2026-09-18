<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">{{ config('app.name') }}</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">

            @if (auth()->user()->role === 'tutor')
                <!--begin::Sidebar Menu-->
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                    aria-label="Main navigation" data-accordion="false" id="navigation">

                    <li class="nav-item">
                        <a href="{{ route('tutor.dashboard') }}" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-mortarboard-fill"></i>
                            <p>
                                Students
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('tutor.students.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Manage Students</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tutor.students.create') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Create Student</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-box-seam-fill"></i>
                            <p>
                                Sessions
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('tutor.sessions.index') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Manage Sessions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tutor.sessions.create') }}" class="nav-link">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Create Session</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!--end::Sidebar Menu-->
            @elseif(auth()->user()->role === 'student')
                <!--begin::Sidebar Menu-->
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                    aria-label="Main navigation" data-accordion="false" id="navigation">

                    <li class="nav-item">
                        <a href="{{ route('student.dashboard') }}" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('student.upcoming-sessions') }}" class="nav-link">
                            <i class="nav-icon bi bi-box-seam-fill"></i>
                            <p>Upcoming Sessions</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('student.session-notes') }}" class="nav-link">
                            <i class="nav-icon bi bi-pencil-square"></i>
                            <p>Session Notes</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('student.homeworks') }}" class="nav-link">
                            <i class="nav-icon bi bi-mortarboard"></i>
                            <p>Homework</p>
                        </a>
                    </li>
                </ul>
                <!--end::Sidebar Menu-->
            @endif

        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
