/* 
 * JSImageUploader
 * Copyright (c) 2013 Rafael Lucio (poste9@gmail.com)
 * licensed under the GPL (GPL-LICENSE.txt) license.
 *
 * e.g. JavaScript requires the Access-Control-Allow-Origin response header set to something 
 * that will allow the client to make the request.
 */
var JSImageUploader = function(){ 
	var self = this;
	
	/**
	* Base64 String of the image
	* @var String
	*/
	var _dataToUpload;
	/**
	* Input file element
	* @var HTMLInputElement
	*/
	var file = document.createElement('input');
	
	var proImg_chk_value = false;
	
	self.proImg_chk = function(){
		proImg_chk_value = true;
	};
	
	/**
	* Define if is going to use multiple upload
	* @var Boolean
	*/
	var multi = false;
	/**
	* Button to trigger choose file action
	* @var HTMLElement
	*/
	var trigger;
	
	/**
	* Properties that will change the image before upload
	* @var Object
	*/
	var imageProperties={};
	
	/**
	* Upload progress callback
	* @var Function
	*/
	var progressHandler = function(){};
	
	/**
	* Set the trigger
	* @param HTMLElement element
	* @function
	*/
	self.setTrigger = function(element) {
		trigger = element;
	};
	
	/**
	* Set the imageProperties
	* @param Object properties
	* @function
	*/
	self.setImageProperties = function(properties){
		imageProperties = properties;
	};
	
	/**
	* Set the progressHandler
	* @param Function handler
	* @function
	*/
	self.setProgressHandler = function(handler) {
		progressHandler = handler;
	};
	
	/**
	* Set the previewHandler
	* @param Function handler
	* @function
	*/
	var previewHandler;//<<初始化
	self.setPreviewHandler = function(handler) {
		previewHandler = handler;
	};
	
	/**
	* Upload the file to the url with the following optional parameters
	* @param String url
	*/
	self.upload = function(url) {
		var xhr = new XMLHttpRequest();
		
		xhr.open('POST', url, true);
		
		xhr.upload.addEventListener("progress", _progressHandler, false);
		xhr.upload.addEventListener("load", _loadHandler, false);
		xhr.upload.addEventListener("error", _errorHandler, false);
		xhr.upload.addEventListener("abort", _abortHandler, false);
		
		xhr.setRequestHeader('Content-Type', 'application/upload');
		xhr.send(_dataToUpload);
		
	};
	var imgpath='';
	self.getimgpath = function(){
		return imgpath;
	}
	var num=0;
	// init
	self.init = function() {
		if (!trigger) throw new Error('Trigger not defined');
		
		file.setAttribute('multiple', multi);
		file.setAttribute('type','file');
		file.setAttribute("accept", "image/jpeg, image/png");
		file.addEventListener('change', _changeHandler);
		
		trigger.addEventListener('click', function(event) {
			num=trigger.getAttribute("num");
			file.click();
		});
	};
	
	// Private functions
	var _changeHandler = function(event) {
		var files = event.target.files;
	
		var img = new Image();
		var reader = new FileReader();
			reader.addEventListener('load', function(e) {
				img.src = e.target.result;
				
				var canvas = document.createElement('canvas');
				var context = canvas.getContext('2d');
				
				var width  = imageProperties.width || img.width;
				var height = imageProperties.height || img.height;
				
				var imeSize = parseInt(img.src.length  / 1.37);
				
				var img_id = $(trigger).attr('id');
				if(imeSize > 2097152) //2M
				{
					$('#'+img_id+'_tobig').show();	
					return false;
				}
				if(proImg_chk_value){
					if(width/height>1.1 || width/height<0.9){
						alertify.alert("圖片需為正方型");
						return false;
					}
					if(imeSize>819200) //800k
					{
						alert("圖片過大，將自動縮小");
						width=700;
						height=700;
					}
				}
				$('#'+img_id+'_tobig').hide();
				
				img.addEventListener('load', function(evt) {
					var resizedImage = new Image();
					var resizedValues = _getResizedValues(width, height, img.width, img.height);
					
					var x=0,y=0;
					
					if (imageProperties.crop) {
						canvas.width = width;
						canvas.height = height;
						
						if (resizedValues.w > resizedValues.h) {
							x = (resizedValues.w - width) / 2 * -1;
						} else {
							y = (resizedValues.h - height) / 2  * -1;
						}
						
					} else {
						canvas.width = resizedValues.w;
						canvas.height = resizedValues.h;
					}

					context.drawImage(img, x, y, resizedValues.w, resizedValues.h);
					
					var preview = new Image();
					//preview.src = canvas.toDataURL(files[0].type);
					preview.src = img.src;
					imgpath= img.src;
					_dataToUpload = preview.src;
					
					previewHandler.apply(self, [new CustomEvent('preview', {detail : { preview: preview,num:num }})] );
					
				});
			});
			
		reader.readAsDataURL(files[0]);
		
	};
	
	
	
	/**
	* Get the resized values of the width and height
	* @param Number reqWidth
	* @param Number reqHeight
	* @param Number imgWidth
	* @param Number imgHeight
	*/
	var _getResizedValues = function(reqWidth, reqHeight, imgWidth, imgHeight) {
		var propWidth = imgWidth / reqWidth;
		var propHeight = imgHeight / reqHeight;
		var newWidth = reqWidth, newHeight = reqHeight;
		
		if (propWidth > propHeight) {
			newWidth = imgWidth * reqHeight / imgHeight;
		} else {
			newHeight = imgHeight * reqWidth / imgWidth;
		}
		
		return {
			w: newWidth,
			h: newHeight
		}
	};
	
	var _progressHandler = function(event) {
		console.log('progress handler');
		progressHandler.apply(self, [event]);
	};
	
	var _abortHandler = function(event) {
		console.log(event);
		console.log('abort handler');
	};
	
	var _loadHandler = function(event) {
		console.log(event);
		console.log('load handler');
	};
	
	var _errorHandler = function(event) {
		console.log(event);
		console.log('error handler');
	};
	
	return self;
};