<?php
if (!defined('_CODE')) {
    die("truy cap that bai");
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function  layout($layoutName = 'header', $data = [])
{
    if (file_exists(require_once(_WEB_PATH_TEMPLATES . '\\layout\\' . $layoutName . '.php'))) {
        require_once(require_once(_WEB_PATH_TEMPLATES . '\\layout\\' . $layoutName . '.php'));
    }
}


// hàm gửi mail
function sendMail($to, $subject, $content)
{

    // //Load Composer's autoloader (chưa học đến)
    // require 'vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hoangvanduc2504@gmail.com';                     //SMTP username
        $mail->Password   = 'rgdmootkleyqnope';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients 
        // mail gửi
        $mail->setFrom('hoangvanduc2504@gmail.com', 'vanduc');
        // mail nhận
        $mail->addAddress($to);     //Add a recipient


        //Content
        $mail->isHTML(true);
        $mail->CharSet = "UTF-8";                                //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        // bảo mật
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow-self_signed' => true,

            )
        );

        $sendMail  = $mail->send();
        // echo 'Gửi thành công';

        if ($sendMail) {
            return $sendMail;
        }
    } catch (Exception $e) {
        echo "lỗi: {$mail->ErrorInfo}";
    }
}

// kiểm tra phương thức GET

function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}

// kiểm tra phương thức POST
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

// hàm Filter lọc dữ liệu
function filter()
{
    $filterArr = [];
    if (isGet()) {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                // tác dụng loại bỏ tất cả các thẻ HTML và PHP trong một chuỗi văn bản, 
                //trả về chuỗi chỉ chứa nội dung văn bản thuần túy mà không có bất kỳ thẻ HTML hoặc PHP nào.
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    return $filterArr;
}

// kiểm tra email
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

// kiểm tra số nguyên
function isInt($number)
{
    $checkInt = filter_var($number, FILTER_VALIDATE_INT);
    return $checkInt;
}

// kiểm tra email tồn tại
function emailUnique($email)
{
    $sql = "SELECT id FROM users WHERE  email = '$email'";
    if (getCountRows($sql) > 0) {
        return false;
    }
    return true;
}

// kiểm tra số điện thoại
function isPhone($phone)
{
    $checkZero = false;
    // điều kiện 1 kí tự đầu tiên là 0
    if ($phone[0] == '0') {
        $checkZero = true;
        $phone = substr($phone, 1);
    }
    // điều kiện 2 đằng sau phải có 9 số  nguyên dương
    $checkNumber = false;
    if (isInt($phone) && (strlen($phone) == 9)) {
        $checkNumber = true;
    }

    if ($checkZero && $checkNumber) {
        return true;
    }
    return false;
}

// thông báo lỗi
function getSmg($smg, $type = 'success')
{
    echo "<div class = 'alert alert-$type'>";
    echo  $smg;
    echo '</div>';
}


// hàm chuyển hướng
function redirect($path = 'index.php')
{
    header("location: $path");
    exit;
}


// thông báo lỗi dưới

function form_error($errors, $fileName, $beforeHtml = '', $afterHtml = '')
{
    return (!empty($errors[$fileName])) ? $beforeHtml . reset($errors[$fileName]) . $afterHtml : null;
}

// hiển thị dữ liệu cũ

function oldData($old_data, $fileName, $defaul = null)
{
    return (!empty($old_data[$fileName])) ? $old_data[$fileName] : $defaul;
}


// hàm kiểm tra trạng thái đăng nhập
function isLogin()
{
    $checkLogin = false;
    if (getSession('tokenLogin')) {
        // kiểm tra token có giống trong bảng không
        $tokenLogin = getSession('tokenLogin');
        $queryToken = selectOne("SELECT user_Id FROM tokenLogin WHERE token = '$tokenLogin'");

        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('tokenLogin');
        }
    }
    return $checkLogin;
}

// kiểm tra sản phẩm tồn tại
function productUnique($product)
{
    $sql = "SELECT id FROM products WHERE name_product = '$product'";
    if (getCountRows($sql) > 0) {
        return false;
    }
    return true;
}
function getCountCart($userId)
{
    $rowCart = getCountRows("SELECT * FROM cart WHERE id_user = $userId");
    if ($rowCart > 0) {
        $cart = selectOne("SELECT * FROM cart WHERE id_user = $userId");
        $cartCount = $cart['count'];
    } else {
        $cartCount = 0;
    }
    return $cartCount;
}
function checkDie($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();
}
function confirm_vnpay()
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $tongtien = $_POST['sotien'];
    $vnp_TmnCode = "X69FAT2F"; //Mã định danh merchant kết nối (Terminal Id)
    $vnp_HashSecret = "3ZWPVYJ7YO2CGPQ3CLK5NCBN22ZP21ET"; //Secret key
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = _WEB_HOST . "/?module=checkouts&action=vnpay_return";
    $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
    //Config input format
    //Expire
    $startTime = date("YmdHis");
    $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

    //thanh toan bang vnpay
    $vnp_TxnRef = time() . "";
    $vnp_OrderInfo = 'Thanh toán đơn hàng đặt tại web';
    $vnp_OrderType = 'billpayment';

    $vnp_Amount = $tongtien * 100;
    $vnp_Locale = 'vn';
    $vnp_BankCode = 'NCB';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $vnp_ExpireDate = $expire;

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
        "vnp_ExpireDate" => $vnp_ExpireDate

    );


    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
    //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    // }

    //var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    $returnData = array(
        'code' => '00',
        'message' => 'success',
        'data' => $vnp_Url
    );
    if (isset($_POST['checkout_submit'])) {
        header('Location: ' . $vnp_Url);
        die();
    } else {
        print_r($returnData);
    }
}
