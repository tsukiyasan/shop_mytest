<?php
/**
* version #1.0
* package Shopping cart
* date 2012/06
* author bibibobo HSU 
* email bibibobo97@gmail.com
* copyright protected
*/

defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );

//-----共通-----//

DEFINE('_YES','Yes');
DEFINE('_NO','No');

DEFINE('_COMMON_COMPONENT_MAINMENU','Main menu');
DEFINE('_COMMON_COMPONENT_NEWS','latest news');



DEFINE('_COMMON_PARAM_ID','Numbering');
DEFINE('_COMMON_PARAM_FROM','Starting number');
DEFINE('_COMMON_PARAM_TITLE','title');
DEFINE('_COMMON_PARAM_NAME','name');
DEFINE('_COMMON_PARAM_PUBLISH','display');
DEFINE('_COMMON_PARAM_LEVEL','Level');
DEFINE('_COMMON_PARAM_BELONGID','Attribution number');
DEFINE('_COMMON_PARAM_PAGETYPE','Page type');
DEFINE('_COMMON_PARAM_DATABASETAB','Attribution table');
DEFINE('_COMMON_PARAM_DATABASEID','Data sheet number');
DEFINE('_COMMON_PARAM_LINKURL','Destination URL');
DEFINE('_COMMON_PARAM_TARGET','Destination');
DEFINE('_COMMON_PARAM_CONTENT','content');
DEFINE('_COMMON_PARAM_TEL','phone');
DEFINE('_COMMON_PARAM_FAX','fax');
DEFINE('_COMMON_PARAM_EMAIL','Email');
DEFINE('_COMMON_PARAM_ADDR','Address');
DEFINE('_COMMON_PARAM_WEBURL','Website URL');
DEFINE('_COMMON_PARAM_WEBTITLE','Website header');
DEFINE('_COMMON_PARAM_WEBKEYS','Site keywords');
DEFINE('_COMMON_PARAM_WEBINTRO','Site description');
DEFINE('_COMMON_PARAM_ALLRIGHT','Declaration of rights');
DEFINE('_COMMON_PARAM_MEDIADEC1','Media description 1');
DEFINE('_COMMON_PARAM_MEDIADEC2','Media description 2');
DEFINE('_COMMON_PARAM_MEDIADEC3','Media description 3');
DEFINE('_COMMON_PARAM_MEDIAURL1','Media link 1');
DEFINE('_COMMON_PARAM_MEDIAURL2','Media link 2');
DEFINE('_COMMON_PARAM_MEDIAURL3','Media Link 3');
DEFINE('_COMMON_PARAM_SEARCH_NAME','Search wording');
DEFINE('_COMMON_PARAM_ODRING','Sort');
DEFINE('_COMMON_PARAM_NEWSDATE','Release date');
DEFINE('_COMMON_PARAM_PUBDATE','deadline');
DEFINE('_COMMON_PARAM_NEWS','latest news');
DEFINE('_COMMON_PARAM_HOT','Hot');
DEFINE('_COMMON_PARAM_LOGINID','Account');
DEFINE('_COMMON_PARAM_PASSWD','password');
DEFINE('_COMMON_PARAM_VR','Authentication random number');
DEFINE('_COMMON_PARAM_CV','Authentication Code');
DEFINE('_COMMON_PARAM_LG','Language code');
DEFINE('_COMMON_PARAM_DLVRPAYCHK','Cash on delivery');
DEFINE('_COMMON_PARAM_BANKPAYCHK','Online banking');
DEFINE('_COMMON_PARAM_CREDITPAYCHK','credit card payment');
DEFINE('_COMMON_PARAM_HOMEDLVRCHK','Shipping');
DEFINE('_COMMON_PARAM_HOMEDLVRCHK_AMT','Shipping fee');
DEFINE('_COMMON_PARAM_HOMEDLVRGMCHK','Free shipping for order ');
DEFINE('_COMMON_PARAM_HOMEDLVRGMCHK_AMT','Order ');
DEFINE('_COMMON_PARAM_BANKNAME','Bank name');
DEFINE('_COMMON_PARAM_BANKBRANCH','Branch (bank)');
DEFINE('_COMMON_PARAM_BANKID','Account Name (bank)');
DEFINE('_COMMON_PARAM_BANKNUM','Account (bank)');
DEFINE('_COMMON_PARAM_DONATEUNIT','Donation receipt unit');
DEFINE('_COMMON_PARAM_POSTBRANCH','Branch (post)');
DEFINE('_COMMON_PARAM_POSTID','Account Name (post)');
DEFINE('_COMMON_PARAM_POSTNUM1','Account 1 (post)');
DEFINE('_COMMON_PARAM_POSTNUM2','Account 2 (post)');
DEFINE('_COMMON_PARAM_DBTABLE','Web database');
DEFINE('_COMMON_PARAM_TYPE','Type');
DEFINE('_COMMON_PARAM_PROCODE','Item Code');
DEFINE('_COMMON_PARAM_STOP_DATE','Validity');
DEFINE('_COMMON_PARAM_NEW_PRODUCT','New item');
DEFINE('_COMMON_PARAM_HOT_PRODUCT','Best sellers');
DEFINE('_COMMON_PARAM_REC_PRODUCT','Recommended item');
DEFINE('_COMMON_PARAM_STOCKCHK','Inventory check');
DEFINE('_COMMON_PARAM_STOCKCNT','Current inventory');
DEFINE('_COMMON_PARAM_INSTOCK','safe inventory');
DEFINE('_COMMON_PARAM_HIGHAMT','Regular price');
DEFINE('_COMMON_PARAM_SALESAMT','Special price');
DEFINE('_COMMON_PARAM_BONUS','Bonus');
DEFINE('_COMMON_PARAM_OTHERURL','External links');
DEFINE('_COMMON_PARAM_PRODUCT_NOTES','item description');
DEFINE('_COMMON_PARAM_PRODUCT_SUMMARY','Brief description');
DEFINE('_COMMON_PARAM_FIELD','Reserved field');
DEFINE('_COMMON_PARAM_MEDIANAME','Media name');
DEFINE('_COMMON_PARAM_MEDIACONT','Media description');
DEFINE('_COMMON_PARAM_MEDIASOURCE','Media destination URL');
DEFINE('_COMMON_PARAM_SID','IC Number');
DEFINE('_COMMON_PARAM_MOBILE','Mobile');
DEFINE('_COMMON_PARAM_SEX','gender');
DEFINE('_COMMON_PARAM_BIRTHDATE','Date of birth');
DEFINE('_COMMON_PARAM_COUPON','Coupon');
DEFINE('_COMMON_PARAM_BONUS','Bonus');
DEFINE('_COMMON_PARAM_NEWSLETTER','Newsletter');
DEFINE('_COMMON_PARAM_PLAN','Promotion');
DEFINE('_COMMON_PARAM_FDATE','Effective');
DEFINE('_COMMON_PARAM_EDATE','Valid until');
DEFINE('_COMMON_PARAM_PLANCONT','Scheme parameters');
DEFINE('_COMMON_PARAM_PRODUCT_STR','Promotion item');
DEFINE('_COMMON_PARAM_BILLTYPE','Order Status');
DEFINE('_COMMON_PARAM_CODENUM','Serial number');
DEFINE('_COMMON_PARAM_QUANTITY','Quantity');

DEFINE('_COMMON_PARAM_VALIDATE_NOT_REQUIRED','Cannot be empty');
DEFINE('_COMMON_PARAM_VALIDATE_FORMAT_ERR','format error');

DEFINE('_COMMON_QUERYMSG_ADD_SUS','Added completed');
DEFINE('_COMMON_QUERYMSG_ADD_ERR','Added failed');
DEFINE('_COMMON_QUERYMSG_UPD_SUS','Update completed');
DEFINE('_COMMON_QUERYMSG_UPD_ERR','Update failed');
DEFINE('_COMMON_QUERYMSG_SAM_TIT','Same Title');
DEFINE('_COMMON_QUERYMSG_DEL_SUS','Delete completed ');
DEFINE('_COMMON_QUERYMSG_DEL_ERR','Delete failed');


DEFINE('_COMMON_QUERYMSG_HAVE_ACT','This is special promo item and cannot be deleted');
DEFINE('_COMMON_QUERYMSG_HAVE_DATA','This item content data and cannot be deleted');
DEFINE('_COMMON_QUERYMSG_HAVE_RELATED_DATA','This item content related data and cannot be deleted');
DEFINE('_COMMON_QUERYMSG_LOGIN_ERROR','Incorrect account password');
DEFINE('_COMMON_QUERYMSG_LOGIN_ERROR2','Member login only');
DEFINE('_COMMON_QUERYMSG_HAVE_LOG','Members have redeemed Coupon and cannot be deleted');
DEFINE('_COMMON_QUERYMSG_SIGNUP_ERROR','This account is already a member, please log in directly');
DEFINE('_COMMON_QUERYMSG_SIGNUP_SUC','Sign up succesful');

DEFINE('_COMMON_ERRORMSG_NET_ERR','Network error, please check your connection status');
DEFINE('_COMMON_ERRORMSG_CHECKCODE_ERR','Verification code error');
DEFINE('_COMMON_ERRORMSG_LOGINOUT_ERR','Logout error');
DEFINE('_COMMON_ERRORMSG_DBPAGE_ERR','This item does not exist');
DEFINE('_COMMON_ERRORMSG_DBPAGE_HASERR','This info will be use as web database');
DEFINE('_COMMON_ERRORMSG_LOGINID_REPEAT','The same account already exists');


DEFINE('_COMMON_NOW_DATE','From now on');
DEFINE('_COMMON_NO_END','no limit');
DEFINE('_COMMON_UNLIMIT','Unlimited');

DEFINE('_COMMON_QUERYMSG_LOGIN_CHECKCODE_ERROR','Verification code error');
DEFINE('_COMMON_PARAM_CHECKCODE','Verification code');
DEFINE('_COMMON_AMT','Turnover');
DEFINE('_COMMON_VIEWCNT','Number of pax');
DEFINE('_COMMON_SALECNT','Quantity');


//0707共用
DEFINE("_COMMON_QUERYMSG_SELECT_ERR","資料查詢失敗");


//0707活動專區
DEFINE("_ACTIVE_TITLE","Special event page");
DEFINE("_ACTIVE_ACTIVITY_LIMIT","You've reached purchase limit for this event");

//0707紅利兌換
DEFINE("_BONUS_NO_PRODUCT","Item not found");

//0707購物車
DEFINE("_CART_ERROR_MSG","Please complete the membership fee payment and email verification");
DEFINE("_CART_PAY_SUCCESS_MSG1","Payment successful notification");
DEFINE("_CART_PAY_SUCCESS_MSG2","Order details");
DEFINE("_CART_PAY_SUCCESS_MSG3","Dear user");
DEFINE("_CART_PAY_SUCCESS_MSG4","A new order received, please process your order as soon as possible through system:");
DEFINE("_CART_PAY_SUCCESS_MSG5","Order No");
DEFINE("_CART_PAY_SUCCESS_MSG6","Contact");
DEFINE("_CART_PAY_SUCCESS_MSG7","Address");
DEFINE("_CART_PAY_SUCCESS_MSG8","Customer Service email:");
DEFINE("_CART_PAY_ERROR_MSG1","Transaction failed, please make payment again");
DEFINE("_CART_PAY_SUCCESS","Successful transaction");
DEFINE("_CART_EMPTY","Your cart is empty");
DEFINE("_CART_INSTOCK_ERROR_MSG","Insufficient stock, please select again");
DEFINE("_CART_PASSWORDTEXT_ERROR_MSG1","Please fill in secret phrase in remarks");
DEFINE("_CART_PASSWORDTEXT_ERROR_MSG2","Please enter the correct secret phrase");
DEFINE("_CART_BONUS_ERROR_MSG","Your shopping wallet is insufficient");
DEFINE("_CART_NET_ERROR_MSG","Internet connection error");
DEFINE("_CART_ORDER_ADD_MSG1","Order Successful notification");
DEFINE("_CART_ORDER_ADD_MSG2","Order details");
DEFINE("_CART_ORDER_ADD_MSG3","Dear");
DEFINE("_CART_ORDER_ADD_MSG4","Member");
DEFINE("_CART_ORDER_ADD_MSG5","Your order information has been received, thank you for your order. You order details as follows:");
DEFINE("_CART_ORDER_ADD_MSG6","");
DEFINE("_CART_ORDER_ADD_MSG7","Order No");
DEFINE("_CART_ORDER_ADD_MSG8","Date of order");
DEFINE("_CART_ORDER_ADD_MSG9","Amount of order");
DEFINE("_CART_ORDER_ADD_MSG10","Contact no.");
DEFINE("_CART_ORDER_ADD_MSG11","Address");
DEFINE("_CART_ORDER_ADD_MSG12","Customer Service email:");
DEFINE("_CART_ORDER_ADD_MSG13","Dear user");
DEFINE("_CART_ORDER_ADD_MSG14","A new order received, please process your order as soon as possible through system:");
DEFINE("_CART_NET_ERROR_MSG2","System error, please contact customer service");
DEFINE("_POINTS_NOT_ENOUGH","Points not enough");


//聯絡我們
DEFINE("_CONTACT_WRITE_MSG","Must fill");
DEFINE("_CONTACT_TITLE","Contact us");
DEFINE("_CONTACT_NAME","Name");
DEFINE("_CONTACT_TEL","Contact");
DEFINE("_CONTACT_EMAIL","Email");
DEFINE("_CONTACT_TYPE","Enquiry");
DEFINE("_CONTACT_CITY","City of residence");
DEFINE("_CONTACT_MSG","Message:");
DEFINE("_CONTACT_SUCCESS_MSG","The message sent! Thank you for your message and support, we will contact you as soon as possible.");

//自訂頁面
DEFINE("_DBPAGE_ABOUTUS","About GoodARCH");


//會員中心
DEFINE("_MEMBER_NO_DATA","Info not found");
DEFINE("_MEMBER_ERROR_CARD","Invalid card");
DEFINE("_MEMBER_SID_REPEAT","IC Number has been used");
DEFINE("_MEMBER_SID_REPEAT_MEMBER","IC Number has been used");
DEFINE("_MEMBER_EMAIL_REPEAT","Email has been used");
DEFINE("_MEMBER_SID_ERROR","Invalid IC Number");
DEFINE("_MEMBER_EMAIL_ERROR","Invalid email");
DEFINE("_MEMBER_SC1","Northern Region Joint Service Center");
DEFINE("_MEMBER_SC1_ADDR","11F.-1, No.266, Sec. 1, Wenhua 2nd Rd., Linkou Dist., New Taipei City 244, Taiwan");
DEFINE("_MEMBER_SC2","Hsinchu Service Center");
DEFINE("_MEMBER_SC2_ADDR","14F.-6, No.27, Guanxin Rd., East Dist., Hsinchu City 300, Taiwan");
DEFINE("_MEMBER_SC3","Taichung Service Center");
DEFINE("_MEMBER_SC3_ADDR","8F.-3, No.666, Sec. 2, Wuquan W. Rd., Nantun Dist., Taichung City 408, Taiwan");
DEFINE("_MEMBER_SC4","Yunlin Service Center");
DEFINE("_MEMBER_SC4_ADDR","1F., No.52, Wenke Rd., Huwei Township, Yunlin County 632, Taiwan");
DEFINE("_MEMBER_SC5","Kaohsiung Service Center");
DEFINE("_MEMBER_SC5_ADDR","11F.-2, No.315, Minghua Rd., Gushan Dist., Kaohsiung City 804, Taiwan");
DEFINE("_MEMBER_SC6","Tainan Global Business Headquarter");
DEFINE("_MEMBER_SC6_ADDR","No.23, Gongye 1st Rd., Annan Dist., Tainan City 709, Taiwan");
DEFINE("_MEMBER_SIGNUP_SUCCESS","registration success");
DEFINE("_MEMBER_HAS_LOGIN","You are logged in");
DEFINE("_MEMBER_EMAILCHK_MSG1","GoodARCH Member Certification Letter");
DEFINE("_MEMBER_EMAILCHK_MSG2","Dear GoodARCH members:");
DEFINE("_MEMBER_EMAILCHK_MSG3","To ensure your e-mail is correct, please verify through this mail to activate your online shopping.");
DEFINE("_MEMBER_EMAILCHK_MSG4","");
DEFINE("_MEMBER_EMAILCHK_MSG5","Verification method");
DEFINE("_MEMBER_EMAILCHK_MSG6","Please click the link below to verify");
DEFINE("_MEMBER_EMAILCHK_MSG7","Click here to verify member account");
DEFINE("_MEMBER_EMAILCHK_MSG8","※Note: This email is automatically generated and sent by the system, please do not reply, if you have any questions, please contact customer service");
DEFINE("_MEMBER_EMAILCHK_MSG9","Send verification mail");
DEFINE("_MEMBER_ERROR","Invalid request");
DEFINE("_MEMBER_EMAILCHK_MSG10","Complete verification, you may start shopping now.");
DEFINE("_MEMBER_NO_MEMBER","Staff info not found, please contact relevant center");
DEFINE("_MEMBER_PAY_SUCCESS","Payment success");
DEFINE("_MEMBER_NO_BONUS","Insufficient bonus");
DEFINE("_MEMBER_SELECT_ORDER","Re-select order");
DEFINE("_MEMBER_NO_OEDER","Order not found");
DEFINE("_MEMBER_CFM_RECEIPT","Confirm received");
DEFINE("_MEMBER_LOGIN_FIRST","Please login");
DEFINE("_MEMBER_ENTER_PWD","Please enter password");
DEFINE("_MEMBER_ERROR_MSG","Password changed, please login with new password");
DEFINE("_MEMBER_NO_MEMBER2","Member not found");
DEFINE("_MEMBER_USER","User");
DEFINE("_MEMBER_RESET_PWD_MSG1","Reset password confirm");
DEFINE("_MEMBER_RESET_PWD_MSG2","Dear");
DEFINE("_MEMBER_RESET_PWD_MSG3","Hi");
DEFINE("_MEMBER_RESET_PWD_MSG4","Please click the link below to reset the password. If you have not requested a password reset on this website, please ignore this mail.");
DEFINE("_MEMBER_SEND_SUCCESS","Successfully sent");
DEFINE("_MEMBER_EMAIL_USERD","This email has been used");
DEFINE("_MEMBER_PWD_ERROR_MSG1","The old and new passwords cannot be empty");
DEFINE("_MEMBER_PWD_ERROR_MSG2","Old password error");
DEFINE("_MEMBER_UPDATE_SUCCESS","Successfully changed");
DEFINE("_MEMBER_SID_EMPTY","The IC Number cannot be empty");
DEFINE("_MEMBER_SID_REPEAT","This IC Number has been registered");
DEFINE("_MEMBER_LOGINID_ENPTY","Account cannot be empty");
DEFINE("_MEMBER_EMAIL_USED","This email has been registered");
DEFINE("_MEMBER_CARD_EMPTY","The membership card number cannot be empty");
DEFINE("_MEMBER_CARD_USED","This membership card number has already been used");
DEFINE("_MEMBER_NO_DISTRIBUTOR","Member info not found");
DEFINE("_MEMBER_LOGIN_SUCCESS","Login Success");
DEFINE("_MEMBER_LOGIN_FAIL","Login failed");

//最新消息
DEFINE("_NEWS_NO_DATA","Info not found");

//商品
DEFINE("_PRODUCT_NO_DATA","Item not found");

//EWAYS
DEFINE("_EWAYS_NO_VIDEO","No videos");
DEFINE("_EWAYS_NO_AD","No ads");
DEFINE("_EWAYS_NO_ADVROLLS_IMAGE","Carousel not uploaded yet");
DEFINE("_EWAYS_SELECT_TAKETYPE","Please select collection method");
DEFINE("_EWAYS_SELECT_PAYTYPE","Please select payment method");
DEFINE("_EWAYS_SELECT_PRODUCT","Please select item");
DEFINE("_EWAYS_CART_MSG1","There are bonus item in the shopping cart, please empty cart first");
DEFINE("_EWAYS_CART_MSG2","There are normal item in the shopping cart, please empty cart first");
DEFINE("_EWAYS_SUCCESS","Operation succeeded");
DEFINE("_EWAYS_ADDPROD","add on");
DEFINE("_EWAYS_CART_EMPTY","Shopping cart is empty");
DEFINE("_EWAYS_TAKE_TYPE1","Shipping");
DEFINE("_EWAYS_TAKE_TYPE2","Self collection");
DEFINE("_EWAYS_TAKE_TYPE3","Cash on delivery");
DEFINE("_EWAYS_PAY_TYPE1","Cash on delivery");
DEFINE("_EWAYS_PAY_TYPE2","Online banking");
DEFINE("_EWAYS_PAY_TYPE3","Credit card");
DEFINE("_EWAYS_PAY_TYPE4","ATM virtual account");
DEFINE("_EWAYS_PAY_TYPE5","Cash on store collection");
DEFINE("_EWAYS_PAY_TYPE6","Credit card");
DEFINE("_EWAYS_PAY_TYPE7","Online banking");
DEFINE("_EWAYS_ESIGNUO_MSG1","Notification of e-member successful membership");
DEFINE("_EWAYS_ESIGNUO_MSG2","Welcome");
DEFINE("_EWAYS_ESIGNUO_MSG3","Account");
DEFINE("_EWAYS_ESIGNUO_MSG4","Password");
DEFINE("_EWAYS_ESIGNUO_MSG5","Member ID:");
DEFINE("_EWAYS_ESIGNUO_MSG6","Please remember your member ID, if you need a physical membership card, please download the application form to the dealer area! Thank you!");
DEFINE("_EWAYS_ESIGNUO_MSG7","");
DEFINE("_EWAYS_ESIGNUO_MSG8","※Note: This email is automatically generated and sent by the system, please do not reply, if you have any questions, please contact customer service");



//後台員工
DEFINE("_ADMINMANAGERS_SAME_USER","An employee record with the same account already exists");
DEFINE("_ADMINMANAGERS_NO_SELECT","No items selected");

//後台-快速連結
DEFINE("_BOTTOMMENU_USED_NOT_DELETE","This page has been used and cannot be deleted");


//後台-員工管理
DEFINE("_MEMBERS_EXPORT_DATA","Member data export ");
DEFINE("_MEMBERS_AUDIT_MSG1","Passed Distributor member review");
DEFINE("_MEMBERS_AUDIT_MSG2","Cumulative bonus has not reached minimum requirement");
DEFINE("_MEMBERS_SALESCHK0","General member");
DEFINE("_MEMBERS_SALESCHK3","Existing Distributor member review");
DEFINE("_MEMBERS_SALESCHK2","Distributor member review in progress");
DEFINE("_MEMBERS_SALESCHK1","Formal");
DEFINE("_MEMBERS_SALESCHK4","Member");
DEFINE("_MEMBERS_MEMTYPE1","e-sign up");
DEFINE("_MEMBERS_MEMTYPE2","general");
DEFINE("_MEMBERS_EXCEL_TITLE","Member ID, Identity card number, member name, mailing address, telephone, mobile, address, E-mail, date of birth, application date (date of payment), upline ID, upline name, upline contact, upline mobile, Member");
DEFINE("_MEMBERS_SAME_EMAIL","The same email address already exists");
DEFINE("_MEMBERS_SAME_CARD_NO","The same member card number already exists");
DEFINE("_MEMBERS_SAME_NO","The same member ID already exists");
DEFINE("_MEMBERS_LOGOUT","Sign out");
DEFINE("_MEMBERS_LOGIN","Sign in");


//後台-訂單
DEFINE("_ORDER_ORDER_EMPTY","This order does not exist");
DEFINE("_ORDER_UPDATE_PAYDATE","Updated payment date");
DEFINE("_ORDER_UPDATE_INVOICE_INFO","Receipt information updated");
DEFINE("_ORDER_UPDATE_RECEIVE","Receipient information updated");
DEFINE("_ORDER_UNABLE_MERGE","Unable to merge orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG1","Unable to merge cash on delivery orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG2","Unable to merge paid orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG3","Unable to merge different member orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG4","Unable to merge different payment method orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG5","Unable to merge different collection method orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG6","Unable to merge different order status orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG7","Unable to merge different delivery timing orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG8","Unable to merge different receipient orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG9","Unable to merge different receipients'contact orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG10","Unable to merge different receipients'address orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG11","Unable to merge different delivery date orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG12","Unable to merge different invoice orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG13","Unable to merge different invoice header orders");
DEFINE("_ORDER_UNABLE_MERGE_MSG14","Unable to merge different invoice serial number orders");
DEFINE("_ORDER_SHIPPING_MSG","Shipping notice");
DEFINE("_ORDER_SHIPPING_MSG1","Dear");
DEFINE("_ORDER_SHIPPING_MSG2","member:");
DEFINE("_ORDER_SHIPPING_MSG3","The goods you ordered have been shipped, please collect your goods within the period, thank you for your cooperation. The shipping information is as follows:");
DEFINE("_ORDER_SHIPPING_MSG4","Order number");
DEFINE("_ORDER_SHIPPING_MSG5","Order date");
DEFINE("_ORDER_SHIPPING_MSG6","order amount");
DEFINE("_ORDER_SHIPPING_MSG7","Courier company name");
DEFINE("_ORDER_SHIPPING_MSG8","Courier tracking number");
DEFINE("_ORDER_SHIPPING_MSG9","Courier tracking URL");
DEFINE("_ORDER_SHIPPING_MSG10","Order Details");
DEFINE("_ORDER_SHIPPING_MSG11","Contact");
DEFINE("_ORDER_SHIPPING_MSG12","Address");
DEFINE("_ORDER_SHIPPING_MSG13","Customer service mailbox");
DEFINE("_ORDER_INVOICETYPESTR0","Request receipt copy");
DEFINE("_ORDER_INVOICETYPESTR1","Donation receipt to other unit");
DEFINE("_ORDER_INVOICETYPESTR2","Do not ask for receipt copy");

DEFINE("_ORDER_EXPORT_STR1","Order number");
DEFINE("_ORDER_EXPORT_STR2","Order date");
DEFINE("_ORDER_EXPORT_STR3","Order tax excluded amount");
DEFINE("_ORDER_EXPORT_STR4","Order tax included amount");
DEFINE("_ORDER_EXPORT_STR5","Member ID");
DEFINE("_ORDER_EXPORT_STR6","Purchaser");
DEFINE("_ORDER_EXPORT_STR7","Total PV");
DEFINE("_ORDER_EXPORT_STR8","Total BV");
DEFINE("_ORDER_EXPORT_STR9","Actual purchaser");
DEFINE("_ORDER_EXPORT_STR10","delivery address");
DEFINE("_ORDER_EXPORT_STR11","Order number");
DEFINE("_ORDER_EXPORT_STR12","Item");
DEFINE("_ORDER_EXPORT_STR13","Product number");
DEFINE("_ORDER_EXPORT_STR14","Product name");
DEFINE("_ORDER_EXPORT_STR15","Quantity");
DEFINE("_ORDER_EXPORT_STR16","Unit price");
DEFINE("_ORDER_EXPORT_STR17","Tax excluded amount");
DEFINE("_ORDER_EXPORT_STR18","Tax included amount");
DEFINE("_ORDER_EXPORT_STR19","Recipient");
DEFINE("_ORDER_EXPORT_STR20","Recipient phone");
DEFINE("_ORDER_EXPORT_STR21","Colour");
DEFINE("_ORDER_EXPORT_STR22","Size");
DEFINE("_ORDER_EXPORT_STR23","Original unit price");
DEFINE("_ORDER_EXPORT_STR24","type of activity");
DEFINE("_ORDER_EXPORT_STR25","Last four digits of the card number");
DEFINE("_ORDER_EXPORT_STR26","Remarks");
DEFINE("_ORDER_EXPORT_MSG","There are too many search results, please set filter criteria before exporting");
DEFINE("_ORDER_EXPORT_STR27","Free gift");
DEFINE("_ORDER_EXPORT_STR28","Shipping");
DEFINE("_ORDER_SHIPDATE_ERROR","Ship date must above today");


//後台-獎金轉點數
DEFINE("_PM_EXPORT_STR_1","Mmember No");
DEFINE("_PM_EXPORT_STR_2","Member Name");
DEFINE("_PM_EXPORT_STR_3","Set Date");
DEFINE("_PM_EXPORT_STR_4","Amount");
DEFINE("_PM_EXPORT_STR_5","Type");
DEFINE("_PM_EXPORT_STR_6","Invalid");
DEFINE("_PM_EXPORT_STR_7","Carryied Forward");

//後台-商品管理
DEFINE("_PRODUCTS_SELECT_FILE","Please select file");
DEFINE("_PRODUCTS_EXCEL_FILE","Excel file format restrictions: xls, xlsx");
DEFINE("_PRODUCTS_IMPORT_MSG1","Item in column  ");
DEFINE("_PRODUCTS_IMPORT_MSG2","");
DEFINE("_PRODUCTS_IMPORT_MSG3","Some files could not be imported, please check and upload again");
DEFINE("_PRODUCTS_IMPORT_MSG4","Item import completed, please enter the product page to fill in other fields and activate item");
DEFINE("_PRODUCTS_COPY_SUCCESS","successfully copied");
DEFINE("_PRODUCTS_ROOT","Root directory");
DEFINE("_PRODUCTS_DELETE_ERROR","There is an order containing this item, unable to delete");


//後台-庫存管理
DEFINE("_PROINSTOCK_DANGER","Danger");
DEFINE("_PROINSTOCK_SAFE","Safe");

//0806 EMAIL
DEFINE("_EMAIL_MEMBER","Member e-Registration Successful Notification");
DEFINE("_EMAIL_MEMBER_1","Successful notification of distributors e-enrollment");
DEFINE("_EMAIL_code","Verify your new Homeway GoodARCH account ");
DEFINE("_EMAIL_notification","OTP Notification");
DEFINE("_EMAIL_dear","Dear");
DEFINE("_EMAIL_hello","");
DEFINE("_EMAIL_membership","Distributor / Membership ");
DEFINE("_EMAIL_register","registration");
DEFINE("_EMAIL_msg1","To verify your email address,");
DEFINE("_EMAIL_msg2","please use the following One Time Password (OTP):");
DEFINE("_EMAIL_msg3","");
DEFINE("_EMAIL_msg4","Please enter your basic information and complete email verification within 15 minutes.");
DEFINE("_EMAIL_msg5","※ This email is a systematic automatic email, please do not reply. If you have any questions, please contact our service line (603) 9054 - 7589. Thank you.");
DEFINE("_EMAIL_msg6","Homeway GoodARCH Website：");
DEFINE("_EMAIL_msg7","Homeway GoodARCH Online shopping：");
DEFINE("_EMAIL_msg8","");
DEFINE("_EMAIL_msg9"," Homeway GoodARCH");
DEFINE("_EMAIL_msg10","Registration success ");
DEFINE("_EMAIL_msg11","Welcome  to Homeway GoodARCH!We are so glad you joined us.");
DEFINE("_EMAIL_msg12","Note: Please do not reply to this email as this is an automatic response to your registration. Please contact our customer service personnel if you have any inquiries, thank you.");
DEFINE("_EMAIL_msg13","360");
DEFINE("_EMAIL_msg14","online rebate points have been added to your account.");
DEFINE("_EMAIL_msg15","Lets use it now!");
DEFINE("_EMAIL_msg16","Let's Shop → ");
DEFINE("_EMAIL_msg17","Member's Benefits → ");
DEFINE("_EMAIL_msg19","Verified successfully");
DEFINE("_EMAIL_msg20","OTP invalid");
DEFINE("_EMAIL_msg21","OTP failed");
DEFINE("_EMAIL_msg22","");
DEFINE("_EMAIL_msg23","ID");
DEFINE("_EMAIL_msg24","Homeway Online Shopping");
DEFINE("_EMAIL_msg25","You can start using Homeway GoodARCH Online shopping.");


//雜項
DEFINE("_SET_PM_MIN","Minimal must bigger than 25");
DEFINE("_PM_KIND_1","Monthly");
DEFINE("_PM_KIND_2","Once");
DEFINE("_PM_IS_INV_0","valid");
DEFINE("_PM_IS_INV_1","Invalid");
DEFINE("_PM_CF_1","Done");
DEFINE("_PM_CF_0","Not Done");
DEFINE("_TOTAL_AMT_0","Your order has been submitted");
DEFINE("_PM_DONE","Done。");
DEFINE("_PM_FAIL","Failed。");
DEFINE("_BILL_ADDRESS_ERROR","Bill address 1 more than 60 characters");
DEFINE("_BILL_ADDRESS_EMPTY","Bill address can't empty");
DEFINE("_BILL_ADDRESS2_ERROR","Bill address 2 more than 60 characters");
DEFINE("_BILL_CITY_EMPTY","Bill Stat can't empty");

//補翻譯
DEFINE("_MEMBER_SUCCESS_SMS","Dear member, congratulations on your successful registration, welcome to GoodARCH and thank you for joining us. You can access the online shopping platform with your registered ID and password and start your purchases!");
DEFINE("_MEMBER_SID_USED","Duplicate registration with existing IC number, please contact the customer service for assistant, thank you.");
DEFINE("_MEMBER_MOBILE_USED","Existing Contact No.");
DEFINE("_MEMBER_ERROR_4","Invalid Sponsor ID No., please re-enter.");
DEFINE("_MEMBER_RETURNED","Returned");
DEFINE("_MEMBER_RETURNED_WEB","Returned online");
DEFINE("_MEMBER_INVALID_CODE","Invalid verification code");
DEFINE("_MEMBER_NONEXIST","Non-existing member");
DEFINE("_MEMBER_UNOFFICIAL","Unofficial member account");
DEFINE("_MEMBER_ERROR5","Application form not submitted ");
DEFINE("_MEMBER_ERROR6","Unrenewed membership");
DEFINE("_MEMBER_EXIST_IC","Existing IC number as member");
DEFINE("_MEMBER_EXIST_MOBILE","Existing Contact No. as member");
DEFINE("_MEMBER_SEND_FAIL","Sending Failed");
DEFINE("_MEMBER_MOBILE_INVALID","Unverified Contact No.");
DEFINE("_MEMBER_EMAIL_INVALID","Unverified Email");
DEFINE("_MEMBER_REC_CODE","This is your GoodARCH verification code");
DEFINE("_MEMBER_E21_EMPTY","e21 is empty");
DEFINE("_MEMBER_ERROR_7","Missing content");
DEFINE("_MEMBER_GENERATION","Generation");
DEFINE("_MEMBER_OVERSEA_POINTS","[Oversea Incentive Points]");
DEFINE("_MEMBER_MTD_EMPTY","mtd is empty");
DEFINE("_MEMBER_NO_VOUCHER","Unable to find the voucher");
DEFINE("_MEMBER_ERROR_8","Insufficient redeemable amount");
DEFINE("_MEMBER_ERROR_9","Invalid amount");
DEFINE("_PW_ERROR_MSG","Please complete the email or mobile verification");
DEFINE("_MEMBER_MOBILE","Mobile");
DEFINE("_MEMBER_ACCPW","Password");
DEFINE("_CODE","Code");
DEFINE("_CODE_EMPTY","Code can't empty");









?>