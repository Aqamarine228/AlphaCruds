<aside class="main-sidebar sidebar-light-primary elevation-3">
    <a href="#" class="brand-link">
        <img src="{{asset('vendor/alphacruds/img/logo.png')}}" alt="Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">AlphaCruds</span>
    </a>

    <a href="{{config('alphacruds.redirect_back_path')}}" class="brand-link" style="padding-left: 30px">
        <em class="fas fa-arrow-left"></em>
        <span class="brand-text font-weight-light">Back To Admin Panel</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2" aria-label="Aside navigation">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('alphacruds.crud-generator')}}" class="nav-link">
                        <em class="nav-icon fas fa-robot"></em>
                        <p>
                            Crud Generator
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('alphacruds.translated-crud-generator')}}" class="nav-link">
                        <em class="nav-icon fas fa-language"></em>
                        <p>
                            Translated Crud Generator
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('alphacruds.api-crud-generator')}}" class="nav-link">
                        <em class="nav-icon fas fa-code"></em>
                        <p>
                            API Crud Generator
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
