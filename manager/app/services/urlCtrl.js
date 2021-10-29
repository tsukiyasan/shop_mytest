angular.module('managerApp').factory('urlCtrl',['$rootScope','$location', function ($rootScope,$location) {
    var key = "bibibobo";
    return {
        enaes: function(word){
        	if(word){
        		if(angular.isObject(word)){
        			word = angular.toJson(word);
        		}
        		
        		//encrypt
        		var rawStr = CryptoJS.AES.encrypt(word, key).toString();
        		var wordArray = CryptoJS.enc.Utf8.parse(rawStr);
        		return CryptoJS.enc.Base64.stringify(wordArray);
        	}
        	return word;
        },
        
        deaes: function(word){
           	//decrypt
            try{	
            	var parsedWordArray = CryptoJS.enc.Base64.parse(word);
            	var parsedStr = parsedWordArray.toString(CryptoJS.enc.Utf8);
            	var result = CryptoJS.AES.decrypt(parsedStr, key).toString(CryptoJS.enc.Utf8);
            	result = angular.fromJson(result);
            }catch(e){
                result = result === "" ? {} : false;
            	$location.url("index"); //解密失敗導回第一頁
            }
        	return result;
        },
        
        go: function(path, hash){
            var self = this;
            if(path == "-1") {
    			history.back();
    		} else {
    			$location.url(path + "#" + self.enaes(hash));
    		}
        }
    }
}]);