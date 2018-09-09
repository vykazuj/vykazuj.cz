<?php
// source: C:\xampp\htdocs\vykazuj\app\presenters/templates/Charge/default.latte

use Latte\Runtime as LR;

class Template8710bd97ee extends Latte\Runtime\Template
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
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		$this->renderBlock('head', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['myChargeableProject'])) trigger_error('Variable $myChargeableProject overwritten in foreach on line 71');
		$this->parentName = "@login.latte";
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<body>

<div class="row">
    <div class="col-3 col-lg-2 text-center panel-left">
        <h2 class="h1 mb-4 font-weight-semibold red">Vykazuj.cz</h2>
        <img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 9 */ ?>/images/<?php
		if ($lastName == 'Haase') {
			?>drhaase<?php
		}
		elseif ($lastName == 'Lamaj') {
			?>jamal<?php
		}
		else {
			?>honza<?php
		}
?>.jpg" class="rounded-circle" alt="Cinque Terre">
        <span class="full-name"><?php echo LR\Filters::escapeHtmlText($firstName) /* line 10 */ ?> <?php
		echo LR\Filters::escapeHtmlText($lastName) /* line 10 */ ?></span>
        <span class="job-title"><?php
		if ($lastName == 'Haase' || $lastName == 'Lamaj') {
			?>Slave<?php
		}
		else {
			?>Jednatel<?php
		}
?></span>
        <div class="list-group">
            <a href="#" class="list-group-item active"><i class="far fa-clock"></i>Timesheety</a>
            <a href="#" class="list-group-item"><i class="fas fa-chart-line"></i>Statistiky</a>
            <a href="#" class="list-group-item"><i class="fas fa-cog"></i>Nastavení</a>
        </div>
    </div>
        
        
    <div class="col-9 col-lg-8 text-center panel-mid">
        <div class="container timetable-blank">
          <ul class="nav nav-pills">
            <li><a href="#">2018 / <?php echo LR\Filters::escapeHtmlText($activeMonth) /* line 23 */ ?></a></li>
            <li <?php
		if ($activeMonth==1) {
			?>class="active"<?php
		}
?>><a href="#">Leden</a></li>
            <li <?php
		if ($activeMonth==2) {
			?>class="active"<?php
		}
?>><a href="#">Únor</a></li>
            <li <?php
		if ($activeMonth==3) {
			?>class="active"<?php
		}
?>><a href="#">Březen</a></li>
            <li <?php
		if ($activeMonth==4) {
			?>class="active"<?php
		}
?>><a href=s#">Duben</a></li>
            <li <?php
		if ($activeMonth==5) {
			?>class="active"<?php
		}
?>><a href="#">Květen</a></li>
            <li <?php
		if ($activeMonth==6) {
			?>class="active"<?php
		}
?>><a href="#">Červen</a></li>
            <li <?php
		if ($activeMonth==7) {
			?>class="active"<?php
		}
?>><a href="#">Červenec</a></li>
            <li <?php
		if ($activeMonth==8) {
			?>class="active"<?php
		}
?>><a href="#">Srpen</a></li>
            <li <?php
		if ($activeMonth==9) {
			?>class="active"<?php
		}
?>><a href="#">Září</a></li>
            <li <?php
		if ($activeMonth==10) {
			?>class="active"<?php
		}
?>><a href="#">Říjen</a></li>
            <li <?php
		if ($activeMonth==11) {
			?>class="active"<?php
		}
?>><a href="#">Listopad</a></li>
            <li <?php
		if ($activeMonth==12) {
			?>class="active"<?php
		}
?>><a href="#">Prosinec</a></li>
          </ul>
        </div>
        
        <div class="container timetable-blank">
            <div class="container container-inner timetable">
                <table class="table table-hover timetable" id="my-charged-records-table">
                    <tbody>
                      <tr>
                        <th colspan="2">Datum</th>
                        <th class="text-left">Projekt</th>
                        <th>Čas</th>
                        <th>Přesčas</th>
                        <th colspan="3">Akce</th>
                      </tr>
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
        
        
        <div class="col-0 col-lg-2 d-none d-lg-block text-left panel-right">
            <ul class="nav nav-pills">
            <li class="nav-pills-red"><a href="#">Předvyplnit</a></li>
            </ul>
            <ul class="nav nav-pills nav-pills-graph">
            <li class="nav-pills-graph">
                
            <div style="margin-left:5%; width:90%; position:relative; text-align: center;">
                    <br>
                    <span class="graph-title">
                        Vyber projekt:<br>
<?php
		if (isset($myChargeableProjects)) {
?>
                    </span>    
                    <select class="my-chargeable-projects" id="my-chargeable-projects">
<?php
			$iterations = 0;
			foreach ($myChargeableProjects as $myChargeableProject) {
				?>                      <option value="<?php echo LR\Filters::escapeHtmlAttr($myChargeableProject->id) /* line 71 */ ?>" title="<?php
				echo LR\Filters::escapeHtmlAttr($myChargeableProject->name) /* line 71 */ ?>">
                          <?php
				if (strlen($myChargeableProject->name)>30) {
					?> <?php echo LR\Filters::escapeHtmlText(substr($myChargeableProject->name, 0, 27).'...') /* line 72 */ ?>

                          <?php
				}
				else {
					echo LR\Filters::escapeHtmlText($myChargeableProject->name) /* line 73 */ ?>

<?php
				}
?>
                      </option>
<?php
				$iterations++;
			}
?>
                    </select>   
                    <br>
                        
                  
                  
<?php
		}
?>
                </span>
                <span class="graph-title">
                <br>
                    Celkem   
                </span>
                <canvas id ="hours-chart" width="100">
                </canvas>
                <div class="graph-intitle">195<div class="graph-under-intitle">hodin</div></div>
                
                <br>
                <span class="graph-title">
                    Přesčas   
                </span>
                <canvas id ="hours-chart2" width="100">
                </canvas>
                <div class="graph-intitle">182<div class="graph-under-intitle">hodin</div></div>
                
                
                <br>
                <span class="graph-title">
                    Víkend   
                </span>
                <canvas id ="hours-chart3" width="100">
                </canvas>
                <div class="graph-intitle">100<div class="graph-under-intitle">hodin</div></div>
                <br>
                <br>
                <br>
                </div>
            </li>
            </ul>
        </div>
</div>

</body>
  <script>
     
    $(document).ready(function() 
    {   

        function createNewRow(obj){
            var selectHTML = $("#my-chargeable-projects").html();
            var abc='<tr id="'+obj["id"]+'">'+
                    '<td class="tiny rowPlus addRecord" recordId="'+obj["id"]+'"><i class="fas fa-plus-circle"></i></td>'+
                    '<td class="tiny rowDay">'+obj["day"]+'</td>'+
                    '<td class="tiny rowDayOfWeek">'+daysOfWeek[obj["day"]%7]+'</td>'+
                    '<td class="wide rowProjectName" recordId="'+obj["id"]+'"><select class="my-chargeable-projects-no-border" recordId="'+obj["id"]+'">'+selectHTML+'</select></td>'+
                    '<td class="tiny rowHours" recordId="'+obj["id"]+'"><span class="value">'+obj["hours"]+'</span>h ' +
                    '<td class="tiny rowHoursOver" recordId="'+obj["id"]+'"><span class="value">'+obj["hours"]+'</span>h ' +
                    '<td class="tiny rowShare" recordId="'+obj["id"]+'"><i class="fas fa-share-alt"></i></td>'+
                    '<td class="tiny rowPencil" recordId="'+obj["id"]+'"><i class="fas fa-pencil-alt"></i></td>'+
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

            hours = Number($("td.rowHours[recordid='"+recordId+"']").children("span.value").html()); 
            hoursOver = Number($("td.rowHoursOver[recordid='"+recordId+"']").children("span.value").html());  
            projectId = Number($("td.rowProjectName[recordid='"+recordId+"']").children("select").val());  
            //alert(recordId+' '+hours+' __  '+hoursOver+' __  '+projectId);

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
        }
             
        
        function setActionOnDeleteRecord(obj){
                            obj.click( function(){
                            var recordId = ($(this).children("svg").attr('recordid'));                        
                            $.ajax(
                            {
                               type: 'GET',
                               url: home_url+'/charge/delete-record?id='+recordId,
                               dataType: 'json',
                               cache: false,
                               success: function(data)
                                    { var json = $.parseJSON(data); 
                                        if(json.result==='OK'){
                                            $("tr#"+recordId).remove();
                                        }else{
                                            alert(json.code);
                                        }
                                    }
                            });
                        });
        }

        function setActionOnAddRecord(obj){
                            obj.click( function(){
                            var parent = obj.parent();
                            var recordId = obj.attr('recordid');
                            var projectId = $("#my-chargeable-projects").val();
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
                                            parent.after(createNewRow(json.data));
                                            setSVGActions(json.data.id);
                                            setSelectOption(json.data.id, json.data.project_id);
                                            
                                        }else{
                                            alert(json.code);
                                        }
                                    }
                            });

                    });
        }


        var daysOfWeek = ["Po","Út","St","Čt","Pá","So","Ne"];
        var actualMonth = <?php echo LR\Filters::escapeJs($actualMonth) /* line 230 */ ?>;
        var actualYear = <?php echo LR\Filters::escapeJs($actualYear) /* line 231 */ ?>;
        var home_url = <?php echo LR\Filters::escapeJs($basePath) /* line 232 */ ?>;
        //alert('Aktuální rok je: '+actualMonth+'/'+actualYear);
         $.ajax(
            {
              type: 'GET',
              url: home_url+'/charge/get-charge-record?month=1&abc=15&userId=5647',
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
            
                    
                     new Chart(
                document.getElementById("hours-chart"),
                {   "type":"doughnut",
                    "data":
                            {"labels":["Klasické hodiny","Přesčas","Víkend"],
                             "datasets":[{
                                  "label":"Hodin:",
                                  "data":[150, 10, 20],
                                  "backgroundColor":["#D3155B","#cccdce","E52428"]
                                 }]
                             },
                    "options":{
                            legend:{
                                "display": false
                            },
                            "cutoutPercentage":"90"
                    }
                }
            );
    

            new Chart(
                document.getElementById("hours-chart2"),
                {   "type":"doughnut",
                    "data":
                            {"labels":["Klasické hodiny","Přesčas","Víkend"],
                             "datasets":[{
                                  "label":"Hodin:",
                                  "data":[10, 30, 40],
                                  "backgroundColor":["#D3155B","#cccdce","E52428"]
                                 }]
                             },
                    "options":{
                            legend:{
                                "display": false
                            },
                            "cutoutPercentage":"90"
                    }
                }
            );
    

            new Chart(
                document.getElementById("hours-chart3"),
                {   "type":"doughnut",
                    "data":
                            {"labels":["Klasické hodiny","Přesčas","Víkend"],
                             "datasets":[{
                                  "label":"Hodin:",
                                  "data":[50, 40, 30],
                                  "backgroundColor":["#D3155B","#cccdce","E52428"]
                                 }]
                             },
                    "options":{
                            legend:{
                                "display": false
                            },
                            "cutoutPercentage":"90"
                    }
                }
            );
    
    
        });

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
    padding-top: 40px;
}
.panel-mid > div.container {
    max-width: 2000px;
}

.panel-right{
    display: table-cell;
    float: none;
    padding-left: 0px;
    padding-right: 0px;
    padding-top: 60px;
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
    margin-top: 20px;
    background-color: #FFFFFF;
    border-radius: 5px;
}

.timetable-blank{
    margin-top: 20px;
    border-radius: 5px;
}

.table-hover > tbody > tr:first-child > td {
    border: 0px solid black;
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
.table-hover > tbody > tr > td.tiny{
    width:10px;
    text-align: center;
}
.table-hover > tbody > tr > td.wide{
    text-align: left;
}

.nav-pills-graph{
    margin-top: 10px;
}

select#my-chargeable-projects {
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
select.my-chargeable-projects-no-border {
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

#my-charged-records-table > tbody > tr > td.addRecord >svg {
    visibility: hidden;
}

#my-charged-records-table > tbody > tr:hover > td.addRecord >svg {
    visibility: visible;
}

svg {
    margin-left: 10px;
    margin-right: 10px;
    cursor: pointer;
}

@media screen and (max-width: 1000px) {
        body{ font-size: 14px;}
        .nav-pills > li { padding: 3px;}
        .nav-pills > li > a { font-size: 12px;}
        .panel-left > h2.red{ font-size: 20px;}
        .panel-left > span.full-name{ font-size: 16px;}
        .panel-left > span.job-title{ font-size: 10px;}
        .table-hover > tbody > tr > td:first-child { font-size: 16px;}
        .table th, .table td {  padding-left: 0rem; vertical-align: middle;}
}

@media screen and (max-width: 1250px) {
        body{ font-size: 16px;}
        .nav-pills > li { padding: 4px;}
        .nav-pills > li > a { font-size: 14px;}
        .panel-left > h2.red{ font-size: 22px;}
        .panel-left > span.full-name{ font-size: 18px;}
        .panel-left > span.job-title{ font-size: 12px;}
        .table-hover > tbody > tr > td:first-child { font-size: 18px;}
        .graph-intitle{ font-size: 36px;}
        .graph-under-intitle{ font-size: 14px;}
}

@media screen and (max-width: 1600px) {
        .graph-intitle{ font-size: 36px;}
        .graph-under-intitle{ font-size: 14px;}
}

</style>
<?php
	}

}
