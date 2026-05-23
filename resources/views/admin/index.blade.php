@extends('main.app')

@section('title', 'Lina Admin')

@section('custom-css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand-block">
            <div class="brand-mark">L</div>
            <div>
                <h1>Lina</h1>
                <p>Admin Console</p>
            </div>
        </div>

        <nav class="admin-nav" id="adminNav"></nav>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <div>
                <p class="eyebrow" id="roleLabel">Admin</p>
                <h2 id="pageTitle">Dashboard</h2>
            </div>
            <div class="topbar-actions">
                <span id="userLabel"></span>
                <button type="button" class="icon-button" id="refreshButton" title="Refresh">
                    <i class="fa-solid fa-rotate"></i>
                </button>
                <button type="button" class="icon-button danger" id="logoutButton" title="Log out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </div>
        </header>

        <div class="alert d-none" id="adminAlert" role="alert"></div>

        <section id="overviewPanel" class="panel-view">
            <div class="metric-grid" id="metricGrid"></div>
            <div class="content-grid">
                <section class="surface">
                    <div class="section-heading">
                        <h3>Pending Requests</h3>
                        <button class="btn btn-sm btn-outline-primary" data-target-resource="bookingRequests">Open</button>
                    </div>
                    <div class="list-stack" id="pendingRequests"></div>
                </section>
                <section class="surface">
                    <div class="section-heading">
                        <h3>Room Assignments</h3>
                        <button class="btn btn-sm btn-outline-primary" data-target-resource="classRoomBookings">Open</button>
                    </div>
                    <div class="list-stack" id="assignmentList"></div>
                </section>
            </div>
        </section>

        <section id="resourcePanel" class="panel-view d-none">
            <div class="resource-layout">
                <section class="surface form-surface">
                    <div class="section-heading">
                        <h3 id="formTitle">Create</h3>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearFormButton">Clear</button>
                    </div>
                    <form id="resourceForm" class="resource-form"></form>
                </section>

                <section class="surface table-surface">
                    <div class="section-heading table-heading">
                        <h3 id="tableTitle">Records</h3>
                        <div class="toolbar">
                            <input type="text" class="form-control form-control-sm" id="paramInput" placeholder="ID, range, or *">
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="showParamButton">Show</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="deletedParamButton">Deleted</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle" id="resourceTable"></table>
                    </div>
                </section>
            </div>
        </section>
    </main>
</div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/admin.js') }}"></script>
@endsection
