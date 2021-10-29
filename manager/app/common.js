/*
	訊息樣式
*/
function msgStyle(msg){
	return "<font size=5 color='000000'>"+msg+"</font>";
}

function seteditor(id){
	
	if($("#"+id).length>0){
		if(CKEDITOR) {
		  CKEDITOR.replace(id, {
			'extraPlugins': 'showblocks,div,doksoft_backup,doksoft_stat',
			'filebrowserImageBrowseUrl': '/lib/ckeditor/plugins/imgbrowse/imgbrowse.html',
			'filebrowserImageUploadUrl': '/lib/ckeditor/plugins/imgupload/imgupload.php'
			//'filebrowserBrowseUrl': '/lib/ckfinder/ckfinder.html',
			//'filebrowserUploadUrl': '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
		  });
		}
	}
}

function parseNum(arr){
	var tmp;
	if(typeof arr=='object'){
		tmp=new Array();
		for(var key in arr){ 
			tmp[key]=parseNum(arr[key]);
		}
	}else{
		tmp='';
		if(!isNaN(arr) && arr[0]!=0){
			tmp=parseFloat(arr);
		}else{
			tmp=arr;
		}
	}
	return tmp;
}