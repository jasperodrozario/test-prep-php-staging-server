<?
include("../configs/autoload.php");

// Initialize session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("location:https://staging-test-prep.asvab-tutoring.com/login");

if ($_GET['email'] && $_GET['password']) {
    $exists = getTotalRow("user", array("email" => $_GET['email'], "password" => $_GET['password']));

    if ($exists) {
        //			    $d=getDataByJoin(array("subscription as s","user as u", "login as l"),"s.course_id,s.expire_date,u.*,l.last_login",array("u.email"=>$_POST['email'],"u.password"=>$_POST['password']),array("u.user_id" => "s.user_id", "u.user_id" => "l.user_id"));

        if (!empty($_GET['course_id'])){

            $user = getDataByJoin(array("user as u", "subscription as s"), "u.*", array("u.email" => $_GET['email'], "u.password" => $_GET['password'], "s.course_id" => (int)$_GET['course_id']), array("u.user_id" => "s.user_id"));

            $d = getDataByJoin(array("user as u", "login as l"), "u.*,l.last_login", array("u.user_id" => $user['user_id']), array("u.user_id" => "l.user_id"));
            
            
        } else{
            $d = getDataByJoin(array("user as u", "login as l"), "u.*,l.last_login", array("u.email" => $_GET['email'], "u.password" => $_GET['password']), array("u.user_id" => "l.user_id"));
        }
        
        $course_id = getFldValueById("subscription", "course_id", array("user_id" => $d["user_id"]));
        $expire_date = getFldValueById("subscription", "expire_date", array("user_id" => $d["user_id"]));

        $_SESSION['user_id']      = $d["user_id"];
        $_SESSION['status']       = $d["activation_status"];
        $_SESSION['email']        = $d['email'];
        $_SESSION['name']         = $d['first_name'] . " " . $d['last_name'];
        $_SESSION['user_type']      = $d['user_type'];
        $_SESSION['reg_date']      = $d['reg_date'];
        $_SESSION['last_login']      = $d['last_login'];
        $_SESSION['test_id']      = $course_id;
        $_SESSION['old_user_id']  = $d["old_user_id"];
        $_SESSION['has_multi_courses']  = $exists;

        // If course_id is missing, try to recover it via the session_id in the subscription table
        if (!$course_id) {
            $session_id = getFldValueById("subscription", "session_id", array("user_id" => $d["user_id"]));
            if ($session_id) {
                $recovered_course_id = getFldValueById("asvab_tutor_package_sessions", "course_id", array("session_id" => $session_id));
                if ($recovered_course_id) {
                    // Backfill the missing course_id into the subscription table
                    updateTable("subscription", array("course_id" => $recovered_course_id), array("user_id" => $d["user_id"]));
                    $course_id = $recovered_course_id;
                    $_SESSION['test_id']   = $course_id;
                }
            }
        }

        if ($course_id) {
            $_SESSION['test_name'] = getFldValueById("course", "course_name", array("course_id" => $course_id));
        }

        if ($expire_date < date('Y-m-d') && $d['user_type'] != "Tutor") {
            updateTable("user", ["activation_status" => "N"], ["user_id" => $d["user_id"]]);
            $_SESSION['status'] = "N";
            // $_SESSION['checking'] = "con: ".  date('Y-m-d');
        }

        #update last login

        updateTable("login", array("last_login" => date("Y-m-d H:i:s")), array("user_id" => $d['user_id']));

        if($_GET['platform'] == "admin"){
            header("location:" . $configs['vpath'] . "exam/progress-report" );
        } else{
            header("location:" . $configs['vpath']);
        }

    } else {
        $data['msg'] = $configs['error_msg'];
    }
}

echo template($data);
