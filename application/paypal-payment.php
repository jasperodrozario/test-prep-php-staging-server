<?php
include("../configs/autoload.php");
include("../smtpmail.php");

function getPayPalAccessToken() {
    $clientId = 'AUKaUC4G2i39Af_KA_EYVK4a0x1pXBP-zt5yEf3PcxeUbyJqm4EQICfZkyKR23_dzh5uhCyL7P1TB8HF'; // Replace with your PayPal Client ID
    $clientSecret = 'EM1SVqqL5eusOZFcoSSfnAM-ZzMraBkWXrO94HSxp1t1oZssUwUbonDsMc02pf3LAjV8dtNPw2Lgx1mA'; // Replace with your PayPal Client Secret
    $url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token'; // Sandbox URL
//    $url = 'https://api-m.paypal.com/v1/oauth2/token'; 

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    $data = json_decode($response, true);
    curl_close($ch);

    if (isset($data['access_token'])) {
        return $data['access_token']; // Return the token
    } else {
        echo "Error fetching access token: ";
        print_r($data);
        return null;
    }
}

function getCachedAccessToken() {
    $cacheFile = 'paypal_token_cache.json'; // File to store the token

    // Check if the cache file exists and is not expired
    if (file_exists($cacheFile)) {
        $cacheData = json_decode(file_get_contents($cacheFile), true);
        if ($cacheData['expires_at'] > time()) {
            return $cacheData['access_token'];
        }
    }

    // Fetch a new token if the cache is expired or doesn't exist
    $accessToken = getPayPalAccessToken();
    if ($accessToken) {
        $cacheData = [
            'access_token' => $accessToken,
            'expires_at' => time() + 3600 * 8 // Cache for 8 hours
        ];
        file_put_contents($cacheFile, json_encode($cacheData));
        return $accessToken;
    }

    return null; // Return null if unable to get a token
}


// Your PayPal access token
$accessToken = getCachedAccessToken();
if (!$accessToken) {
    die("Error: Unable to get a PayPal access token.");
}
$requestId = uniqid(); // Generate a unique request ID

// Product data to send to PayPal
$productData = [
    "name" => "Video Streaming Service",
    "description" => "A video streaming service",
    "type" => "SERVICE",
    "category" => "SOFTWARE",
    // "image_url" => "",
    "home_url" => "https://staging-officers.asvab-tutoring.com/thank-you"
];

// Initialize cURL
$ch = curl_init('https://api-m.sandbox.paypal.com/v1/catalogs/products');
//$ch = curl_init('https://api-m.paypal.com/v1/catalogs/products');

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken,
    'PayPal-Request-Id: ' . $requestId
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));

// Execute the cURL request and capture the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Decode the response
    $responseData = json_decode($response, true);
    
    // Check if the product was created successfully
    if (isset($responseData['id'])) {
        // echo "<br><br><br><br><br><br>Product created successfully. Product ID: " . $responseData['id'];
    } else {
        // Handle error response from PayPal
        echo "Error creating product: " . print_r($responseData, true);
    }
}

// Close the cURL session
curl_close($ch);

if($_POST['duration']=='1 month'){
    $month = 1;
}
else if($_POST['duration']=='3 months'){
    $month = 3;
}
else if($_POST['duration']=='6 months'){
    $month = 6;
}
else if($_POST['duration']=='12 months'){
    $month = 12;
}

// Billing plan data to send to PayPal
$billingPlanData = [
    "product_id" => $responseData['id'], // Replace with your actual product ID
    "name" => "Basic Plan", // Change to your desired store name
    "description" => "Basic Plan", // Description remains unchanged
    "billing_cycles" => [
        [
            "frequency" => [
                "interval_unit" => "DAY",//"MONTH",
                "interval_count" => 1//$month
            ],
            "tenure_type" => "REGULAR", // Changed from TRIAL to REGULAR
            "sequence" => 1,
            "total_cycles" => 0, // 0 means it will continue indefinitely
            "pricing_scheme" => [
                "fixed_price" => [
                    "value" => $_POST['amount'], // Set Basic Plan fee to $15
                    "currency_code" => "USD"
                ]
            ]
        ]
    ],
    "payment_preferences" => [
        "auto_bill_outstanding" => true,
        "setup_fee" => [
            "value" => "0", // Set setup fee to $0 if not applicable
            "currency_code" => "USD"
        ],
        "setup_fee_failure_action" => "CONTINUE",
        "payment_failure_threshold" => 1
    ],
    "taxes" => [
        "percentage" => "10",
        "inclusive" => false
    ]
];

// Initialize cURL session
$ch = curl_init('https://api-m.sandbox.paypal.com/v1/billing/plans');
//$ch = curl_init('https://api-m.paypal.com/v1/billing/plans');

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $accessToken,
    'Content-Type: application/json',
    'PayPal-Request-Id: ' . $requestId
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($billingPlanData));

// Execute the cURL request and capture the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Decode the response
    $bresponseData = json_decode($response, true);
    
    // Check if the billing plan was created successfully
    if (isset($bresponseData['id'])) {
        // echo "Billing plan created successfully. Plan ID: " . $bresponseData['id'];
    } else {
        // Handle error response from PayPal
        echo "Error creating billing plan: ";
        print_r($bresponseData);
    }
}

// Close the cURL session
curl_close($ch);

$data['plan_id'] = $bresponseData['id']; // Store the plan ID in data array

echo template($data);

?>
