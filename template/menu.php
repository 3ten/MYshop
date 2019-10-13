<div class="menu">
    <div class="row">
        <a href="order.php"><span class="menu_logo"><i class="fas fa-shopping-basket fa-3x"></i></span></a>
        <a href="home.php"><span class="menu_logo"><i class="fas fa-user-circle fa-3x"></i></span></a>
        <a href="index.php"><span class="menu_logo"><i class="fas fa-store-alt fa-3x"></i></span></a>
        <a href="order_tracking.php"> <span class="menu_logo"><i class="fas fa-truck fa-3x"></i></span></a>
        <?php if ($_SESSION['ROLE'] == '0') { ?>
            <a href="admin.php"><span class="menu_logo"><i class="fas fa-user-cog fa-3x"></i></span></a>
            <a href="order_list.php"><span class="menu_logo"><i class="fas fa-clipboard-list fa-3x"></i></span></a>
        <?php } ?>
        <input type="text" placeholder="Поиск" oninput="searchInput(this, '.mainBox')" id="search">
    </div>
</div>