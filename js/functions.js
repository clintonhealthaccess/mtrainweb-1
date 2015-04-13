
function filterLoadLga(combo, lgaSelectId, depth){
    $('.loadingdiv').removeClass('hidden');
    var url = '';
    if(depth == '' || depth==0)
        url = './ajax/filterLoadLga';
    else
        url = '../ajax/filterLoadLga';
    
    var index = combo.selectedIndex;
    var select = document.getElementById(lgaSelectId);
    
    //console.log('index: ', index, 'select')
    
    $.ajax({
        type: 'POST',
        url: url,
        data: {stateid:index},
        dataType: 'json',
        success: function(lgas){
            
            if(select==null || select.options==null)
                return;
            else
                select.options.length = 0;
            
            //log('lgas: ' + lgas.length + ' type: ' + JSON.stringify(lgas));
            
            for(key in lgas){         
                if(isNaN(key)) return;
                var option = document.createElement("option");
                option.text = lgas[key];
                option.value = key;
                select.add(option);
            }
            
            //set the facility to just one (first) element to avoid inconsistency
            var facSelect = document.getElementById('facilityDropdown');
            if(facSelect != null) facSelect.options.length = 1;
            $('.loadingdiv').addClass('hidden');
        },
        error: function(){log('An error occurred.')},
        complete:function(){}
    });
}


function filterLoadFacility(combo, facilitySelectId, depth){
    $('.loadingdiv').removeClass('hidden');
    var url = '';
    if(depth == '' || depth == 0)
        url = './ajax/filterLoadFacility';
    else if(depth==1)
        url = '../ajax/filterLoadFacility';
    
    var index = $('#' + combo.id).val(); 
    var select = document.getElementById(facilitySelectId);
    
    log('index ' + index +' select ' + select);
    
    $.ajax({
        type: 'POST',
        url: url,
        data: {lgaid:index},
        dataType: 'json',
        success: function(facs){
            console.log('facs: ' + JSON.stringify(facs));
            if(select==null || select.options==null)
                return;
            else
                select.options.length = 0;
            
            for(key in facs){                
                if(isNaN(key)) return;
                var option = document.createElement("option");
                option.text = facs[key];
                option.value = key;
                select.add(option);
            }
            
            $('.loadingdiv').addClass('hidden');
        },
        error: function(){},
        complete:function(){}
    });
}

function createExcelFile(url, format ){
    var url = './' + url;    
    
    $('#dialog p').text('Your report is being generated. Please wait!');
    $('#dialog').dialog({modal:true});
    
    state = $('#stateDropdown').val();
    lga = $('#lgaDropdown').val();
    facility = $('#facilityDropdown').val();
    cadre = $('#cadreDropdown').val();
    
    log('createExcelFile - url: ' + url);
                     
    $.ajax({
        type: 'POST',
        url: url,
        dataType:'json',
        data: {state:state,lga:lga,facility:facility,cadre:cadre, format: format},
        success: function(resultObj){
            log('excelFileUrl: ' + JSON.stringify(resultObj)); 
            //return;
            if(resultObj.STATUS == 'ERROR'){
                $('#dialog p').text('An error occurred while generating report. Please try again later.');
                //USE THIS IN DEBUG MODE
                //$('#dialog p').text(resultObj['MESSAGE']);
            }
            else{
                $('#dialog p').text('Report successfully generated. Download will now begin');
                
                setTimeout(function(){
                    $('#dialog').dialog("close");
                    $('#dframe').attr('src',resultObj.URL);
                },2000);
            }
        },
        error: function(){},
        complete:function(){}
    });
}


function createDatedExcelFile(url, format ){ 
    var url = './' + url;
    
    $('#dialog p').text('Your report is being generated. Please wait!');
    $('#dialog').dialog({modal:true});
    
    channel = $('#channelDropdown').val();
    state = $('#stateDropdown').val();
    lga = $('#lgaDropdown').val();
    facility = $('#facilityDropdown').val();
    cadre = $('#cadreDropdown').val();
    fromdate = $('#from').val();
    todate = $('#to').val();
                     
    $.ajax({
        type: 'POST',
        url: url,
        dataType:'json',
        data: {channel:channel, state:state,lga:lga,facility:facility,cadre:cadre, format: format, fromdate: fromdate, todate: todate},
        success: function(resultObj){
            console.log('excelFileUrl: ' + resultObj);
            //console.log('excelFileUrl: ' + resultObj.STATUS);
            if(resultObj.STATUS == 'ERROR'){
                $('#dialog p').text('An error occurred while generating report. Please try again later.');
                //USE THIS IN DEBUG MODE
                //$('#dialog p').text(resultObj.MESSAGE);
            }
            else{
                $('#dialog p').text('Report successfully generated. Download will now begin');
                
                setTimeout(function(){
                    $('#dialog').dialog("close");
                    $('#dframe').attr('src',resultObj.URL);
                },2000);
            }
        },
        error: function(){},
        complete:function(){}
    });
}

 function checkAccess(operation, permissions){
    //log('permissions 2: ' + $('#container-row').data("permissions"));
    //log('operation: ' + operation);   

    for(key in permissions)
        if(permissions[key] == operation)
            return true;

    return null;
}


function loadStackedBarChart(){
    //log('loadStackedBarChart');
    //$('#dialog p').text('Loading...Please wait!');
    //$('#transparentdialog').dialog({modal:true});
    $('.loadingdiv').removeClass('hidden');
    
    var url = '';
    url = './site/filterStackedChart';
    
    state = $('#stateDropdown').val();
    lga = $('#lgaDropdown').val();
    facility = $('#facilityDropdown').val();
    cadre = $('input[name="cadreOption"]:checked').val();
    fromdate = $('#from').val();
    todate = $('#to').val()
    
    //log('state: ' + state + ' lga: ' + lga + ' facility ' + facility + ' cadre ' + cadre);
    //return;
    
    $.ajax({
        type: 'POST',
        url: url,
        data: {state:state, lga:lga, facility:facility, cadre:cadre, fromdate:fromdate, todate:todate},
        dataType: 'json',
        success: function(performanceItems){
            //log(JSON.stringify(performanceItems));
            //reloadChart(performanceItems);
            log('performanceItems: ' + performanceItems);
            
            drawChart(performanceItems);
            
            $('.loadingdiv').addClass('hidden');
        },
        error: function(e){
            log('An error occured loading chart data: ' + JSON.stringify(e));
        },
        complete:function(){
            
        }
    });

       
}


/*
 * Help to reload the google chart
 */
function reloadGoogleChart(performanceItems){
    var data = "['Year', 'Fantasy & Sci Fi', 'Romance', 'Mystery/Crime', 'General','Western', 'Literature', {type: 'string', role: 'tooltip'} ], " +
               "['Two thousand and ten', 10, 24, 20, 32, 18, 5, 'tttttt']," +
               "['Two thousand and twenty', 16, 22, 23, 30, 16, 9, 'wwwwww'],"  +
               "['Two thousand and thirty', 28, 19, 29, 30, 12, 13, 'hhhhh']";
     
      return data;      
}


function reloadChart(performanceItems){
       //log('reloadChart');
       var chart1 = new cfx.Chart();

       chart1.setGallery(cfx.Gallery.Bar); 
       chart1.getAllSeries().setStacked(cfx.Stacked.Normal);

/////////////////////////////////////////////////////////
//        performanceItems = [{
//                "Health Worker Performance" : "Bar 1", 
//                "High Performing" : 20,
//                "Average" : 10,
//                "Under Performing" : 20,
//                "Failing" : 10,
//                "No Data" : 40
//        },
//        {
//                "Health Worker Performance" : "Bar 2", 
//                "High Performing" : 40,
//                "Average" : 10,
//                "Under Performing" : 20,
//                "Failing" : 10,
//                "No Data" : 20
//        }
//    ];
    //XX% High Performing: Percent HCWs with median test score >80% 
/////////////////////////////////////////////////////////

    var items = performanceItems;
    //log('items: ' + JSON.stringify(items));
    chart1.setDataSource(items); 

    chart1.getAxisY().getLabelsFormat().setFormat(cfx.AxisFormat.Percentage);

    $('#div_obj-1').html('');

    chart1.create('div_obj-1');
}





//$(function(){
//    $(window).resize(function(){
//        var viewportWidth = $(window).width();
//        var viewportHeight = $(window).height();
//        var donut = document.getElementById('div_obj');
//        donut.style.width = donutWidth = Math.round(32 * viewportWidth / 100) + 'px';
//        donut.style.height = donutHeight = Math.round(58 * viewportHeight / 100) + 'px';
//        log('donutWidth: ' + donutWidth + ' donutHeight: ' + donutHeight + ' viewportWidth: ' + viewportWidth + ' viewportHeight ' + viewportHeight);
//        donut._ud(true);
//    });
//}); 




/*
 * UTILITY FUNCTIONS
 */
function log(msg){
    console.log(msg);
}

function removeElementById(id,callback){
    //$('#'+id).remove();
    if(callback==''){
        $('#'+id).fadeOut();
        //$('#'+id).remove();
    }
    else {
        $('#'+id).fadeOut(callback);
        //$('#'+id).remove();
    }
}

function removeElementByClass(classname, callback){
    //$('.'+classname).remove();
    $('.'+classname).fadeOut();
}