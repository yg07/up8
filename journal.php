<?php
require_once("init.php");
require_once("check-login.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>БД Студент - Журнал</title>
<meta charset="utf-8" />
<link rel="stylesheet" media="screen" href="css/superfish.css"/>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css" />
<link rel="stylesheet" media="screen" href="css/ui.jqgrid.css"/>
<script src="js/jquery-3.6.0.js" type="text/javascript" ></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-ru.js" type="text/javascript"></script>
<script src="js/hoverIntent.js" type="text/javascript" ></script>
<script src="js/superfish.min.js" type="text/javascript" ></script>

<style type="text/css">
.menu-header {
  padding:0px; 
  margin:0px; 
  width:250px; 
  height:45px; 
  position:fixed; 
  top:0px; 
  left:0px; 
  z-index:100;
}
.table_div {
  padding:0px; 
  margin:0px; 
  width:100%; 
  position:fixed; 
  top:45px; 
  left:0px; 
}
</style >


<script  type="text/javascript">

$(function(){

var cm = [
          {
          name:'id', index:'id', hidden: true, editable:false, key:true
          },
          {
          name:'day', index:'day', sortable:true, editable:true, editrules:{required:true}
          },
          {
          name:'grp', index:'g.name', sortable:true, editable:false
          },
          {
          name:'predmet', index:'pr.name', sortable:true, editable:true, edittype:"select", editoptions: {dataUrl:'get-predmet.php'}, editrules:{required:true}
          },
          {
          name:'student', index:'concat(s.fam," ",s.name," ",s.otch)', sortable:true, editable:true, edittype:"select", editoptions: {dataUrl:'get-student.php'}, editrules:{required:true}
          },
          {
          name:'city', index:'c.name', sortable:true, editable:false
          },
          {
          name:'prepod', index:'concat(p.fam," ",p.name," ",p.otch)', sortable:true, editable:true, edittype:"select", editoptions: {dataUrl:'get-prepod.php'}, editrules:{required:true}
          },
          {
          name:'present', index:'pres', sortable:true, editable:true, edittype:"select", editoptions: {value:"1:присутствовал;2:отсутствовал"}, editrules:{required:true}
          },
          {
          name:'mark', index:'mark', sortable:true, editable:true, edittype:"select", editoptions: {value:"5:5;4:4;3:3;2:2"}
          }
];


var prmAddEdit = {dataheight: 200, height: 290, width: 500, jqModal: false, closeOnEscape: true, closeAfterAdd:true, closeAfterEdit:true, savekey:[true,13],
    errorTextFormat: function (response) {
        return '<span class="ui-icon ui-icon-alert" ' +
                'style="float:left; margin-right:.3em;"></span>' +
                response.responseText;
    }
};    
var prmDel =  {    width: 500,
    errorTextFormat: function (response) {
        return '<span class="ui-icon ui-icon-alert" ' +
                'style="float:left; margin-right:.3em;"></span>' +
                response.responseText;
    }
};
var prmView = {width: 1300};
var prmSearch = {width: 700, recreateFilter: true, multipleSearch: false, multipleGroup:false, closeAfterSearch: true};


  $("#table_main").jqGrid({
      url:'journal-load.php',
      editurl:'journal-edit.php',
      datatype: 'json',
      mtype: 'GET',
      sortname: "day",
      sortorder: "desc",
      height: window.innerHeight-135,
      width: window.innerWidth,
      autowidth: true,
      shrinkToFit: true,
      rowNum: 30,
      rowList: [30,50,75,100],
      colNames:['Id','Дата','Группа','Предмет','Студент','Город','Преподаватель','Присутствие','Оценка'],
      colModel: cm,
      //multiselect: true,
      viewrecords: true,
      rownumbers: true,
      hidegrid: false,
      gridview: true,
      pager: $('#gridpager'),
      caption: "Журнал успеваемости",
      loadError: function (jqXHR, textStatus, errorThrown) {
                            alert('Сообщение сервера базы данных: ' + jqXHR.responseText);
      },
      ondblClickRow: function(){
            var row_id = $("#table_main").jqGrid('getGridParam','selrow');
            if(row_id != null) $('#table_main').jqGrid('editGridRow',row_id, prmAddEdit);
            else alert("Please Select Row");
      }
  });
      


  $("#table_main").jqGrid('navGrid','#gridpager',
      {view:true,search:true,edit:true,add:true,del:true}, //options
      prmAddEdit, // edit options
      prmAddEdit, // add options
      prmDel, // del options
      prmSearch, // search options
      prmView // view options
  );


  $(window).resize(function() {
    $('#table_main').jqGrid('setGridHeight',window.innerHeight-130);
    $('#table_main').jqGrid('setGridWidth',window.innerWidth);
  });  
	
  $("ul.sf-menu").superfish();
});

</script>
</head>
<body>
<?php include_once("body.php")?>
</body>
</html>
