@php
    use App\Http\Controllers\api\template\TemplateMenuController;
@endphp
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                @php
                    $apiMenu = new TemplateMenuController();
                @endphp
                {!! $apiMenu->generateMenuWeb() !!}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
