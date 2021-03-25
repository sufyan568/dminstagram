"use strict";
$("document").ready(function(){

$('[data-toggle="popover"]').popover(); 
$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;}); 

$("#addon_url_upload").uploadFile({
    url:base_url+"themes/upload_addon_zip",
    fileName:"myfile",
    maxFileSize:100*1024*1024,
    showPreview:false,
    returnType: "json",
    dragDrop: true,
    showDelete: true,
    multiple:false,
    maxFileCount:1, 
    showDelete:false,
    acceptFiles:".zip",
    deleteCallback: function (data, pd) {
        var delete_url= base_url+'themes/delete_uploaded_zip';
          $.post(delete_url, {op: "delete",name: data},
              function (resp,textStatus, jqXHR) {                         
              });
       
     },
     onSuccess:function(files,data,xhr,pd)
       {
           var data_modified = data;
           window.location.assign(base_url+'themes/lists'); 
       }
});
});