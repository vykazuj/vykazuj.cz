
                
            function createMyCompaniesNewRow(data){
                var selected = '';
                if(data["pref_company"] !== null){ selected = 'selected'; }
                var row = '<option value="'+data['id']+'" '+selected+'>'+data['name']+'</option>';
                $('#company-select').append(row);
                if(data["pref_company"] !== null){ selected = 'selected'; $('#company-select').val(data['id']);}
            }            
            
            function mojeFunckce(){
                return 424242;
            }
            function actionGetMyChargableCompanies(home_url){     
                $.ajax(
                {
                   type: 'GET',
                   url: home_url+'/settings/get-my-chargable-companies',
                   dataType: 'json',
                   cache: false,
                   success: function(data)
                        {
                            var json = $.parseJSON(data); 
                            var raw_data = json.data;
                            if(json.result==='OK'){
                                for(i in raw_data){ 
                                    createMyCompaniesNewRow(raw_data[i]);
                                }
                            }else{
                                alert(json.code);
                            }
                        }      
                });
            }
            
            function changeActiveCompany(companyId, home_url, active_page){     
                $('.requests').remove();
                $.ajax(
                {
                   type: 'GET',
                   url: home_url+'/settings/change-active-company?companyId='+companyId,
                   dataType: 'json',
                   cache: false,
                   success: function(data)
                        {
                            var json = $.parseJSON(data); 
                            if(json.result==='OK'){
                            }else{
                                alert(json.code);
                            }
                            window.location.replace(home_url+'/'+active_page);
                        },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                }  
                });
            }
            
            function initSharedFunctions(home_url, active_page){
                
                actionGetMyChargableCompanies(home_url);
                $("#company-select").change(function(){
                    var companyId = $(this).val();
                    changeActiveCompany(companyId, home_url, active_page);
                });
            }
            
            
            
