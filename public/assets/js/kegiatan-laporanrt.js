$(document).ready(function(){
    role = $('.roleuser').val();

var store = new DevExpress.data.CustomStore({
    key: "id",
    load: function() {
        return sendRequest(apiurl + "/kegiatan-laporanrt");
    },
    insert: function(values) {
        values.lampiran = $path;
        return sendRequest(apiurl + "/kegiatan-laporanrt", "POST", values);
    },
    update: function(key, values) {
        if($path!=""){
            values.lampiran = $path;
        }
        return sendRequest(apiurl + "/kegiatan-laporanrt/"+key, "PUT", values);
    },
    remove: function(key) {
        return sendRequest(apiurl + "/kegiatan-laporanrt/"+key, "DELETE");
    }
});

function moveEditColumnToLeft(dataGrid) {
    dataGrid.columnOption("command:edit", { 
        visibleIndex: -1,
        width: 80 
    });
}

// attribute
var dataGrid = $("#kegiatan-laporanrt").dxDataGrid({    
    dataSource: store,
    allowColumnReordering: true,
    allowColumnResizing: true,
    columnsAutoWidth: true,
    columnMinWidth: 80,
    columnHidingEnabled: true,
    wordWrapEnabled: true,
    showBorders: true,
    filterRow: { visible: true },
    filterPanel: { visible: true },
    headerFilter: { visible: true },
    // selection: {
    //     mode: "multiple"
    // },
    editing: {
        useIcons:true,
        mode: "popup",
        allowAdding: true,
        allowUpdating: true,
        allowDeleting: true,
        popup: {
            title: "Kegiatan RT",
            showTitle: true,
            width: 700,
            height: 525,
            position: {
                my: "center",
                at: "center",
                of: window
            },
            toolbarItems: [
                {
                    toolbar: 'bottom',
                    location: 'after',
                    widget: 'dxButton',
                    options: {
                        onClick: function(e) {
                            if($path=="") {
                                DevExpress.ui.notify("Silakan pilih gambar Anda sebelum menyimpan data","error");
                            } else {
                                if($adafile) {
                                    DevExpress.ui.notify("Harap selesaikan unggahan Anda sebelum menyimpan data","error");
                                    e.cancel = true;
                                } else {
                                    datagrid.saveEditData();
                                }
                            }
                        },
                        text: 'Simpan'
                    }
                },
                {
                    toolbar: 'bottom',
                    location: 'after',
                    widget: 'dxButton',
                    options: {
                        onClick: function(e) {
                            datagrid.cancelEditData();
                        },text: 'Batal'
                    }
                }
            ]
        },
        form: {
            items: [{
                itemType: "group",
                colCount: 2,
                colSpan: 2,
                items: [
                    {
                        dataField: "id_kegiatan",
                    },
                    {
                        dataField: "tanggal",
                    },
                    {
                        dataField: "keterangan",
                    }
                ]
            },{
                itemType: "group",
                colCount: 2,
                colSpan: 2,
                caption: "Photo",
                items: [{
                  dataField: "lampiran",
                  colSpan: 2
                }]
              }]
        },
        
    },
    scrolling: {
        mode: "virtual"
    },
    columns: [
        {
            caption: '#',
            formItem: { 
                visible: false
            },
            width: 40,
            cellTemplate: function(container, options) {
                container.text(options.rowIndex +1);
            }
        },
        { 
            caption: "Jenis Kegiatan",
            dataField: "id_kegiatan",
            // width: 250,
            editorType: "dxSelectBox",
            lookup: {
                dataSource: listKegiatan,  
                valueExpr: 'id',
                displayExpr: 'nama_kegiatan',
            },
            validationRules: [{ type: "required" }]
        },
        { 
            dataField: "tanggal",
            validationRules: [
                { type: "required" }
            ],
            dataType:"date", format:"dd-MM-yyyy",displayFormat: "dd-MM-yyyy",
        },
        { 
            dataField: "keterangan",
            caption: "keterangan",
            validationRules: [{ type: "required" }]
        },
        {
            dataField: "lampiran",
            width: 70,
            allowFiltering: false,
            allowSorting: false,
            cellTemplate: cellTemplate,
            // cellTemplate: function (container,options) {
            //     if(options.value!=""){
            //         $("<div />").dxButton({
            //             icon: "download",
            //             stylingMode: "contained",
            //             type: "success",
            //             target: "_blank",
            //             width: 50,
            //             height: 25,
            //             onClick: function (e) {
            //                 window.open('upload/'+options.value, '_blank');
            //             }
            //         }).appendTo(container);
            //     }
            // },
            editCellTemplate: editCellTemplate
        }
       
    ],
    // export: {
    //     enabled: true,
    //     fileName: "master-user",
    //     excelFilterEnabled: true,
    //     allowExportSelectedData: true
    // },
    onInitialized: function(e) {
        datagrid = e.component;
    },
    onInitNewRow: function(e) {  
        // e.data.bulan = new Date().getMonth()+1;
        // e.data.tahun = new Date().getFullYear();
    } ,
    onContentReady: function(e){
        // moveEditColumnToLeft(e.component);
    },
    onEditorPreparing: function(e) {
        // datagrid.getEditor("keterangan").option("value", 'some new text'); 
    },
    onToolbarPreparing: function(e) {
        dataGrid = e.component;

        e.toolbarOptions.items.unshift({						
            location: "after",
            widget: "dxButton",
            options: {
                hint: "Refresh Data",
                icon: "refresh",
                onClick: function() {
                    dataGrid.refresh();
                }
            }
        })
    },
}).dxDataGrid("instance");

function cellTemplate(container, options) {
  let imgElement = document.createElement("img");
  imgElement.setAttribute("src", "upload/" + options.value);
  imgElement.setAttribute("height", "50");
  imgElement.setAttribute("width", "70");
  container.append(imgElement);
}

function editCellTemplate(cellElement, cellInfo) {
  let buttonElement = document.createElement("div");
  buttonElement.classList.add("retryButton");
  let retryButton = $(buttonElement).dxButton({
    text: "Retry",
    visible: false,
    onClick: function() {
      // The retry UI/API is not implemented. Use a private API as shown at T611719.
      for (var i = 0; i < fileUploader._files.length; i++) {
        delete fileUploader._files[i].uploadStarted;
      }
      fileUploader.upload();
    }
  }).dxButton("instance");

  $path = "";
  $adafile = "";
  let fileUploaderElement = document.createElement("div");
  let fileUploader = $(fileUploaderElement).dxFileUploader({
    multiple: false,
    accept: "image/*",
    uploadMode: "instantly",
    name: "myFile",
    uploadUrl: apiurl + "/upload-berkas",
    onValueChanged: function(e) {
      let reader = new FileReader();
      reader.onload = function(args) {
        imageElement.setAttribute('src', args.target.result);
      }
      reader.readAsDataURL(e.value[0]); // convert to base64 string
    },
    onUploaded: function(e){
        $path = e.request.response;
        $adafile = false;
        cellInfo.setValue(e.request.responseText);
        retryButton.option("visible", false);
    //   cellInfo.setValue("upload/" + e.request.response);
    //   retryButton.option("visible", false);
    },
    onUploadError: function(e){
        $path = "";
        DevExpress.ui.notify(e.request.response,"error");
    //   let xhttp = e.request;
    //   if(xhttp.status === 400){
    //     e.message = e.error.response;
    //   }
    //   if(xhttp.readyState === 4 && xhttp.status === 0) {
    //     e.message = "Connection refused";
    //   }
    //   retryButton.option("visible", true);
    }
  }).dxFileUploader("instance");

  let imageElement = document.createElement("img");
  imageElement.classList.add("uploadedImage");
  imageElement.setAttribute('src', "upload/" +cellInfo.value);
  imageElement.setAttribute('height', "50");

  cellElement.append(imageElement);
  cellElement.append(fileUploaderElement);
  cellElement.append(buttonElement);
}

})