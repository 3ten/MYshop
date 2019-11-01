<!--<div class="menu">-->
<!--    <div class="row">-->



<!--            <a href="admin.php"><span class="menu_logo"><i class="fas fa-user-cog fa-3x"></i></span></a>-->
<!--            <a href="order_list.php"><span class="menu_logo"><i class="fas fa-clipboard-list fa-3x"></i></span></a>-->

<!--        <input type="text" placeholder="Поиск" oninput="searchInput(this, '.mainBox')" id="search">-->
<!--    </div>-->
<!--</div>-->
<nav class="navbar navbar-expand-lg navbar-dark bg-light fixed-top menu bg-dark">
    <a class="navbar-brand" href="index.php">Деревенская корзинка</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php"><i class="fas fa-store-alt fa-1x"></i> Магазин <span
                            class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="order.php"><i class="fas fa-shopping-basket fa-1x"></i> Корзина</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Инструменты
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <?php if ($_SESSION['ROLE'] == '0') { ?>
                    <a class="dropdown-item" href="admin.php"><i class="fas fa-user-cog fa-1x"></i> Админка</a>
                    <a class="dropdown-item" href="order_list.php"><i class="fas fa-user-cog fa-1x"></i> Список заказов</a>
                    <?php } ?>
                    <a class="dropdown-item" href="order_tracking.php"><i class="fas fa-truck fa-1x"></i> Отследить заказ</a>
                    <a class="dropdown-item" href="home.php"><i class="fas fa-user-circle fa-1x"></i> Кабинет</a>
<!--                    <div class="dropdown-divider"></div>-->
<!--                    <a class="dropdown-item" href="#">Something else here</a>-->
                </div>
            </li>

        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" aria-label="Search" placeholder="Поиск"
                   oninput="searchInput(this, '.mainBox')" id="search">

            <!--            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">-->
            <!--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>-->
        </form>
    </div>
</nav>
