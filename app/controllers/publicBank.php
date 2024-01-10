<?php
include '../../config.php';

class PublicBankSyberCourse
{
    private $scretKey = null;
    //錯誤訊息
    protected $errorMsg = "";
    //請求網址
    protected $url = "";
    //請求端點
    protected $endPoint = "/pay";
    //帳單資訊
    protected $bill = null;

    //請求參數
    public $requestParam = array(
        'access_key' => '',
        'transaction_uuid' => '',
        'signed_date_time' => '',
        'profile_id' => '',
        'signed_field_names' => '',
        'locale' => 'en',
        'transaction_type' => 'sale',
        'reference_number' => '',
        'amount' => '',
        'currency' => 'myr',
    );

    //帳單格式
    public $billFormat = array(
        'bill_to_forename' => '',
        'bill_to_surname' => '',
        'bill_to_email' => '',
        'bill_to_address_line1' => '',
        'bill_to_address_line2' => '',
        'bill_to_address_city' => '',
        'bill_to_address_country' => '',
    );

    public function __construct($scretKey = null)
    {
        if (is_null($scretKey)) {
            $this->errorMsg = "";
            if (is_null($scretKey)) {
                $this->errorMsg = "scretKey未設定";
            }
            echo $this->errorMsg;
        } else {
            $this->scretKey = $scretKey;
            $this->bill = $this->billFormat;
            $this->reflesh();
        }
    }

    //特定參數刷新
    public function reflesh()
    {
        $this->requestParam['transaction_uuid'] = uniqid();
        $this->requestParam['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
    }

    //取屬性
    public function get($attr)
    {
        return $this->$attr;
    }

    //設定屬性
    public function set($attr, $val)
    {
        $this->$attr = $val;
    }

    //設定請求參數
    public function setRequestParam($attr, $val)
    {
        if (isset($this->requestParam[$attr])) {
            $this->requestParam[$attr] = $val;
        }
    }

    //加密
    public function sign($params)
    {
        return $this->signData($this->commaSeparate($this->buildDataToSign($params)), $this->scretKey);
    }

    public function signData($data, $secretKey)
    {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    //取請求參數
    public function getRequestParams()
    {
        return array_merge($this->requestParam, $this->bill);
    }

    public function buildDataToSign($params)
    {
        $signedFieldNames = explode(",", $params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
            $dataToSign[] = $field . "=" . $params[$field];
        }
        return $dataToSign;
    }

    public function commaSeparate($dataToSign)
    {
        return implode(",", $dataToSign);
    }

    public function request($test = false)
    {
        if (!(json_encode($this->bill) == json_encode($this->billFormat))) {
            $this->requestParam = array_merge($this->requestParam, $this->bill);
        }
        $signed_field_names = array();
        foreach ($this->requestParam as $_name => $_value) {
            if (!in_array($_name, $signed_field_names)) {
                $signed_field_names[] = $_name;
            }
        }
        $this->requestParam['signed_field_names'] = implode(',', $signed_field_names);
        $this->requestParam['signature'] = $this->sign($this->requestParam);
        $url = $this->url . $this->endPoint;

        $html = '<html>';
        $html .= '<head>';
        $html .= '<title>Secure Acceptance - Payment Form Example</title>';
        $html .= '</head>';
        $html .= '<body>';
        if ($test) {
            $html .= '<fieldset id="confirmation">';
            $html .= '<legend>Review Payment Details</legend>';
            $html .= '<div>';
            $html .= 'url:' . $url;
            foreach ($this->requestParam as $name => $value) {
                $html .= '<div>';
                $html .= '<span>' . $name . '</span>:<span>' . $value . '</span>';
                $html .= '</div>';
            }
            $html .= '</div>';
            $html .= '</fieldset>';
        }
        $html .= '<form id="payment_confirmation" action="' . $url . '" method="post">';
        foreach ($this->requestParam as $name => $value) :
            $html .= '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $value . '">';
        endforeach;
        if ($test) {
            $html .= '<input type="submit" value="送出">';
        }
        $html .= '</form>';
        $html .= '<script type="text/javascript">';
        if (!$test) {
            $html .= 'document.getElementById("payment_confirmation").submit();';
        }
        $html .= '</script>';
        $html .= '</body>';
        $html .= '</html>';
        echo $html;
    }
}

//正式
$url = "https://secureacceptance.cybersource.com";
//測試
$url = "https://testsecureacceptance.cybersource.com";
//scretKey
$scretKey = '576cb92356ba499a8c38b976c4b513bf6549207a2a7f4449baec8461c36a621efbc2806bdf9848f68c0cfea9527443ac2fa2990ba2094cb7943f443eb3ada76c00bf36419e3f4122a860837d2fa48c45dbd23ba60fc645d6b0ee5651ddf5f662ec93b36fc39a4c99b9cda2b3cb60b0c0c3f6323653964ec29963fb24f7c1a1c3';
//profileId
$profileId = '30B0DB1F-6784-41E5-AC87-E25FDED3DA54';
//accessKey
$accessKey = 'ad32f2d96b933f20a491831c66ae3576';

$obj = new PublicBankSyberCourse($scretKey);

$task = global_get_param($_REQUEST, 'task', '', 0, 1);
$test = global_get_param($_REQUEST, 'test', '', 0, 1);
$handMode = global_get_param($_REQUEST, 'handMode', '', 0, 1);
$session = global_get_param($_REQUEST, 'session', '1', 0, 1);

$request = $_REQUEST;
if ($test == '1') {
    // $request = '{"req_currency":"MYR","decision":"CANCEL","req_locale":"en","req_payer_authentication_indicator":"01","signature":"J7LsGUUfeLvHaZ0Cc3rDHvAt4SVcdemh8rMc6vfhPB0=","req_card_type_selection_indicator":"1","req_bill_to_surname":"Noreg","req_bill_to_address_city":"Kelantan","message":"The consumer cancelled the transaction","signed_field_names":"req_currency,decision,req_locale,req_payer_authentication_indicator,req_card_type_selection_indicator,req_bill_to_surname,req_bill_to_address_city,message,req_transaction_uuid,req_bill_to_forename,req_bill_to_address_country,req_transaction_type,req_payment_method,req_access_key,req_profile_id,req_reference_number,req_payer_authentication_merchant_name,req_amount,req_bill_to_email,req_bill_to_address_line1,signed_field_names,signed_date_time","req_transaction_uuid":"62675420b7a31","req_bill_to_forename":"Noreg","req_bill_to_address_country":"MY","req_transaction_type":"sale","req_payment_method":"card","req_access_key":"ad32f2d96b933f20a491831c66ae3576","req_profile_id":"30B0DB1F-6784-41E5-AC87-E25FDED3DA54","req_reference_number":"3S010-22040107","req_payer_authentication_merchant_name":"GOODARCH TECH SB (MYR)","req_amount":"97","req_bill_to_email":"h2108@goodarch2u.com","signed_date_time":"2022-04-26T02:08:41Z","req_bill_to_address_line1":"Noreg"}';
    // $request = json_decode($request, true);
}
if (in_array($request['decision'], array('CANCEL', 'ACCEPT'))) {
    $task = 'receipt';
    print_r($request);
}

switch ($task) {
        //購物車訂單授權+請款
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
                echo "查無未付款訂單($orderNum/" . $_SESSION[$conf_user]['uid'] . ")";
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

        //設定網址
        $obj->set('url', $url);

        //設定請求-START
        //profile_id
        $obj->setRequestParam('profile_id', $profileId);
        //access_key
        $obj->setRequestParam('access_key', $accessKey);
        //訂單編號
        $obj->setRequestParam('reference_number', $orderNum);
        //金額
        $obj->setRequestParam('amount', $amount);

        //設定請求-END

        //設定帳單-START
        //取帳單格式
        $billFormat = $obj->get('billFormat');
        //名字
        $billFormat['bill_to_forename'] = '';
        $billFormat['bill_to_forename'] = $order['memberName'];
        //姓氏
        $billFormat['bill_to_surname'] = '';
        $billFormat['bill_to_surname'] = $order['memberName'];
        //email
        $billFormat['bill_to_email'] = '';
        $billFormat['bill_to_email'] = $order['memberEmail'];
        //地址1
        $billFormat['bill_to_address_line1'] = '';
        $billFormat['bill_to_address_line1'] = $order['bill_address'];
        //地址2
        $billFormat['bill_to_address_line2'] = '';
        $billFormat['bill_to_address_line2'] = $order['bill_address2'];
        //城市
        $billFormat['bill_to_address_city'] = '';
        $billFormat['bill_to_address_city'] = $order['bill_city'];
        //國家代號(ISO country codes)
        $billFormat['bill_to_address_country'] = 'MY';

        if ($test == '1') {
            //名字
            $billFormat['bill_to_forename'] = "A";
            //姓氏
            $billFormat['bill_to_surname'] = "B";
            //email
            $billFormat['bill_to_email'] = "C@gmail.com";
            //地址1
            $billFormat['bill_to_address_line1'] = '666666666666666666666666666666666666666666666666666666666666';
            //地址2
            $billFormat['bill_to_address_line2'] = '777777777777777777777777777777777777777777777777777777777777';
            //城市
            $billFormat['bill_to_address_city'] = '789';
            //國家代號(ISO country codes)
            $billFormat['bill_to_address_country'] = 'MY';
        }

        $obj->set('bill', $billFormat);
        //設定帳單-END

        //請求參數
        $requestParams = $obj->getRequestParams();
        //寫入log
        $log = array(
            'orderNum' => $orderNum,
            'uuid' => $requestParams['transaction_uuid'],
            'request' => json_encode($requestParams, JSON_UNESCAPED_UNICODE),
            'requestTime' => time(),
        );
        $insertSql = dbInsert('public_bank_log', $log);
        $db->setQuery($insertSql);
        $db->query();

        //送出請求
        if ($handMode == '1') {
            $obj->request(true);
            //自動
        } else {
            $obj->request();
        }

        break;
        //接收通知
    case "receipt":
        // $paramsStr = '{"task":"notice","auth_cv_result":"M","req_locale":"en","req_card_type_selection_indicator":"1","auth_trans_ref_no":"6492107251406391603005","payer_authentication_enroll_veres_enrolled":"Y","req_bill_to_surname":"Smith","payer_authentication_proof_xml":"&lt;AuthProof&gt;&lt;Time&gt;2022 Apr 06 02:05:19&lt;/Time&gt;&lt;DSUrl&gt;https://merchantacsstag.cardinalcommerce.com/MerchantACSWeb/vereq.jsp?acqid=CYBS&lt;/DSUrl&gt;&lt;VEReqProof&gt;&lt;Message id=&quot;VQHgxWH9mWyVFMAAaJy0&quot;&gt;&lt;VEReq&gt;&lt;version&gt;1.0.2&lt;/version&gt;&lt;pan&gt;XXXXXXXXXXXX1096&lt;/pan&gt;&lt;Merchant&gt;&lt;acqBIN&gt;469216&lt;/acqBIN&gt;&lt;merID&gt;3301574068&lt;/merID&gt;&lt;/Merchant&gt;&lt;Browser&gt;&lt;deviceCategory&gt;0&lt;/deviceCategory&gt;&lt;accept&gt;*/*&lt;/accept&gt;&lt;userAgent&gt;Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36&lt;/userAgent&gt;&lt;/Browser&gt;&lt;/VEReq&gt;&lt;/Message&gt;&lt;/VEReqProof&gt;&lt;VEResProof&gt;&lt;Message id=&quot;VQHgxWH9mWyVFMAAaJy0&quot;&gt;&lt;VERes&gt;&lt;version&gt;1.0.2&lt;/version&gt;&lt;CH&gt;&lt;enrolled&gt;Y&lt;/enrolled&gt;&lt;acctID&gt;5397223&lt;/acctID&gt;&lt;/CH&gt;&lt;url&gt;https://merchantacsstag.cardinalcommerce.com/MerchantACSWeb/pareq.jsp?vaa=b&amp;amp;gold=AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA&lt;/url&gt;&lt;protocol&gt;ThreeDSecure&lt;/protocol&gt;&lt;/VERes&gt;&lt;/Message&gt;&lt;/VEResProof&gt;&lt;/AuthProof&gt;","req_card_expiry_date":"12-2025","merchant_advice_code":"01","card_type_name":"Visa","auth_amount":"100.00","auth_response":"00","bill_trans_ref_no":"6492107251406391603005","req_payment_method":"card","auth_time":"2022-04-06T020525Z","transaction_id":"6492107251406391603005","req_card_type":"001","payer_authentication_pares_status":"Y","payer_authentication_cavv":"AAABAWFlmQAAAABjRWWZEEFgFz+=","auth_avs_code":"Y","auth_code":"831000","req_bill_to_address_country":"MY","auth_cv_result_raw":"M","req_profile_id":"30B0DB1F-6784-41E5-AC87-E25FDED3DA54","signed_date_time":"2022-04-06T02:05:25Z","req_bill_to_address_line1":"1 My Apartment","payer_authentication_validate_e_commerce_indicator":"vbv","req_card_number":"445653xxxxxx1096","signature":"NmkQxgFhFuAPECwiYyGHwJm0JSzDzpKtcm4DVse5hTg=","req_bill_to_address_city":"Mountain View","auth_cavv_result":"2","reason_code":"100","req_bill_to_forename":"Joe","request_token":"Axj//wSTX/AWPH7nke89ABos2aOWTFg3ZNWLRg2ZuWLZgzYMGqiONzNwiwFRHG5m4RblDpxAgvhk0ky9GLCQEIMFcmv+AseP3PI956AAKzNK","auth_cavv_result_raw":"2","req_amount":"100","req_bill_to_email":"joesmith@example.com","payer_authentication_reason_code":"100","auth_avs_code_raw":"Y","req_currency":"MYR","decision":"ACCEPT","message":"Request was processed successfully.","signed_field_names":"transaction_id,decision,req_access_key,req_profile_id,req_transaction_uuid,req_transaction_type,req_reference_number,req_amount,req_currency,req_locale,req_payment_method,req_bill_to_forename,req_bill_to_surname,req_bill_to_email,req_bill_to_address_line1,req_bill_to_address_city,req_bill_to_address_country,req_card_number,req_card_type,req_card_type_selection_indicator,req_card_expiry_date,card_type_name,message,reason_code,auth_avs_code,auth_avs_code_raw,auth_response,auth_amount,auth_code,auth_cavv_result,auth_cavv_result_raw,auth_cv_result,auth_cv_result_raw,auth_trans_ref_no,auth_time,request_token,merchant_advice_code,bill_trans_ref_no,payer_authentication_enroll_veres_enrolled,payer_authentication_xid,payer_authentication_proof_xml,payer_authentication_eci,payer_authentication_cavv,payer_authentication_pares_status,payer_authentication_validate_result,payer_authentication_reason_code,payer_authentication_validate_e_commerce_indicator,signed_field_names,signed_date_time","req_transaction_uuid":"624cf512cc22e","payer_authentication_eci":"05","req_transaction_type":"sale","payer_authentication_xid":"VlFIZ3hXSDltV3lWRk1BQWFKeTA=","req_access_key":"ad32f2d96b933f20a491831c66ae3576","req_reference_number":"1649210642","payer_authentication_validate_result":"0"}';
        // $paramsStr = '{"task":"notice","req_currency":"MYR","decision":"CANCEL","req_locale":"en","signature":"0KexKeJIIWwD00cArlGpeG2+yYZhWt6/cK9uuD0XXgg=","req_card_type_selection_indicator":"1","req_bill_to_surname":"Smith","req_bill_to_address_city":"Mountain View","message":"The consumer cancelled the transaction","signed_field_names":"req_currency,decision,req_locale,req_card_type_selection_indicator,req_bill_to_surname,req_bill_to_address_city,message,req_transaction_uuid,req_bill_to_forename,req_bill_to_address_country,req_transaction_type,req_payment_method,req_access_key,req_profile_id,req_reference_number,req_amount,req_bill_to_email,req_bill_to_address_line1,signed_field_names,signed_date_time","req_transaction_uuid":"6246c49d86d24","req_bill_to_forename":"Joe","req_bill_to_address_country":"MY","req_transaction_type":"sale","req_payment_method":"card","req_access_key":"ad32f2d96b933f20a491831c66ae3576","req_profile_id":"30B0DB1F-6784-41E5-AC87-E25FDED3DA54","req_reference_number":"1648805021","req_amount":"100","req_bill_to_email":"joesmith@example.com","signed_date_time":"2022-04-01T09:23:49Z","req_bill_to_address_line1":"1 My Apartment"}';
        // $params = json_decode($paramsStr, true);
        $params = $request;
        unset($params['task']);
        //寫入log
        $logType = 'add';
        $log = array(
            'response' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'responseTime' => time(),
        );

        if (strcmp($params["signature"], $obj->sign($params)) == 0) {
            $orderNum = $params['req_reference_number'];
            //付款成功
            if ($params['auth_response'] == '00') {
                echo "成功";
                $uuid = $params['req_transaction_uuid'];
                //訂單成功更新
                orderSuccessUpdate($orderNum);
                //取同訂單請求log
                $logSql = "SELECT * FROM public_bank_log WHERE responseTime = 0 AND orderNum = '$orderNum' AND uuid = '$uuid'";
                $db->setQuery($logSql);
                $logData = $db->loadRow();
                if (!empty($logData)) {
                    $log['id'] = $logData['id'];
                    $logType = 'update';
                } else {
                    $log['orderNum'] = $orderNum;
                }
                $log['status'] = "1";
                //付款失敗
            } else {
                switch ($params['decision']) {
                    case "CANCEL":
                        echo "取消($orderNum)";

                        // $orderMode = getFieldValue(" SELECT orderMode FROM orders WHERE orderNum = '$orderNum' ", "orderMode");
                        // //不是addMember才取消
                        // if (!($orderMode == 'addMember')) {
                        //     //訂單取消更新
                        //     $result = orderCancelUpdate($orderNum);
                        // }
                        $log['status'] = "9";
                        break;
                    default:
                        echo "其他";
                        $log['status'] = "-1";
                        break;
                }
            }
            //print_r($params);
        } else {
            echo "驗證錯誤";
            $log['status'] = "8";
        }
        $logSql = "";
        switch ($logType) {
            case "update":
                $logSql = dbUpdate('public_bank_log', $log, "id = '" . $log['id'] . "'");
                break;
            case "add":
                $logSql = dbInsert('public_bank_log', $log);
                break;
        }
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
        $params = $_REQUEST;
        //寫入log
        $log = array(
            'response' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'responseTime' => time(),
        );
        $logSql = "";
        $logSql = dbInsert('public_bank_log', $log);
        if (!($logSql == "")) {
            $db->setQuery($logSql);
            $db->query();
        }
        break;
}

include $conf_php . 'common_end.php';
