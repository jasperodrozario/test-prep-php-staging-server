<?php
/*
 * Revenue rules shared by the admin reports (Web Analytics, Data Analysis Dashboard).
 *
 * Every payment is counted ONCE, from the ledger that recorded it, in dollars:
 *
 * 1. Square ledger — edu_square_payments
 *    Every Square payment (web checkout AND app users paying through Square) is recorded here by
 *    the Square webhook. Amounts are stored in CENTS -> divided by 100. Only COMPLETED payments count.
 *    The payment is App revenue when the payer is an app user, otherwise Web revenue
 *    (including payments whose payer could not be matched to an account).
 *
 * 2. App-store ledger — edu_subscription rows of app users with no Square payment mode
 *    (payment_mode NULL or ''): Apple, Google Play, PayPal and in-app purchases.
 *    Stored in DOLLARS, but some app builds sent CENTS (e.g. 1499 for $14.99, 1500 for $15.00).
 *    No app product costs $100 or more, so amounts >= 100 are treated as cents.
 *    Rows with any other payment_mode (square_subscription, CARD, ...) were paid through Square,
 *    are already counted in ledger 1, and are skipped here so nothing is counted twice.
 *
 * Tester accounts (edu_testing_account, marked on the Student List page) are excluded from both.
 */

// Accounts excluded from revenue
function revenueTesterIds()
{
	global $configs;
	return "(SELECT user_id FROM " . $configs['prev'] . "testing_account)";
}

// ---- Ledger 1: Square (cents) ----

// Table expression for getQuery()/getDataByWhere() (the first table gets the prefix automatically)
function revenueSquareFrom()
{
	global $configs;
	return "square_payments AS p LEFT JOIN " . $configs['prev'] . "user AS ru ON ru.user_id = p.user_id";
}

function revenueSquareAmount()
{
	return "p.amount / 100";
}

function revenueSquareDate()
{
	return "DATE(p.created_at)";
}

// $source: 'app', 'web' or 'all'
function revenueSquareWhere($source = 'all', $from = '', $to = '')
{
	$where = "p.payment_status = 'COMPLETED' AND p.amount > 0 AND (p.user_id IS NULL OR p.user_id NOT IN " . revenueTesterIds() . ")";
	if ($source === 'app') {
		$where .= " AND ru.reg_at LIKE '%app%'";
	} elseif ($source === 'web') {
		$where .= " AND (ru.user_id IS NULL OR ru.reg_at IS NULL OR ru.reg_at NOT LIKE '%app%')";
	}
	if ($from !== '' && $to !== '') {
		$where .= " AND p.created_at BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
	}
	return $where;
}

// ---- Ledger 2: App store (dollars, with legacy cents rows) ----

function revenueAppStoreFrom()
{
	global $configs;
	return "subscription AS rs INNER JOIN " . $configs['prev'] . "user AS ru ON ru.user_id = rs.user_id";
}

function revenueAppStoreAmount()
{
	return "CASE WHEN rs.amount >= 100 THEN rs.amount / 100 ELSE rs.amount END";
}

function revenueAppStoreDate()
{
	return "rs.subscription_date";
}

function revenueAppStoreWhere($from = '', $to = '')
{
	$where = "ru.reg_at LIKE '%app%' AND rs.amount > 0"
		. " AND (rs.payment_mode IS NULL OR rs.payment_mode = '')"
		. " AND rs.user_id NOT IN " . revenueTesterIds();
	if ($from !== '' && $to !== '') {
		$where .= " AND rs.subscription_date BETWEEN '$from' AND '$to'";
	}
	return $where;
}

// ---- Totals ----

// Total revenue in dollars for a date range (empty range = all time). $source: 'app', 'web' or 'all'
function revenueTotal($source = 'all', $from = '', $to = '')
{
	$total = 0;

	$row = getDataByWhere(revenueSquareFrom(), "COALESCE(SUM(" . revenueSquareAmount() . "), 0) AS total", revenueSquareWhere($source, $from, $to));
	$total += $row ? (float) $row['total'] : 0;

	if ($source !== 'web') {
		$row = getDataByWhere(revenueAppStoreFrom(), "COALESCE(SUM(" . revenueAppStoreAmount() . "), 0) AS total", revenueAppStoreWhere($from, $to));
		$total += $row ? (float) $row['total'] : 0;
	}

	return round($total, 2);
}
