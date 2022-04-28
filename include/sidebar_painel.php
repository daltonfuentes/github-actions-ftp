<!--**********************************
        Sidebar start
    ***********************************-->
    <div class="deznav">
        <div class="deznav-scroll">
            <ul class="metismenu" id="menu">
                <li><a href="./home" class="ai-icon" aria-expanded="false">
                        <i class="fas fa-home fs-20"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li><a href="./painel-pedidos" class="ai-icon" aria-expanded="false">
                        <i class="fa-solid fa-solar-panel fs-20"></i>
                        <span class="nav-text">Painel de pedidos</span>
                    </a>
                </li>
            </ul>
            <div class="text-center merchant-status-ifood dropup">
                <button type="button" class="btn btn-light p-2 rounded-circle pulse merchant-close" data-toggle="dropdown" aria-expanded="false" style="height: 50px; width: 50px;">
                    <media class="">
                        <img class="platform animate__fadeIn animate__animated ifood-white d-none" height="16" src="images/icons/logo_ifood_4.png" alt="ifood">
                        <img class="platform animate__fadeIn animate__animated ifood-dark" height="16" src="images/icons/logo_ifood_5.png" alt="ifood">
                        <img class="d-none loading" height="30" src="images/loading.gif" alt="loading">
                    </media>
                </button>
                <div id="box_satus_ifood">
                </div>
            </div>
            <div class="text-center merchant-status-site dropup">
                <button type="button" class="btn btn-success p-2 rounded-circle pulse merchant-open" data-toggle="dropdown" aria-expanded="false" style="height: 50px; width: 50px;">
                    <media class="">
                        <img class="platform animate__fadeIn animate__animated ifood-white " height="16" src="images/icons/icon_shop.png" alt="shop">
                        <img class="platform animate__fadeIn animate__animated ifood-dark d-none" height="16" src="images/icons/icon_shop_2.png" alt="shop">
                        <img class="d-none loading" height="30" src="images/loading.gif" alt="loading">
                    </media>
                </button>
                <div class="dropdown-menu dropdown-menu-status py-3 px-4 bg-warning" style="width: 300px;">
                    <div class="media align-items-center">
                    <i class="fa-solid fa-circle-exclamation text-white fs-24 mr-3"></i><h4 class="fs-16 font-w600 text-white mb-0">Erro ao carregar status<br><span class="fs-14 font-w400">Problema com a conexão</span></h4>
                    </div>
                    
                </div>
            </div>
            
            
        </div>
    </div>
    <!--**********************************
        Sidebar end
    ***********************************-->