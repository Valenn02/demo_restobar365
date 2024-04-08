<div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li @click="menu=42" class="nav-item">
                        <a class="nav-link active" href="#"><i class="fa fa-dashboard"></i> Escritorio</a>
                    </li>
                    <li class="nav-title">
                        Operaciones
                    </li>


                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-briefcase"  ></i> Restaurante</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Restaurante</a>
                    </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-list-alt"></i> Ventas</a>
                        <ul class="nav-dropdown-items">
                        <li @click="menu=16" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Apertura/Cierre Caja</a>
                            </li>
                            <li @click="menu=39" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ventas</a>
                            </li>
                            <li @click="menu=6" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Clientes</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tags"></i> Productos</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=2" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Productos</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Reportes</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=45" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Ventas Diarias</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-lock"></i> Accesos</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=7" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Usuarios</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>