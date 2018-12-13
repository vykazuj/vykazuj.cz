<?php
// source: C:\xampp\htdocs\vykazuj\app\presenters/templates/Charge/default.latte

use Latte\Runtime as LR;

class Template2b83ee62a2 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'head' => 'blockHead',
	];

	public $blockTypes = [
		'content' => 'html',
		'head' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		$this->renderBlock('head', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 46');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
       
        
    <div class="col-12 col-lg-8 col-sm-12 col-xs-12 text-center panel-mid">        
        <div class="container timetable-blank text-left">
            Aktivní firma: 
          <select id = "company-select" class="client_not_name_label">
          </select> <br class="active-project-separator"> Aktivní projekt:  
          <select class="client_not_name_label" id="my-chargeable-projects">
              <br class="active-project-separator">
          </select>   
                    
        </div>
        
        <div class="container timetable-blank">
          <ul class="nav nav-pills">
            <li id="liPickYear">
                <select class="client_not_name_label" id="yearPick">
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2019">2020</option>
                </select>
            </li>
            <li class="monthLink<?php
		if ($actualMonth==1) {
			?> active<?php
		}
?>" month="1">Leden</li>
            <li class="monthLink<?php
		if ($actualMonth==2) {
			?> active<?php
		}
?>" month="2">Únor</a></li>
            <li class="monthLink<?php
		if ($actualMonth==3) {
			?> active<?php
		}
?>" month="3">Březen</a></li>
            <li class="monthLink<?php
		if ($actualMonth==4) {
			?> active<?php
		}
?>" month="4">Duben</a></li>
            <li class="monthLink<?php
		if ($actualMonth==5) {
			?> active<?php
		}
?>" month="5">Květen</a></li>
            <li class="monthLink<?php
		if ($actualMonth==6) {
			?> active<?php
		}
?>" month="6">Červen</a></li>
            <li class="monthLink<?php
		if ($actualMonth==7) {
			?> active<?php
		}
?>" month="7">Červenec</a></li>
            <li class="monthLink<?php
		if ($actualMonth==8) {
			?> active<?php
		}
?>" month="8">Srpen</a></li>
            <li class="monthLink<?php
		if ($actualMonth==9) {
			?> active<?php
		}
?>" month="9">Září</a></li>
            <li class="monthLink<?php
		if ($actualMonth==10) {
			?> active<?php
		}
?>" month="10">Říjen</a></li>
            <li class="monthLink<?php
		if ($actualMonth==11) {
			?> active<?php
		}
?>" month="11">Listopad</a></li>
            <li class="monthLink<?php
		if ($actualMonth==12) {
			?> active<?php
		}
?>" month="12">Prosinec</a></li>
          </ul>
        </div>       
        
          <!--<div id="progressContainer"></div>   
          -->
            
                <div class="container container-inner timetable" id="my-charged-records-table"> 
                    
<?php
		if (isset($flashes)) {
			?>                <?php
			if ($flashes != null) {
				?><br><?php
			}
?>

<?php
			$iterations = 0;
			foreach ($flashes as $flash) {
				?>                <div class="text-left alert alert-<?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 46 */ ?>">
                    <span>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?php
				if ($flash->type == "danger") {
?><strong>Chyba! </strong>
                        <?php
				}
				elseif ($flash->type == "warning") {
?><strong>Varování: </strong>
                        <?php
				}
				elseif ($flash->type == "info") {
?><strong>Info: </strong>
                        <?php
				}
				elseif ($flash->type == "success") {
?><strong>Úspěch: </strong>
<?php
				}
				?>                        <?php echo LR\Filters::escapeHtmlText($flash->message) /* line 54 */ ?>

                    </span>
                </div>
<?php
				$iterations++;
			}
		}
?>
            
                        <div class="row" id="my-charged-records-table-first">
                            <div class="col-lg-8 col-xl-8 col-md-8 col-sm-8 nopadding">
                                <div class="row">
                                    <div class="col-2 text-left font-weight-semibold nopadding">
                                        Datum
                                    </div>
                                    <div class="col-10 text-left font-weight-semibold nopadding">
                                        Projekt
                                    </div>
                                </div>
                            </div>

                             <div class="col-lg-4 col-xl-4 col-md-4 col-sm-4  nopadding text-right font-weight-semibold">
                                        Čas | Přesčas | Akce
                            </div>
                        </div>
                    </div>
            </div>
        
        <div class="col-12 col-lg-2 d-block d-lg-block text-left panel-right">
            <ul class="nav nav-pills">
                

                
                
            <li class="nav-pills-red" data-toggle="modal" data-target="#exampleModal"><a href="#">Předvyplnit</a></li>
            </ul>
            <ul class="nav nav-pills nav-pills-graph nopointer">
            <li class="nav-pills-graph nopointer">
                
            <div style="margin-left:5%; width:90%; position:relative; text-align: center; nopointer">
            
                <!--<br><a n:href="createTimesheet 2018, 12, 4, 0" target="_blank">Timesheet<i class="fas fa-download"></i></a> <br -->
                </span>
                <br>
                <div class ="graph-title">
                    
                    <div>
                      <p class = "right-block-p">  Souhrn za měsíc:</p> 
                    </div>                    
                    
                        <div id = "chargedProject">
                            
                        </div>
                                                            
                </div>                                   
                </div>
                
                
            </li>
            </ul>
        </div>
</div>

                
<!-- Modal for bulk charging -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Předvyplnit Timesheet</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <h5>Vyber projekt:</h5>   
            <div>
                      <select class="my-chargeable-projects" id="my-chargeable-projects-bulk"></select>   
            </div>
            <h5>Počet hodin:</h5>   
            <div>
                      <input type="number" step="0.5" min="0" max="24" value="8" class="my-chargeable-projects" id="my-hours-bulk"></select>   
            </div>
            
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
        <button type="button" class="btn btn-primary" id="preFillButton" data-dismiss="modal">Předvyplnit</button>
      </div>
    </div>
  </div>
</div>

                
</body>
  <script>
     
    $(document).ready(function() 
    {          
        var home_url = <?php echo LR\Filters::escapeJs($basePath) /* line 149 */ ?>;  
        var active_page = <?php echo LR\Filters::escapeJs($activePage) /* line 150 */ ?>;
        $.getScript(home_url+'/js/init_scripts.js', function()
        {
            initSharedFunctions(home_url, active_page);
        });
        
        function daysInMonth (month, year) {
            return new Date(year, month, 0).getDate();
        }        
       
    
        function createNewRow(obj){
            var selectHTML = $("#my-chargeable-projects").html(); 
            var abc='<tr id="'+obj["id"]+'">'+
                    '<td class="tiny rowPlus addRecord" recordId="'+obj["id"]+'"><i class="fas fa-plus-circle"></i></td>'+
                    '<td class="tiny rowDay">'+obj["day"]+'</td>'+
                    '<td class="tiny rowDayOfWeek">'+daysOfWeek[obj["day"]%7]+'</td>'+
                    '<td class="wide rowProjectName" recordId="'+obj["id"]+'"><select class="my-chargeable-projects-no-border" recordId="'+obj["id"]+'">'+selectHTML+'</select></td>'+
                    '<td class="tiny rowHours" recordId="'+obj["id"]+'"><input type="number" min=0 max=24 step=0.5 value="'+obj["hours"]+'" class="value"></input>' +
                    '<td class="tiny rowHoursOver" recordId="'+obj["id"]+'"><span class="value">'+obj["hours"]+'</span>' +
                    '<td class="tiny rowShare" recordId="'+obj["id"]+'"><i class="fas fa-share-alt"></i></td>'+
                    '<td class="tiny rowSave" recordId="'+obj["id"]+'"><i class="fas fa-save"></i></td>'+
                    '<td class="tiny rowTrash deleteRecord" recordId="'+obj["id"]+'"><i class="fas fa-trash-alt deleteRecord" recordId="'+obj["id"]+'"></i></td>'+
                '</tr>';
            return abc;
        }
    
   
       
    
        function setSVGActions(id){
            var obj = $(".rowPlus[recordid='"+id+"']");   
            setActionOnAddRecord(obj); 
            var obj2 = $(".rowTrash[recordid='"+id+"']");   
            setActionOnDeleteRecord(obj2);
        }
                 
        function updateRecord(recordId){      
            let hours = Number($(".rowHours[recordid='"+recordId+"']").children("input").val()); 
            let hoursOver = Number($(".rowHoursOver[recordid='"+recordId+"']").children("input").val());  
            let projectId = $(".rowProjectName[recordid='"+recordId+"']").children("select").val();  

            $.ajax(
            {
               type: 'GET',
               url: home_url+'/charge/change-record?recordId='+recordId+'&hours='+hours+'&hoursOver='+hoursOver+'&projectId='+projectId,
               dataType: 'json',
               cache: false,
               success: function(data)
                    { var json = $.parseJSON(data); 
                        if(json.result==='OK'){
                        }else{
                            alert(json.code);
                        }
                    }
            });

        }
        
        function setSelectOption(id, optionId){
            //$("select.my-chargeable-projects-no-border[recordid='"+id+"']").val(optionId); 
            $("select.my-chargeable-projects-no-border[recordid='"+id+"']").val(optionId); 
            $("select.my-chargeable-projects-no-border[recordid='"+id+"']").change(function (){
                updateRecord(id);
            });
            
            $(".rowHours[recordid='"+id+"']").focusout(function (){
                updateRecord(id);
            });
            
            $(".rowHoursOver[recordid='"+id+"']").focusout(function (){
                updateRecord(id);
            });
        }
             
        
        function setActionOnDeleteRecord(obj){
                            obj.click( function(){                                
                            var recordId = obj.attr('recordid');                  
                            $.ajax(
                            {
                               type: 'GET',
                               url: home_url+'/charge/delete-record?id='+recordId,
                               dataType: 'json',
                               cache: false,
                               success: function(data)
                                    { var json = $.parseJSON(data); 
                                        if(json.result==='OK'){ 
                                            //console.log($(".rowDay[recordid='"+recordId+"']").html());
                                            if($(".rowDay[recordid='"+recordId+"']").html()>0){
                                                let day=$(".rowDay[recordid='"+recordId+"']").html();
                                                let dayName=$(".rowDayOfWeek[recordid='"+recordId+"']").html();
                                                $("#"+recordId).replaceWith(createChargedRowBlank(day, day, dayName));
                                                setSVGActions(-day);
                                                setSelectOption(-day, -day);
                                                progress();
                                            } else {
                                            $("#"+recordId).remove();
                                            progress();
                                            }                                                 
                                        }else{
                                            alert(json.code);
                                        }
                                        projectCharged();
                                    }
                            });
                        });
        }

        function bulkRecors(projectId, hours){
            
            $.ajax(
                {

                   type: 'GET',
                   url: home_url+'/charge/bulk-records?hours='+hours+'&projectId='+projectId+'&month='+actualMonth+'&year='+actualYear,
                   dataType: 'json',
                   cache: false,
                   success: function(data)
                        {
                            deleteActualRecors();
                            var json = $.parseJSON(data); 
                            if(json.result==='OK'){
                               fillMeCalendar(actualMonth, actualYear);
                            }else{
                                alert(json.code);
                            }
                        }
                });
        }

        function setActionOnAddRecord(obj){
                        obj.click( function(){
                            var recordId = obj.attr('recordid');
                            var projectId = $("#my-chargeable-projects").val();
                            if(Number(recordId)>0){
                                $.ajax(
                                {

                                   type: 'GET',
                                   url: home_url+'/charge/create-record?id='+recordId+'&projectId='+projectId,
                                   dataType: 'json',
                                   cache: false,
                                   success: function(data)
                                        {
                                            var json = $.parseJSON(data); 
                                            if(json.result==='OK'){
                                                $("#"+recordId).after(createNewRowUnderDate(json.data));
                                                setSVGActions(json.data.id);
                                                setSelectOption(json.data.id, json.data.project_id);
                                                $("#"+json.data.id).prev('.row').css( "border-bottom", "0px solid #dee2e6" );
                                                $("#"+json.data.id).closest('.withL').css( "border-bottom", "1px solid #dee2e6" );

                                            }else{
                                                alert(json.code);
                                            }
                                        }
                                });
                            }else{
                                
                                var year = Number($("#yearPick").val());
                                var day = Number($(this).parent().children("span.rowDay").text());
                                let dayName =$(this).parent().children("span.rowDayOfWeek").text();
                                $.ajax(
                                {
                                    
                                   type: 'GET',
                                   url: home_url+'/charge/create-record-by-date?projectId='+projectId+'&month='+actualMonth+'&day='+day+'&year='+year,
                                   dataType: 'json',
                                   cache: false,
                                   success: function(data)
                                        {
                                            var json = $.parseJSON(data); 
                                            if(json.result==='OK'){
                                                $("#"+recordId).replaceWith(createChargedRow(json.data,day,dayName));
                                               // $("#"+recordId).remove();
                                                setSVGActions(json.data.id);
                                                setSelectOption(json.data.id, json.data.project_id);

                                            }else{
                                                alert(json.code);
                                            }
                                        }
                                });
                            }
                    });
        }

        function getMyProjects(){
            
            $.ajax(
            {
                type: 'GET',
                url: home_url+ '/charge/get-my-chargeable-projects',
                dataType: 'json',
                cache: false,
                success: function(data)
              { 
            
                var json = $.parseJSON(data);
                if(json.result == 'OK')
                {
                    let option_array = [];
                   for(i in json.data){
                       option_array.push(new Option(json.data[i].name,json.data[i].id));
                       var name = json.data[i].name;
                       if(name.length >=30){ name = name.substring(0,29)+'...';}
                       $("#my-chargeable-projects").append(new Option(name,json.data[i].id));
                       $("#my-chargeable-projects-bulk").append(new Option(name,json.data[i].id));
                   } 
                   //var abc = option_array;
                   //$("#my-chargeable-projects-bulk").html($("#my-chargeable-projects").html());
                   //alert($("#my-chargeable-projects-bulk").html());
                    fillMeCalendar(actualMonth, actualYear);
                }
                else {
                     alert(json.code);
                }
            }
            }
        )
        }
   
        function loadActualRecords(month, year){
                 $.ajax(
            {
              type: 'GET',
              url: home_url+'/charge/get-charge-record?month='+month+'&year='+year,
              dataType: 'json',
              cache: false,
              success: function(data)
              { 
                var json = $.parseJSON(data);
                if(json.result!=='OK'){
                    alert(json.code);
                }else{
                    var raw_data = json.data;
                    for (i in raw_data)
                    {
                      $("#my-charged-records-table").append(createNewRow(raw_data[i]));   
                      setSVGActions(raw_data[i].id);
                      setSelectOption(raw_data[i].id, raw_data[i].project_id);
                      //setSelectAction(raw_data[i].id);
                    }        
                }              
              }
            });  
        }
        
        function createChargedRow(obj, day, dayName){
            let selectHTML = $("#my-chargeable-projects").html();

            let row='<div class="nopadding row rows-intable withL" id="'+obj["id"]+'"><div class="col-md-12 col-lg-8 nopadding"><div class="row">'+
                    '<div class="nopadding col-2 col-xl-2 col-lg-2 col-md-2  col-sm-2  text-left font-weight-semibold" recordId="'+obj["id"]+'">'+
                    '<span class="nopadding font-weight-semibold rowPlus" recordId="'+obj["id"]+'"><i class="fas fa-plus-circle"></i></span> '+
                    '<span class="nopadding font-weight-semibold rowDay" recordId="'+obj["id"]+'">'+day+'</span> '+
                    '<span class="nopadding font-weight-semibold rowDayOfWeek" recordId="'+obj["id"]+'"">'+dayName+'</span> '+
                    '</div>'+
                    '<div class="nopadding col-10 col-xl-10 col-lg-10 col-md-10  col-sm-10  text-left font-weight-semibold rowProjectName wide" recordId="'+obj["id"]+'"><select class="my-chargeable-projects-no-border" recordId="'+obj["id"]+'">'+selectHTML+'</select></div>'+
                    '</div></div>'+
                    '<div class="nopadding col-md-12 col-lg-4"><div class="row float-right">'+
                    '<span class="nopadding font-weight-semibold rowHours" recordId="'+obj["id"]+'"><input type="number" class="input text-center" min=0 max=24 step=0.5 value="'+obj["hours"]+'" class="value"></input></span>'+
                    '<span class="nopadding font-weight-semibold rowHoursOver" recordId="'+obj["id"]+'"><input type="number" class="input text-center" min=0 max=16 step=0.5 value="'+obj["hours_over"]+'" class="value"></input></span>'+
                    //'<div class="nopadding col-lg-1 col-md-1  col-1 text-mid font-weight-semibold rowSave tiny" recordId="'+obj["id"]+'"><i class="fas fa-save"></i></div>'+
                    '<span class="nopadding font-weight-semibold rowTrash" recordid="'+obj["id"]+'"><i class="fas fa-trash-alt deleteRecord"></i></span>'+
                    '</div></div></div>';

                    return row;
            //$("#my-charged-records-table").append(row);                           
        }  

          function createChargedRowBlank(i, day, dayName){
            let selectHTML = $("#my-chargeable-projects").html();
            let row='<div class="nopadding row rows-intable withL" id="'+-i+'"><div class="col-md-12 col-lg-8 nopadding"><div class="row">'+
                    '<div class="nopadding col-2 col-xl-2 col-lg-2 col-md-2  col-sm-2  text-left font-weight-semibold" recordId="'+-i+'">'+
                    '<span class="nopadding font-weight-semibold rowPlus" recordId="'+-i+'"><i class="fas fa-plus-circle"></i></span> '+
                    '<span class="nopadding font-weight-semibold rowDay" recordId="'+-i+'">'+day+'</span> '+
                    '<span class="nopadding font-weight-semibold rowDayOfWeek" recordId="'+-i+'">'+dayName+'</span> '+
                    '</div>'+
                    '<div class="nopadding col-10 col-xl-10 col-lg-10 col-md-10  col-sm-10  text-leftfont-weight-semibold rowProjectName wide" recordId="'+-i+'"></div>'+
                    '</div></div>'+
                    '<div class="nopadding col-md-12 col-lg-4"><div class="row float-right">'+
                    '<span class="nopadding col-lg-3 col-md-3  col-3 text-mid font-weight-semibold rowHours tiny" recordId="'+-i+'"></span>'+
                    '<span class="nopadding col-lg-3 col-md-3  col-3 text-mid font-weight-semibold rowHoursOver tiny" recordId="'+-i+'"></span>'+
                    //'<div class="nopadding col-lg-1 col-md-1  col-1 text-mid font-weight-semibold rowSave tiny" recordId="'+-i+'"><i class="fas fa-save"></i></div>'+
                    '<span class="nopadding col-lg-2 col-md-2  col-2 text-right font-weight-semibold rowTrash tiny" recordid="'+-i+'"></span>'+
                    '</div></div></div>';
            //$("#my-charged-records-table").append(row); 
            return row;                          
        } 

            function createNewRowUnderDate(obj){
                let selectHTML = $("#my-chargeable-projects").html();
                let row='<div class="nopadding row rows-intable withoutL" id="'+obj["id"]+'"><div class="col-md-12 col-lg-8 nopadding"><div class="row">'+
                    '<div class="nopadding col-2 col-xl-2 col-lg-2 col-md-2  col-sm-2  text-left font-weight-semibold" recordId="'+obj["id"]+'">'+
                    '<span class="nopadding font-weight-semibold rowPlus tiny" recordId="'+-i+'"></span> '+
                    '<span class="nopadding font-weight-semibold rowDay" recordId="'+obj["id"]+'"></span> '+
                    '<span class="nopadding font-weight-semibold rowDayOfWeek" recordId="'+obj["id"]+'"></span> '+
                    '</div>'+
                    '<div class="nopadding col-10 col-xl-10 col-lg-10 col-md-10  col-sm-10  text-leftfont-weight-semibold rowProjectName wide" recordId="'+obj["id"]+'"><select class="my-chargeable-projects-no-border" recordId="'+obj["id"]+'">'+selectHTML+'</select></div>'+
                    '</div></div>'+
                    '<div class="nopadding col-md-12 col-lg-4"><div class="row float-right">'+
                    '<span class="nopadding font-weight-semibold rowHours" recordId="'+obj["id"]+'"><input type="number" class="input text-center" min=0 max=24 step=0.5 value="'+obj["hours"]+'" class="value"></input></span>'+
                    '<span class="nopadding font-weight-semibold rowHoursOver" recordId="'+obj["id"]+'"><input type="number" class="input text-center" min=0 max=16 step=0.5 value="'+obj["hours_over"]+'" class="value"></input></span>'+
                    //'<div class="nopadding col-lg-1 col-md-1  col-1 text-mid font-weight-semibold rowSave tiny" recordId="'+obj["id"]+'"><i class="fas fa-save"></i></div>'+
                    '<span class="nopadding font-weight-semibold rowTrash" recordid="'+obj["id"]+'"><i class="fas fa-trash-alt deleteRecord"></i></span>'+
                    '</div></div></div>';

                return row; 
                }
                             
                // Sum of totalHours
                function hoursSum(projectId){
                //function hoursSum(){
                let myArray1=[];
                    
                  $('.my-chargeable-projects-no-border').each(function()
                  {
                      var thisProject = $(this).val();
                      var recordId = $(this).attr("recordid");
                      var hoursAdd = Number($("span.rowHours[recordid='"+recordId+"']").children("input").val());
                      if(isNaN(hoursAdd)){ hoursAdd = 0;}  
                      var hoursOld = Number(myArray1[thisProject]);
                      if(isNaN(hoursOld)){ hoursOld = 0;}  
                      if(typeof hoursOld === 'undefined'){ hoursOld = 0;}
                      myArray1[thisProject] = Number(hoursOld + hoursAdd);
                      //return myArray1[projectId];
                  });
                                    
                  return (myArray1[projectId]);
                  
              }; 
             // Sum of totalHoursOver 
              function hoursSumOver(projectId){
                //function hoursSum(){
                let myArray1=[];
                    
                  $('.my-chargeable-projects-no-border').each(function()
                  {
                      var thisProject = $(this).val();
                      var recordId = $(this).attr("recordid");
                      var hoursAdd = Number($("span.rowHoursOver[recordid='"+recordId+"']").children("input").val());
                      if(isNaN(hoursAdd)){ hoursAdd = 0;}  
                      var hoursOld = Number(myArray1[thisProject]);
                      if(isNaN(hoursOld)){ hoursOld = 0;}  
                      if(typeof hoursOld === 'undefined'){ hoursOld = 0;}
                      myArray1[thisProject] = Number(hoursOld + hoursAdd);
                      //return myArray1[projectId];
                  });                 
                  
                  return (myArray1[projectId]);
                  
              }; 
              
                
                // Vypsani projektu a vypoctu na zmenu inputu hodin
                
                $('.timetable').on('input', function()
                {
                    projectCharged();
                });  
                            
                // vypsani projektu do praveho bloku
                
                function projectCharged(){  // vytvorim si array a dam do ni projekty, ktere jsou na dane strance
                                            var selected=[];
                                            let selectedId=[];
                                            $('.timetable .my-chargeable-projects-no-border option:selected').each(function(){
                                            selected[$(this).val()]=$(this).text();
                                            selectedId[$(this).val()]=$(this).val();
                                            });
                                            console.log(selected);
                                            
                                            // vyprazdnim si div, kam vypisuji projekty
                                            $('#chargedProject').empty();
                                            
                                            // vypisu projekty na stranku, zbavim se prazdnych hodnot
                                            for (var i = 0; i < selected.length; i++)
                                            {
                                                if (selected[i] !== undefined)
                                                {                                                
                                                $('#chargedProject').append('<ul class="right-block-li list-unstyled">'+selected[i]+'\n\
                                                                                          <li>Hodiny: <a id ="totalHours"'+i+'>'+hoursSum(i)+' '+'h</a></li>\n\
                                                                                          <li>Přesčas: <a id ="totalHoursOver">'+hoursSumOver(i)+' '+'h</a></li>\n\
                                                                                          <li>Faktura celkem: 1 000 Kč</li>\n\
                                                                                          <li><a href="'+home_url+'/charge/create-timesheet?year='+actualYear+'&amp;month='+actualMonth+'&amp;projectId='+selectedId[i]+'&amp;withPrices=0" target="_blank">Timesheet</a></li>\n\
                                                                                          <li><a href="'+home_url+'/charge/send-timesheet?year='+actualYear+'&amp;month='+actualMonth+'&amp;projectId='+selectedId[i]+'&amp;withPrices=0">Odeslat fakturu</a></li>\n\
                                                                                      </ul> </br>');
                                                }               
                                            }
                                        };
        
                
        //Posrane je to posrane
           

        function deleteActualRecors(){               
            $.each($("#my-charged-records-table-first").nextAll(), 
                    function (){ 
                        $(this).remove();}
                );

        }
        function fillMeCalendar(month, year){ 
            $.ajax(
                    {
                        type: 'GET',
                        url: home_url+ '/charge/get-charge-record?month='+month+'&year='+year,
                        dataType: 'json',
                        cache: false,
                        success: function(data)
                            { 
                                var json = $.parseJSON(data);
                                    if(json.result === 'OK')
                                        {  
                                        let days = daysInMonth(month, year); 
                                        let hrouda = 0;   
                                        deleteActualRecors();
                                        for(var i=1; i<=days;i++){
                                            var date = new Date(month+'/'+i+'/'+year);
                                            var dayName = daysOfWeek[date.getDay()];
                                            if(json.data.length!=hrouda && i==json.data[hrouda].day){
                                                $("#my-charged-records-table").append(createChargedRow(json.data[hrouda], i, dayName));
                                                setSVGActions(json.data[hrouda].id);
                                                setSelectOption(json.data[hrouda].id, json.data[hrouda].project_id); 
                                                hrouda++;
                                                
                                            }else{
                                                $("#my-charged-records-table").append(createChargedRowBlank(i, i, dayName));
                                                setSVGActions(-i);
                                                setSelectOption(-i, -i);     
                                            }
                                            while (json.data.length!=hrouda && i==json.data[hrouda].day){
                                                    $("#my-charged-records-table").append(createNewRowUnderDate(json.data[hrouda]));
                                                    setSVGActions(json.data[hrouda].id);
                                                    setSelectOption(json.data[hrouda].id, json.data[hrouda].project_id); 
                                                    $("#"+json.data[hrouda].id).prev('.row').css( "border-bottom", "0px solid #dee2e6" );
                                                    $("#"+json.data[hrouda].id).css( "border-bottom", "1px solid #dee2e6" );
                                                    hrouda++;
                                                }                                                                                      
                                            }
                                            projectCharged();                                     
                                            
                                            
                                                                                    }
                                    else {
                                            alert(json.code);
                                        }                                 
                            }
                    }
                )
        }     
      
        $("li.monthLink").click(function(){
            var newMonth = $(this).attr('month');
            fillMeCalendar(newMonth, actualYear);
            $('li.active').removeClass('active');
            $(this).addClass('active');
            actualMonth = newMonth;
        });
        
        $("#preFillButton").click(function (){
            projectId = $("#my-chargeable-projects-bulk").val();
            hours = $("#my-hours-bulk").val();
            bulkRecors(projectId, hours);         
            
        });

        
        var daysOfWeek = ["Ne","Po","Út","St","Čt","Pá","So"];
        var actualMonth = <?php echo LR\Filters::escapeJs($actualMonth) /* line 620 */ ?>;
        var actualYear = <?php echo LR\Filters::escapeJs($actualYear) /* line 621 */ ?>;
        var home_url = <?php echo LR\Filters::escapeJs($basePath) /* line 622 */ ?>;  
        getMyProjects();
        
        $("#yearPick").val(actualYear);
        $("#yearPick").change(function(){
            actualYear = $(this).val();
            fillMeCalendar(actualMonth, actualYear);
            //fillEmployeeChargesOverview(actualMonth, actualYear);
        });
        
              function progress()
        {
            
            $('<div id="progress" class="docasny"></div>').appendTo($('#progressContainer'));
            document.getElementById("progress").textContent = "Ukládám...";
                        
            var el = document.getElementById("progress");
            var width = 1;
            var id = setInterval(frame,3);
            
            function frame()
            {
                if (width>=100)
                {
                    clearInterval(id);                    
                    
                    document.getElementById("progress").textContent = "Uloženo...";
                    document.getElementById("progress").style.backgroundColor = "lightgreen";
                    
                   function deleteText()
                    {
                        $('.docasny').remove();                        
                    } 
                    setTimeout(deleteText,1000);
                   
                }
                else
                {
                    width++;
                    el.style.width = width + '%';
                }
            }
        } 
              
              
        }
        );

    </script>
    
<?php
	}


	function blockHead($_args)
	{
		extract($_args);
?>

<style>

    body {     
    font-family: 'Titillium Web', sans-serif;
    font-style: normal; 
    background: #f8f9fa;
    display: -ms-flexbox;
    -ms-flex-align: center;
    align-items: center;
    font-size:20px;
}

.right-block-p {
 
   color: #ff3333;
   width: 100%;
   font-size: 20px;
   text-align: center;
    
}

.right-block-li > li {
  background-color: #FFFFFF;
  margin-left: -1px;
  padding: 1px;
  flex-grow: 1;
  color: #20252D;
  font-weight: 600; 
  text-align: left;
  font-size: 14px;
  cursor: pointer;
}  

.withL{
    border-bottom: 1px solid #dee2e6;
}

.withoutL{
    border-bottom: 0px solid #dee2e6;
}

.input{
    border-radius:8px 8px 8px 8px;
    border-style: none;
    text-align: center;
}

.grayer{
    color: #dee2e6;
}

.chartjs-size-monitor{
    max-width: 80%;
}
    .btn-sheet{
     -webkit-margin-before: 1em;
     -webkit-margin-after: 1em;
}
    
.panel-left{
    background-color: #20252D;
    display: table-cell;
    float: none;
    /*padding-bottom: 100%;
    margin-bottom: -100%;*/
    padding-left: 0px;
    padding-right: 0px;
    /* min-width: 200px; */

}

.panel-mid{
    display: table-cell;
    float: none;
    padding-left: 15px;
    padding-right: 15px;
}
.panel-mid > div.container {
    max-width: 2000px;
}

.panel-right{
    display: table-cell;
    float: none;
    padding-left: 0px;
    padding-right: 0px;
    padding-top: 75px;
    background: #f8f9fa;

}

.panel-right > ul > li > span.graph-title{
    color: #212529;
    font-size: 22px;
    font-weight: 600;
    display:block;
}

.panel-left > h2.red{
    color: #D3155B;
    margin-top: 30px;
    font-size: 26px;
}

.panel-left > span.full-name{
    color: #FFFFFF;
    font-size: 22px;
    font-weight: 600;
    display:block;
}

.panel-left > span.job-title{
    color: #FFFFFF;
    margin-top: 0px;
    font-size: 16px;
    font-weight: 400;
    display:block;
}

.timetable{
    margin-top: 18px;
    background-color: #FFFFFF;
    border-radius: 5px;
}

.timetable-blank{
    margin-top: 20px;
    border-radius: 5px;
}

.table-hover > tbody > tr:first-child > td {
    font-weight: 400;
}
.table-hover > tbody > tr > td:first-child {
    font-weight: 700;
    font-size: 22px;
    text-align: right;
    width:10px;
    padding-right: 1px;
}
.table-hover > tbody > tr > td:first-child + td{
    width:10px;
    text-align: left;
    padding-left: 2px;
}
.tiny{
    width:10px;
    text-align: center;
}

.table-hover > tbody > tr > td.midi{
    width:60px;
    text-align: center;
}

.table-hover > tbody > tr > td.no-top-border{
    border-top: 0px solid black;
}

.wide{
    text-align: left;
}

.nav-pills-graph{
    margin-top: 10px;
}

.nav-pills > li {
  background-color: #FFFFFF;
  margin-left: -1px;
  padding: 5px;
  flex-grow: 1;
  color: #20252D;
  font-weight: 600;
  font-size: 18px;
  cursor: pointer;
}  

select#my-chargeable-projects-bulk, input#my-hours, input#my-hours-bulk {
    padding: 1px 1px 1px 5px;
    color: #333333;
    border-radius: 5px 5px 5px 5px;
    margin: 0px 0;
    box-sizing: border-box;
    border: 1px solid #d1d1d1;
    font-size: 16px;
    position:relative;
    width:90%;
} 

select#my-chargeable-projects {

}

input.my-hours-no-border{
    padding: 1px 1px 1px 5px;
    color: #333333;
    border-radius: 5px 5px 5px 5px;
    margin: 0px 0;
    box-sizing: border-box;
    border: 0px solid #d1d1d1;
    font-size: 20px;
    text-align: right;
    display:inline-block;
    width:60px;
}

select.my-chargeable-projects-no-border, select.my-chargeable-projects-bulk-no-border {
    padding: 1px 1px 1px 5px;
    color: #333333;
    border-radius: 5px 5px 5px 5px;
    margin: 0px 0;
    box-sizing: border-box;
    border: 0px solid #d1d1d1;
    font-size: 20px;
}

.graph-intitle{
    font-size: 60px;
    font-weight: 600;    
    margin-top: -48%;
    position: relative;
    width: 100%;
    text-align: center;
}

.graph-under-intitle{
    font-size: 18px;
    position: relative; 
    margin-top: -5%;
}

.fa-plus-circle {
    margin-left: 0px;
    margin-right: 0px;
    color: #EDEDED;

}

.fa-plus-circle:hover {
    margin-left: 0px;
    margin-right: 0px;
    color: #000;

}

.rows-intable:hover{
    background: #dee2e6;
}

svg {
    margin-left: 10px;
    margin-right: 10px;
    cursor: pointer;
}

.btn {
  font-size: 15pt;
}

#progressContainer
{
    width: 100%;
    background-color: #ddd;
}

#progress
{
    width: 0%;
    height: 25px;
    background-color: #D3155B;
}

.active-project-separator{
    display:none;
}

@media screen and (max-width: 1600px) {
        .graph-intitle{ font-size: 36px;}
        .graph-under-intitle{ font-size: 14px;}
        select.my-chargeable-projects-no-border, select.my-chargeable-projects-bulk-no-border, input { font-size: 16px;}
}

@media screen and (max-width: 1250px) {
        body{ font-size: 16px;}
        .nav-pills > li { padding: 4px; font-size: 14px;}
        .panel-left > h2.red{ font-size: 22px;}
        .panel-left > span.full-name{ font-size: 18px;}
        .panel-left > span.job-title{ font-size: 12px;}
        .table-hover > tbody > tr > td:first-child { font-size: 18px;}
        .graph-intitle{ font-size: 36px;}
        .graph-under-intitle{ font-size: 14px;}
        select.my-chargeable-projects-no-border, select.my-chargeable-projects-bulk-no-border, input{ font-size: 14px;}
        .nav-pills > li:first-child { margin-right: 15px; margin-left: 15px; border-radius: 5px; }
        .panel-right { display: table-cell; float: none; padding-left: 0px; padding-right: 0px; padding-top: 10px; background: #f8f9fa;}
}

@media screen and (max-width: 1000px) {
        body{ font-size: 14px;}
        .nav-pills > li { padding: 3px; font-size: 12px;}
        .panel-left > h2.red{ font-size: 20px;}
        .panel-left > span.full-name{ font-size: 16px;}
        .panel-left > span.job-title{ font-size: 10px;}
        .table-hover > tbody > tr > td:first-child { font-size: 16px;}
        .table th, .table td {  padding-left: 0rem; vertical-align: middle;}
        select.my-chargeable-projects-no-border, select.my-chargeable-projects-bulk-no-border, input { font-size: 12px;}
        .active-project-separator{ display:block ;}
}

</style>
<?php
	}

}
