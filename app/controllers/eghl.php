<?php
include '../../config.php';

class formEGHLsend
{

    private $URL = null;
    protected $TransactionType = null;
    protected $PymtMethod = null;
    protected $ServiceID = null;
    protected $PaymentID = null;
    protected $OrderNumber = null;
    protected $PaymentDesc = null;
    protected $MerchantReturnURL = null;
    protected $MerchantCallBackURL = null;
    protected $Amount = null;
    protected $CurrencyCode = null;
    protected $HashValue = null;
    protected $CustIP = null;
    protected $CustName = null;
    protected $CustEmail = null;
    protected $CustPhone = null;
    protected $PageTimeout = null;
    protected $MerchantTermsURL = null;

    // Define all hash value components specifiying if they are mandatory or not
    private $hash_components = array( //Param => is_mandatory
        'ServiceID' => true,
        'PaymentID' => true,
        'MerchantReturnURL' => true,
        'MerchantCallBackURL' => false,
        'Amount' => true,
        'CurrencyCode' => true,
        'CustIP' => true,
        'PageTimeout' => false,
    );

    // Define all post params specifiying if they are mandatory or not
    private $post_vars = array( //Param => is_mandatory
        'TransactionType' => true,
        'PymtMethod' => true,
        'ServiceID' => true,
        'PaymentID' => true,
        'OrderNumber' => true,
        'PaymentDesc' => true,
        'MerchantReturnURL' => true,
        'MerchantCallBackURL' => false,
        'Amount' => true,
        'CurrencyCode' => true,
        'HashValue' => true,
        'CustIP' => true,
        'CustName' => true,
        'CustEmail' => true,
        'CustPhone' => true,
        'PageTimeout' => true,
        'MerchantTermsURL' => false,
    );

    // Will contain the HTTP Query format of post params
    private $post_args = null;

    public function __construct($URL = null)
    {
        if (is_null($URL)) {
            echo "Payment URL is not provided</br>";
        } else {
            $this->URL = $URL;
        }
    }

    // Method to get the value of protected/private variable of this class
    public function get($attr)
    {
        return $this->$attr;
    }

    // Method to set the value of protected/private variable of this class
    public function set($attr, $value)
    {
        $this->$attr = $value;
    }

    public function getRequestParams()
    {
        $exempted_attr = array('URL', 'hash_components', 'post_args', 'post_vars');
        $returnData = array();
        $args = get_object_vars($this);
        foreach ($args as $ind => $val) {
            if (!in_array($ind, $exempted_attr)) {
                $returnData[$ind] = $val;
            }
        }
        return $returnData;
    }

    //Calling this function will automatically populate all post variables via $_REQUEST
    public function getValuesFromRequest()
    {
        $exempted_attr = array('URL', 'hash_components', 'post_args', 'post_vars');
        $args = get_object_vars($this);
        foreach ($args as $ind => $val) {
            if (!in_array($ind, $exempted_attr)) {
                if (isset($_REQUEST[$ind])) {
                    $this->$ind = $_REQUEST[$ind];
                }
            }
        }
    }

    /*     calculate hashing (HashValue)
    must pass the merchant password as argument to this function
     */
    public function calcHash($mPassword = null)
    {

        if (is_null($mPassword)) {
            return false;
        }
        $hash_str = $mPassword;
        if ($this->checkHashComponents()) {
            foreach ($this->hash_components as $component => $is_mandatory) {
                $hash_str .= $this->$component;
            }
            $this->HashValue = hash('sha256', $hash_str);
            return $this->HashValue;
        } else {
            return false;
        }
    }

    /*
    回傳Hash值
     */
    public function responseCalcHash($mPassword = null, $request = array())
    {

        if (is_null($mPassword)) {
            return false;
        }
        $hash_str = $mPassword;
        if (!empty($request)) {
            $check = true;
            $hashParams = array('TxnID', 'ServiceID', 'PaymentID', 'TxnStatus', 'Amount', 'CurrencyCode', 'AuthCode');
            foreach ($hashParams as $_param) {
                if ($check) {
                    $check = isset($request[$_param]);
                    if ($check) {
                        $hash_str .= $request[$_param];
                    }
                }
            }
            if (!$check) {
                return false;
            }

            return hash('sha256', $hash_str);
        }
    }

    //forming payment request
    public function getFormHTML($hashpass = null, $test = false)
    {

        $hash = $this->calcHash($hashpass);

        if ($hash === false) {
            echo 'HashValue cannot be calculated';
        } elseif ($this->checkPostVars()) {
            // $html = '<!DOCTYPE html>
            //             <html lang="en">
            //             <head>
            //                   <meta charset="UTF-8">
            //                   <title>Document</title>
            //             </head>
            //             <body>

            //                   <form name="frmPayment" id="frmPayment" method="post" action="' . $this->URL . '">
            //                         <input type="hidden" name="TransactionType" value="' . $this->TransactionType . '">
            //                         <input type="hidden" name="PymtMethod" value="' . $this->PymtMethod . '">
            //                         <input type="hidden" name="ServiceID" value="' . $this->ServiceID . '">
            //                         <input type="hidden" name="PaymentID" value="' . $this->PaymentID . '">
            //                         <input type="hidden" name="OrderNumber" value="' . $this->OrderNumber . '">
            //                         <input type="hidden" name="PaymentDesc" value="' . $this->PaymentDesc . '">
            //                         <input type="hidden" name="MerchantReturnURL" value="' . $this->MerchantReturnURL . '">
            //                         <input type="hidden" name="MerchantCallBackURL" value="' . $this->MerchantCallBackURL . '">
            //                         <input type="hidden" name="Amount" value="' . $this->Amount . '">
            //                         <input type="hidden" name="CurrencyCode" value="' . $this->CurrencyCode . '">
            //                         <input type="hidden" name="CustIP" value="' . $this->CustIP . '">
            //                         <input type="hidden" name="CustName" value="' . $this->CustName . '">
            //                         <input type="hidden" name="CustEmail" value="' . $this->CustEmail . '">
            //                         <input type="hidden" name="CustPhone" value="' . $this->CustPhone . '">
            //                         <input type="hidden" name="HashValue" value="' . $this->HashValue . '">
            //                         <input type="hidden" name="MerchantTermsURL" value="' . $this->MerchantTermsURL . '">
            //                         <input type="hidden" name="PageTimeout" value="' . $this->PageTimeout . '">

            //                   </form>
            //                   <script type="text/javascript">
            //                     document.getElementById("frmPayment").submit();
            //                   </script>
            //             </body>
            //             </html>';

            $params = $this->getRequestParams();

            $html = '<!DOCTYPE html>';
            $html .= '<html lang="en">';
            $html .= '<head>';
            $html .= '<meta charset="UTF-8">';
            $html .= '<title>Document</title>';
            $html .= '</head>';
            $html .= '<body>';

            if ($test) {
                $html .= '<fieldset id="confirmation">';
                $html .= '<legend>Review Payment Details</legend>';
                $html .= '<div>';
                $html .= 'url:' . $this->URL;
                foreach ($params as $_name => $_value) {
                    $html .= '<div>';
                    $html .= '<span>' . $_name . '</span>:<span>' . $_value . '</span>';
                    $html .= '</div>';
                }
                $html .= '</div>';
                $html .= '</fieldset>';
            }

            $html .= '<form name="frmPayment" id="frmPayment" method="post" action="' . $this->URL . '">';
            foreach ($params as $_name => $_value) {
                $html .= '<input type="hidden" name="' . $_name . '" value="' . $_value . '">';
            }
            if ($test) {
                $html .= '<input type="submit" value="送出">';
            }
            $html .= '</form>';
            $html .= '<script type="text/javascript">';
            if (!$test) {
                $html .= 'document.getElementById("frmPayment").submit();';
            }
            $html .= '</script>';
            $html .= '</body>';
            $html .= '</html>';
            return $html;
        } else {
            exit;
        }
    }

    //to form HTTP Query format i.e. name value params seperated by &
    public function buildPostVarStr()
    {
        $exempted_attr = array('URL', 'hash_components', 'post_args', 'post_vars');
        $args = get_object_vars($this);
        foreach ($args as $ind => $val) {
            if (in_array($ind, $exempted_attr)) {
                unset($args[$ind]);
            }
        }
        $this->post_args = http_build_query($args);
        return $this->post_args;
    }

    //to validate the mandatory hash components are present
    private function checkHashComponents()
    {
        foreach ($this->hash_components as $component => $is_mandatory) {
            if (is_null($this->$component) && $is_mandatory) {
                echo 'A mandatory hash component "' . $component . '" is missing...<br/>';
                return false;
            }
        }
        return true;
    }

    //to validate the mandatory post params are present
    private function checkPostVars()
    {
        foreach ($this->post_vars as $component => $is_mandatory) {
            if (is_null($this->$component) && $is_mandatory) {
                echo 'A mandatory Post param "' . $component . '" is missing...<br/>';
                return false;
            }
        }
        return true;
    }
}

//正式
$url = "https://securepay.e-ghl.com/IPG/Payment.aspx";
//測試
//$url = "https://test2pay.ghl.com/IPGSG/Payment.aspx";
//assign the merchant password given by eGHL
$merchantPWD = 'mrHwMEaY';
//assign the Merchant ID given by eGHL
$serverID = 'GAT';
//貨幣
$currency = 'MYR';
//付款有效時間(13分)
$pageTimeOut = 13 * 60;
$obj = new formEGHLsend($url);

$task = global_get_param($_REQUEST, 'task', '', 0, 1);
$test = global_get_param($_REQUEST, 'test', '', 0, 1);
$handMode = global_get_param($_REQUEST, 'handMode', '', 0, 1);
$session = global_get_param($_REQUEST, 'session', '1', 0, 1);

switch ($task) {
    //授權+請款
    case "orderSale":
        $orderNum = $_REQUEST['orderNum'];

        //取自己的訂單
        $orderSql = "SELECT";
        $orderSql .= " orders.*";
        $orderSql .= " ,members.name as memberName, members.email as memberEmail, members.phone as memberPhone, members.mobile as memberMobile";
        $orderSql .= " FROM orders ";
        $orderSql .= " LEFT JOIN members ON members.id = orders.memberid";
        $orderSql .= " WHERE 1";
        $orderSql .= " AND orders.orderNum = '$orderNum'";
        if ($session == '1') {
            $orderSql .= " AND orders.memberid = '" . $_SESSION[$conf_user]['uid'] . "'";
        }
        $orderSql .= " AND orders.status = '0'";
        $db->setQuery($orderSql);
        $order = $db->loadRow();
        //測試不檢查
        if (!($test == '1')) {
            //查無訂單
            if (empty($order)) {
                echo "查無未付款訂單($orderNum)";
                exit;
            } else {
                //狀態檢查
            }
        }
        $amount = $order['totalAmt'] - $order['m_discount'] - $order['cb_use_points'] - $order['use_points'];

        //測試用
        if ($test == '1') {
            $orderNum = time();
            $amount = 100;
        }

        //多個參數要將&取代成;
        //e.g.
        //https://domain/test.php?task=notice&orderNum=test123
        //換成
        //https://domain/test.php?task=notice;orderNum=test123

        //返回頁面
        //$returnURL = "https://shop1.goodarch2u.com/app/controllers/agent.php?task=back;payFlow=eghl";
        $returnURL = SHOPURL . "app/controllers/eghl.php?task=back";
        //通知頁面-最多3次
        //$backURL = "https://shop1.goodarch2u.com/app/controllers/agent.php?task=receipt;payFlow=eghl";
        $backURL = SHOPURL . "app/controllers/eghl.php?task=receipt";

        //取小數點兩位-四捨五入
        $amount = round($amount, 2);
        $amountArr = explode(".", $amount);
        if (count($amountArr) == 1) {
            $amount = $amountArr[0] . '.00';
        }

        //用戶資料
        $memberInfo = array(
            'name' => $order['memberName'],
            'email' => $order['memberEmail'],
            'phone' => $order['memberMobile'],
        );
        if ($memberInfo['phone'] == '') {
            $memberInfo['phone'] = $order['memberPhone'];
        }

        if ($test == '1') {
            $memberInfo = array(
                'name' => "A",
                'email' => "C@gmail.com",
                'phone' => "0912345678",
            );
        }

        //assign the appropraite values to each of the param
        $obj->set('TransactionType', 'SALE');
        //銀行匯款(ANY-有開放都有)
        $obj->set('PymtMethod', 'ANY');
        $obj->set('OrderNumber', $orderNum);
        $obj->set('PaymentDesc', 'order');
        //幣值
        $obj->set('CurrencyCode', $currency);
        $obj->set('ServiceID', $serverID);
        //must be unique. Do increment
        $obj->set('PaymentID', $orderNum);
        $obj->set('MerchantReturnURL', $returnURL);
        $obj->set('MerchantCallBackURL', $backURL);
        //金額一定要到小數第二位
        $obj->set('Amount', $amount);
        $obj->set('CustIP', $_SERVER['REMOTE_ADDR']);

        $obj->set('PageTimeout', $pageTimeOut);
        $obj->set('CustName', $memberInfo['name']);
        $obj->set('CustEmail', $memberInfo['email']);
        $obj->set('CustPhone', $memberInfo['phone']);
        $obj->set('MerchantTermsURL', '');
        //assign the merchant password given by eGHL

        //寫入log
        $log = array(
            'orderNum' => $orderNum,
            'request' => json_encode($obj->getRequestParams(), JSON_UNESCAPED_UNICODE),
            'requestTime' => time(),
        );
        $insertSql = dbInsert('eghl_log', $log);
        $db->setQuery($insertSql);
        $db->query();

        //送出請求
        if ($handMode == '1') {
            echo $obj->getFormHTML($merchantPWD, true);
            //自動
        } else {
            echo $obj->getFormHTML($merchantPWD);
        }
        break;
    //接收通知
    case "receipt":
        //寫入log
        $logType = 'add';
        $log = array(
            'response' => json_encode($_REQUEST, JSON_UNESCAPED_UNICODE),
            'responseTime' => time(),
        );
        $HashValue = $_REQUEST['HashValue'];
        //驗證參數
        if ($HashValue == $obj->responseCalcHash($merchantPWD, $_REQUEST)) {
            //收到參數
            // TransactionType
            // PymtMethod
            // ServiceID
            // PaymentID
            // OrderNumber
            // Amount
            // CurrencyCode
            // TxnID
            // TxnStatus
            // Param6
            // Param7
            // TxnMessage
            // HashValue
            // HashValue2
            $orderNum = $_REQUEST['OrderNumber'];
            $amount = $_REQUEST['Amount'];
            $txnStatus = $_REQUEST['TxnStatus'];
            //成功
            if ($txnStatus == '0') {
                echo "OK";
                //訂單成功更新
                orderSuccessUpdate($orderNum);
                //取同訂單請求log
                $logSql = "SELECT * FROM eghl_log WHERE responseTime = 0 AND orderNum = '$orderNum'";
                $db->setQuery($logSql);
                $logData = $db->loadRow();
                if (!empty($logData)) {
                    $log['id'] = $logData['id'];
                    $logType = 'update';
                } else {
                    $log['orderNum'] = $orderNum;
                }
                $log['status'] = "1";
            } else {
                echo "OK";
                //訂單取消更新
                // orderCancelUpdate($orderNum);
                //其他
                $log['status'] = "-1";
            }
        }
        $logSql = "";
        switch ($logType) {
            case "update":
                $logSql = dbUpdate('eghl_log', $log, "id = '" . $log['id'] . "'");
                break;
            case "add":
                $logSql = dbInsert('eghl_log', $log);
                break;
        }
        //echo $logSql;
        if (!($logSql == "")) {
            $db->setQuery($logSql);
            $db->query();
        }
        break;
    case "back":
        header('Location: ' . SHOPURL . 'member_page/order');
        exit();
        break;
    default:
        break;
}

include $conf_php . 'common_end.php';
