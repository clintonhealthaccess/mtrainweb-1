//$(document).ready(function(){
		    //Prepare jTable
var facilityGrid = {            
		table : function(){	$('#FacilityTableContainer').jtable({
				//title: 'Table of people',
				paging: true,
				pageSize: 4,
				sorting: true,
                                columnSelectable: false,
				defaultSorting: 'facility_name ASC',
                                pageSizeChangeArea: true,
				actions: {
                                    listAction: './healthFacility/ajaxList',
                                    createAction: './healthFacility/ajaxCreate',
                                    updateAction: './healthFacility/ajaxUpdate',
                                    deleteAction: './healthFacility/ajaxDelete'
				},
				fields: {
					facility_id: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					facility_name: {
						title: 'Facility Name',
						width: '20%'
                                                //inputClass: 'validate[required]'
					},
					facility_address: {
						title: 'Facility Address',
						width: '25%',
                                                type: 'textarea'
                                                //inputClass: 'validate[required]'
					},
                                        state_id: {
                                                title: 'State',
                                                width: '20%',
                                                options: 'healthFacility/getStatesList'
                                            },
					lga_id: {
						title: 'Local Government Area',
						width: '20%',
                                                //dependsOn: 'state_id',
                                                //options: {0:'-- Select LGA --',1:'Gbagada', 2:'Shomolu', 15:'Kosofe'}
                                                options: function(){
                                                    return 'healthFacility/getStatesList';
                                                }
                                                
                                        }
                                }
			});
                }

                        //Load person list from server
                        //$('#FacilityTableContainer').jtable('load');
//       });
}