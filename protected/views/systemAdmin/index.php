<div class="row" id="container-row" >
    <div class="col-md-12">
        
    
        <!--pagetitle-->
        <div class="row">
            <div class="col-md-11 col-md-offset-1 marginbottom20 ">
                <h3 class="arialtitlebold">Users</h3>
            </div>
        </div>


        <!--form-->
        <div class="row">
            <div class="col-md-10 col-md-offset-1 marginbottom20">

                <header class="containerheader"><h6>Administrators Information</h6></header>


                <section class="container">
                    <article>
                        <div class="row whiteframe">
                                <div id="adminTableContainer" class="col-md-12 margintop10"></div>
                          </div>                        
                    </article>
                </section>

            </div>
        </div>

    </div>
    
</div>


<script type="text/javascript">
        //$('#container-row').data("permissions",<?php //echo $permissions; ?>);
        //log('permissions 1: ' + permissions);
            
       //var permissions = $('#container-row').data("permissions");
       var permissions = <?php echo $permissions; ?>;
        
    //Prepare jTable
        $('#adminTableContainer').jtable({
                title: 'Administrators',
                paging: true,
                pageSize: 10,
                sorting: true,
                defaultSorting: 'username ASC',
                actions: {
                        listAction: './systemAdmin/ajaxList',
                        createAction: checkAccess('create_admin_user', permissions) ? './systemAdmin/ajaxCreate' : null,
                        updateAction: checkAccess('update_admin_user', permissions) ? './systemAdmin/ajaxUpdate' : null,
                        deleteAction: checkAccess('delete_admin_user', permissions) ? './systemAdmin/ajaxDelete' : null
                },
                fields: {
                        admin_id: {
                                key: true,
                                create: false,
                                edit: false,
                                list: false
                        },
                        firstname: {
                                title: 'First Name *'
                        },
                        middlename: {
                                title: 'Middle Name'
                        },
                        lastname: {
                                title: 'Last Name *'
                        },
                        gender: {
                                title: 'Gender *',
                                list: false,
                                options: ['-- Select Gender --','Male', 'Female']
                        },
                        email: {
                                title: 'Email'
                        },
                        phone: {
                                title: 'Phone *'
                        },
                        
                        role_id: {
                                title: 'Role *',
                                options: 'systemAdmin/getRolesList'
                                //list: false
                                
                                //options: ['-- Select Role --','LG Officer', 'State Officer', 'FMOH Officer']
                                //inputClass: 'validate[required]'
                        },
                        state_id: {
                                title: 'State *',
                                options: 'healthFacility/getStatesList'
                        },
                        lga_id: {
                                title: 'Local Govt. Area *',
                                dependsOn: 'state_id',
                                options: function(data){
                                    if(data.source == 'list'){
                                        return 'healthFacility/getLgaList';
                                    }
                                    return 'healthFacility/getLgaList?stateid=' + data.dependedValues.state_id;
                                }

                        },
                        username: {
                                title: 'Username *',
                                list: false
                        },
                        password: {
                                title: 'Password *',
                                type: 'password',
                                edit: false,
                                list: false
                        },
                        cpassword: {
                                title: 'Confirm Password *',
                                type: 'password',
                                edit: false,
                                list: false
                        }
                        
                }

        });
     
        
        
        
        
        //Load person list from server
        $('#adminTableContainer').jtable('load');
</script>

