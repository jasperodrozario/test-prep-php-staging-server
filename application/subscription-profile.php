<?php
include("../configs/autoload.php");
chklogin();

// ----------------------------------------------------
// ✅ Generate auth_token and store in login table (edu_login)
// NOTE: Always omit "edu_" in table name => use "login"
// ----------------------------------------------------

$uid = (int)($_SESSION['user_id'] ?? 0);

if ($uid > 0) {

    // 64-character secure token
    $auth_token = bin2hex(
        function_exists('random_bytes')
            ? random_bytes(32)
            : openssl_random_pseudo_bytes(32)
    );

    $now = date("Y-m-d H:i:s");

    // Check if login row exists
    $row = getDataByWhere("login", "login_id", ["user_id" => $uid]);
    $loginRow = (is_array($row) && isset($row[0]) && is_array($row[0])) ? $row[0] : $row;

    if (is_array($loginRow) && !empty($loginRow['login_id'])) {

        // Update existing row
        updateTable("login", [
            "auth_token"            => $auth_token,
            "auth_token_created_at" => $now
        ], ["user_id" => $uid]);

    }

    // Make token available to template
    $data['auth_token'] = $auth_token;
}

$data['user_id'] = $uid;
$data['test_names'] = getQuery("course", "*", "", "course_name");
$data['meta_title'] = "User Subscription";

// Join user + subscription (your existing logic)
$data['db_value'] = getDataByJoin(
    ["user as u", "subscription as s"],
    "u.*,s.*",
    ["u.user_id" => $_SESSION['user_id']],
    ["u.user_id" => "s.user_id"]
);

// ---------------------------------------------
// Config: Next.js endpoints  https://www.asvab-tutoring.com
// ---------------------------------------------
define("NEXTJS_CANCEL_URL",   $configs['nextjs_base_url'] . "/api/cancel-subscription");
define("NEXTJS_RESUME_URL",   $configs['nextjs_base_url'] . "/api/resume-subscription");
define("NEXTJS_INTERNAL_KEY", $configs['internalApiSecret']);

// ---------------------------------------------
// Helpers 
// ---------------------------------------------
function isAjaxRequest(): bool {
    $xrw = strtolower(trim($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
    return $xrw === 'xmlhttprequest';
}

function respondAjax(bool $ok, string $msg, array $extra = []) {
    http_response_code($ok ? 200 : 400);
    echo json_encode(array_merge([
        "ok"      => $ok,
        "message" => $msg,
    ], $extra));
    exit;
}

function redirectSelf() {
    header("Location: " . ($_SERVER['REQUEST_URI'] ?? ''));
    exit;
}

function normCancelStatus($v): string {
    $s = strtoupper(trim((string)$v));
    return $s === '' ? 'NONE' : $s;
}

function isCancelPendingLike(string $cancelStatus): bool {
    $s = strtoupper(trim($cancelStatus));
    return (
        $s === 'CANCEL_PENDING' ||
        $s === 'REQUESTED' ||
        $s === 'PENDING' ||
        strpos($s, 'CANCEL_PENDING') !== false ||
        strpos($s, 'PENDING') !== false
    );
}

function isSquareCanceledLike(string $squareStatus): bool {
    $s = strtoupper(trim($squareStatus));
    return ($s === 'CANCELED' || $s === 'CANCELLED' || $s === 'ENDED' || $s === 'EXPIRED');
}

/**
 * Server-side call to Next.js (waits for response)
 * Returns: [ok(bool), httpCode(int), res(array|null), raw(string), curlErr(string)]
 */
function callNextJsJson(string $url, array $payload): array {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_filter([
        "Content-Type: application/json",
        (NEXTJS_INTERNAL_KEY !== "" ? "X-Internal-Key: " . NEXTJS_INTERNAL_KEY : "")
    ]));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    // Harden for common hosting issues (ngrok + IPv6 routing)
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    $raw      = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($raw === false) {
        return [false, $httpCode, null, "", $curlErr];
    }

    $res = json_decode($raw, true);
    if (!is_array($res)) {
        return [false, $httpCode, null, (string)$raw, ""];
    }

    $ok = (bool)($res["ok"] ?? $res["isSuccess"] ?? false);
    if (!$ok || $httpCode < 200 || $httpCode >= 300) {
        return [false, $httpCode, $res, (string)$raw, ""];
    }

    return [true, $httpCode, $res, (string)$raw, ""];
}

/**
 * Read first row from getDataByWhere result, regardless of helper returning
 * associative row or list-of-rows.
 */
function firstRow($row) {
    if (is_array($row) && isset($row[0]) && is_array($row[0]) && !isset($row['subscription_id'])) {
        return $row[0];
    }
    return $row;
}

/**
 * Pick the "best" Square subscription row for a user:
 * 1) Prefer a row with status=ACTIVE if present.
 * 2) Otherwise return the first available row.
 */
function pickSquareSubRowForUser(int $user_id) {
    // Prefer ACTIVE row
    $active = getDataByWhere("square_subscriptions", "*", ["user_id" => $user_id, "status" => "ACTIVE"]);
    $activeRow = firstRow($active);
    if (is_array($activeRow) && !empty($activeRow['subscription_id'])) return $activeRow;

    // Fallback: any row
    $any = getDataByWhere("square_subscriptions", "*", ["user_id" => $user_id]);
    $anyRow = firstRow($any);
    if (is_array($anyRow) && !empty($anyRow['subscription_id'])) return $anyRow;

    return null;
}

/**
 * IMPORTANT: updateTable() cannot write SQL NULL; it writes empty strings.
 * So to CLEAR nullable fields, we run a tiny raw UPDATE setting columns to NULL.
 *
 * $whereSql should be a complete condition WITHOUT the "WHERE" keyword.
 * Example: subscription_id="abc" OR user_id=5
 */
function setColumnsNull(string $table, array $cols, string $whereSql): bool {
    global $configs;

    if (!$cols) return true;

    // Optional safety: whitelist columns that actually exist
    $allowed = [];
    $sql = "SHOW COLUMNS FROM " . $configs['prev'] . $table;
    $res = mysqli_query($configs['con'], $sql);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $allowed[] = $row['Field'];
        }
    }

    $setParts = [];
    foreach ($cols as $c) {
        if (!$allowed || in_array($c, $allowed, true)) {
            $setParts[] = $c . "=NULL";
        }
    }
    if (!$setParts) return true;

    $q = "UPDATE " . $configs['prev'] . $table . " SET " . implode(",", $setParts) . " WHERE " . $whereSql;
    return (bool)mysqli_query($configs['con'], $q);
}

// ---------------------------------------------
// Flash messages (PRG support)
// ---------------------------------------------
$data['success_message'] = $_SESSION['flash_success'] ?? '';
$data['error_message']   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// ---------------------------------------------
// ✅ Handle RESUME request
// ---------------------------------------------
if (isset($_POST['resume_subscription']) && $_POST['resume_subscription'] === 'resume') {

    $user_id = (int)($_SESSION['user_id'] ?? 0);

    if ($user_id <= 0) {
        if (isAjaxRequest()) respondAjax(false, "Unauthorized.");
        $_SESSION['flash_error'] = "Unauthorized.";
        redirectSelf();
    }

    $subRow = pickSquareSubRowForUser($user_id);

    if (!$subRow || empty($subRow['subscription_id'])) {
        if (isAjaxRequest()) respondAjax(false, "No Square subscription found for this user.");
        $_SESSION['flash_error'] = "No Square subscription found for this user.";
        redirectSelf();
    }

    $subscription_id = trim((string)$subRow['subscription_id']);
    $square_status   = trim((string)($subRow['status'] ?? ''));
    $cancel_eff      = trim((string)($subRow['cancel_effective_date'] ?? ''));
    $cancel_status   = normCancelStatus($subRow['cancel_status'] ?? 'NONE');

    if (!isCancelPendingLike($cancel_status)) {
        $msg = "Resume is only available for subscriptions that are pending cancellation.";
        if (isAjaxRequest()) respondAjax(false, $msg);
        $_SESSION['flash_error'] = $msg;
        redirectSelf();
    }

    $payload = [
        "subscription_id"       => $subscription_id,
        "user_id"               => $user_id,
        "status"                => $square_status,
        "cancel_effective_date" => $cancel_eff,
    ];

    [$ok, $httpCode, $res, $raw, $curlErr] = callNextJsJson(NEXTJS_RESUME_URL, $payload);

    if (!$ok) {
        $msg = "Resume failed. ";

        if ($curlErr !== '') {
            $msg .= "Network error: " . $curlErr;
        } elseif (is_array($res)) {
            $msg .= (string)($res["message"] ?? ("Next.js rejected. HTTP " . $httpCode));
        } else {
            $msg .= "Bad response from Next.js. HTTP " . $httpCode;
        }

        if (isAjaxRequest()) respondAjax(false, $msg, ["http" => $httpCode, "raw" => substr((string)$raw, 0, 500)]);
        $_SESSION['flash_error'] = $msg;
        redirectSelf();
    }

    // -----------------------------
    // ✅ Update DB after resume
    // -----------------------------
    global $configs;
    $now = date("Y-m-d H:i:s");

    $target_subscription_id = trim((string)($res["subscription_id"] ?? $subscription_id));
    if ($target_subscription_id === '') {
        $_SESSION['flash_error'] = "Resume succeeded but missing subscription_id to update DB.";
        redirectSelf();
    }

    // 1) Update only "normal" values using updateTable()
    $update = [
        "status"     => trim((string)($res["status"] ?? $square_status)),
        "updated_at" => $now,
    ];

    // next_billing_date: if Next.js gives "", do not write "", we will NULL it
    $nextBillingWasProvided = array_key_exists("next_billing_date", (array)$res);
    $nextBillingIsEmpty     = false;

    if ($nextBillingWasProvided) {
        $nb = trim((string)$res["next_billing_date"]);
        if ($nb !== '') {
            $update["next_billing_date"] = $nb;
        } else {
            $nextBillingIsEmpty = true;
        }
    }

    // Prefer updating by subscription_id
    updateTable("square_subscriptions", $update, ["subscription_id" => $target_subscription_id]);

    // Safety fallback by user_id
    updateTable("square_subscriptions", $update, ["user_id" => $user_id]);

    // 2) Clear cancel-related fields with real SQL NULL
    $escSubId = mysqli_real_escape_string($configs['con'], $target_subscription_id);

    setColumnsNull(
        "square_subscriptions",
        ["cancel_status", "cancel_effective_date", "canceled_at"],
        'subscription_id="' . $escSubId . '"'
    );

    // Clear next_billing_date if Next.js explicitly returned empty
    if ($nextBillingWasProvided && $nextBillingIsEmpty) {
        setColumnsNull(
            "square_subscriptions",
            ["next_billing_date"],
            'subscription_id="' . $escSubId . '"'
        );
    }

    // Also clear by user_id (in case of multiple rows / helper differences)
    $uidInt = (int)$user_id;
    setColumnsNull(
        "square_subscriptions",
        ["cancel_status", "cancel_effective_date", "canceled_at"],
        "user_id=" . $uidInt
    );

    if ($nextBillingWasProvided && $nextBillingIsEmpty) {
        setColumnsNull(
            "square_subscriptions",
            ["next_billing_date"],
            "user_id=" . $uidInt
        );
    }

    if (isAjaxRequest()) {
        respondAjax(true, "Subscription resumed successfully.", [
            "status" => $update["status"]
        ]);
    }

    $_SESSION['flash_success'] = "Subscription resumed successfully.";
    redirectSelf();
}

// ---------------------------------------------
// ✅ Handle CANCEL request
// ---------------------------------------------
if (isset($_POST['cancel_subscription']) && $_POST['cancel_subscription'] === 'cancel') {

    $user_id = (int)($_SESSION['user_id'] ?? 0);

    if ($user_id <= 0) {
        if (isAjaxRequest()) respondAjax(false, "Unauthorized.");
        $_SESSION['flash_error'] = "Unauthorized.";
        redirectSelf();
    }

    $cancel_reason = trim((string)($_POST['cancel_reason'] ?? ''));
    if ($cancel_reason === '') {
        if (isAjaxRequest()) respondAjax(false, "Cancel reason is required.");
        $_SESSION['flash_error'] = "Cancel reason is required.";
        redirectSelf();
    }

    $subRow = pickSquareSubRowForUser($user_id);

    if (!$subRow || empty($subRow['subscription_id'])) {
        if (isAjaxRequest()) respondAjax(false, "No Square subscription found for this user.");
        $_SESSION['flash_error'] = "No Square subscription found for this user.";
        redirectSelf();
    }

    $subscription_id = trim((string)$subRow['subscription_id']);
    $square_status   = trim((string)($subRow['status'] ?? ''));
    $cancel_status   = normCancelStatus($subRow['cancel_status'] ?? 'NONE');

    // Always save reason locally
    updateTable("square_subscriptions", [
        "cancel_subs_reason" => $cancel_reason,
        "updated_at"         => date("Y-m-d H:i:s"),
    ], ["user_id" => $user_id]);

    // If already canceled in Square, just mark internal and refresh
    if (isSquareCanceledLike($square_status)) {
        updateTable("square_subscriptions", [
            "cancel_status" => "CANCELED",
            "updated_at"    => date("Y-m-d H:i:s"),
        ], ["user_id" => $user_id]);

        if (isAjaxRequest()) respondAjax(true, "Subscription is already canceled. Reason saved.");
        $_SESSION['flash_success'] = "Subscription is already canceled. Reason saved.";
        redirectSelf();
    }

    // If already pending internally, do not re-request
    if (isCancelPendingLike($cancel_status)) {
        if (isAjaxRequest()) respondAjax(true, "Cancellation is already pending. Reason saved.");
        $_SESSION['flash_success'] = "Cancellation is already pending. Reason saved.";
        redirectSelf();
    }

    $payload = [
        "subscription_id"       => $subscription_id,
        "user_id"               => $user_id,
        "status"                => $square_status,
        "cancel_effective_date" => "",
        "cancel_reason"         => $cancel_reason
    ];

    [$ok, $httpCode, $res, $raw, $curlErr] = callNextJsJson(NEXTJS_CANCEL_URL, $payload);

    if (!$ok) {
        $msg = "Cancel failed. ";

        if ($curlErr !== '') {
            $msg .= "Network error: " . $curlErr;
        } elseif (is_array($res)) {
            $msg .= (string)($res["message"] ?? ("Next.js rejected. HTTP " . $httpCode));
        } else {
            $msg .= "Bad response from Next.js. HTTP " . $httpCode;
        }

        if (isAjaxRequest()) respondAjax(false, $msg, ["http" => $httpCode, "raw" => substr((string)$raw, 0, 500)]);
        $_SESSION['flash_error'] = $msg;
        redirectSelf();
    }

    // ✅ Update DB after successful cancellation request
    $now = date("Y-m-d H:i:s");

    $update = [
        "cancel_status" => (!empty($res["status"]) ? trim((string)$res["status"]) : "CANCEL_PENDING"),
        "canceled_at"   => $now,
        "updated_at"    => $now,
    ];

    if (!empty($res["cancel_effective_date"])) {
        $update["cancel_effective_date"] = $res["cancel_effective_date"];
    }

    updateTable("square_subscriptions", $update, ["user_id" => $user_id]);

    if (isAjaxRequest()) respondAjax(true, "Cancellation requested successfully.", [
        "cancel_status"         => $update["cancel_status"],
        "cancel_effective_date" => $update["cancel_effective_date"] ?? ""
    ]);

    $_SESSION['flash_success'] = "Cancellation requested successfully.";
    redirectSelf();
}

// ---------------------------------------------
// Display logic
// NEW RULE: If at least one row exists in edu_square_subscriptions
// (table: square_subscriptions) with status=ACTIVE => show status ACTIVE
// ---------------------------------------------
$userId = (int)($_SESSION['user_id'] ?? 0);
$data['userId'] = $userId;

$is_square_subscriber = getTotalRow("square_subscriptions", ["user_id" => $userId]);
$is_active_user       = getTotalRow("user", ["user_id" => $userId, "activation_status" => "Y"]);

if (!$is_square_subscriber && !$is_active_user) {

    $data['db_value']['status'] = "Expired";
    

} elseif (!$is_square_subscriber && $is_active_user) {

    $data['db_value']['status'] = "Free";
    

}  else {
    $subRow = pickSquareSubRowForUser($userId);
    

    // Fallback-safe values from selected row
    $data['db_value']['next_billing_date'] = (string)($subRow['next_billing_date'] ?? '');
    $data['db_value']['cancel_effective_date'] = (string)($subRow['cancel_effective_date'] ?? '');
    $data['db_value']['cancel_status'] = (string)($subRow['cancel_status'] ?? '');
    $data['db_value']['subscription_start_date'] = (string)($subRow['start_date'] ?? '');
    $data['db_value']['subscription_id'] = (string)($subRow['subscription_id'] ?? '');

    // ✅ Enforce the rule: ANY ACTIVE row => status ACTIVE
    $hasActive = (int)getTotalRow("square_subscriptions", ["user_id" => $userId, "status" => "ACTIVE"]);
    if ($hasActive > 0 && $is_active_user) {
        $data['db_value']['status'] = "ACTIVE";
    } else if($hasActive > 0 && !$is_active_user){
        $data['db_value']['status'] = "EXPIRED";
    } else {
        // otherwise, use the selected row status
        $data['db_value']['status'] = (string)($subRow['status'] ?? '');
    }
}

$data['success_message'] = $data['success_message'] ?? '';
$data['error_message']   = $data['error_message'] ?? '';

echo template($data);
?>