<div id="sidebarMenu" class="collapse in">
    <div class="sidebar">
        <div class="nav-profile"></div>
        <ul class="nav-sidebar">
            <?php
            foreach($menus as $rw) {
                $main_name = stripslashes($rw['name']);
                $class = ($rw['id']==$_REQUEST['mid']) ? "open" : "";

                $admin_url = !empty($rw['url']) ? $rw['url'] : "#";

                if(count($sub_menu[$rw['id']])) {
                    echo '<li class="nav-dropdown ' . $class . '">
                        <a href="'.$admin_url.'"><i class="ion-navicon-round"></i>'.$main_name.'
                        <i class="ion-chevron-right pull-right"></i></a>';
                    echo "<ul>";
                    foreach($sub_menu[$rw['id']] as $d) {
                        $sub_name = stripslashes($d['name']);
                        $admin_url2 = !empty($d['url']) ? $d['url'] : "#";
                        echo '<li><a href="'.$admin_url2.'?mid=' . $rw['id'] .'">'.$sub_name.'</a></li>';
                    }
                    echo "</ul></li>";
                } else {
                    echo '<li><a href="'.$admin_url.'"><i class="ion-navicon-round"></i>'.$main_name.'</a></li>';
                }
            }
            ?>
        </ul>

    </div>
</div>

<style> 
.fixed-leftside .leftside .nav-sidebar {
    height: 550px;
}
</style> 

