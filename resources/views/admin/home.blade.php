@extends('layouts.app')

@section('content')
    <!-- Container-fluid starts-->
    <div class="row">
        <div class="col-6">
            <h3 class="mt-2">Dashboard</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Instructors</p>
                            <h4 class="mb-0">{{ $instructorCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="fas fa-user-tie font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('faculty.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Students</p>
                            <h4 class="mb-0">{{ $studentCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-user-graduate font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('users.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Subjects</p>
                            <h4 class="mb-0">{{ $subjectCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-book font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('subject.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Classes</p>
                            <h4 class="mb-0">{{ $classCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-user-friends font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('classes.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Departments</p>
                            <h4 class="mb-0">{{ $departmentCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary align-self-center">
                                <span class="avatar-title">
                                    <i class="far fa-building font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('department.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Rooms</p>
                            <h4 class="mb-0">{{ $roomCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-house-user font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('room.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Users</p>
                            <h4 class="mb-0">{{ $userCount }}</h4>
                        </div>

                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-users font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary text-center">
                    <a href="{{ route('users.index') }}" class="text-white"> Show All Details</a>
                </div>
            </div>
        </div>
    </div>
@endsection
