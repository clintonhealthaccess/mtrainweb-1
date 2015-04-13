<div class="row">
    <div class="col-md-12">
        
    
    <!--pagetitle-->
    <div class="row">
        <div class="col-md-11 col-md-offset-1 marginbottom20 ">
            <h3 class="arialtitlebold">Settings</h3>
        </div>
    </div>
    
    
    <!--form-->
    <div class="row">
        <div class="col-md-10 col-md-offset-1 marginbottom20">
            
            <header class="containerheader"><h6>Cadres</h6></header>
            
            
            <section class="container">
                <article>
                    
                    <div class="row whiteframe">
                            <div id="cadreTableContainer" class="col-md-6 margintop10"></div>
                      </div>
                        
                        
                </article>
            </section>
        </div>
    
    </div>
</div>
</div>


<script type="text/javascript">
            /*
             * NOTE THAT ./cadre/ajaxList FORMAT OF ACCESSING THE METHODS HERE IS NOT 
             * SAYING THAT THE FILE IS INSIDE THE SAME FOLDER AS THIS VIEW BUT WE USE IT 
             * TO FOLLOW YII'S PATH NOMENCLATURE. YII WILL PREPEND THE BASE PATH TO THIS 
             * PATH TO FORM THE FULL PATH
            */
            
            //get permissions for the current user
            var permissions = <?php echo $permissions; ?>;
            
            //Prepare jTable
                //'http://localhost/yii/mtrain'
                $('#cadreTableContainer').jtable({
                        title: 'Cadre',
                        paging: true,
                        pageSize: 4,
                        //sorting: true,
                        columnSelectable: false,
                        selecting: true, //Enable selecting
                        multiselect: true, //Allow multiple selecting
                        //selectingCheckboxes: true, //Show checkboxes on first column
                        //selectOnRowClick: false, //Enable this to only select using checkboxes
                        defaultSorting: 'cadre_title ASC',
                        actions: {
//                                listAction: './cadre/ajaxList',
//                                createAction: './cadre/ajaxCreate',
//                                updateAction: './cadre/ajaxUpdate',
//                                deleteAction: './cadre/ajaxDelete'
                                
                                listAction: './cadre/ajaxList',
                                createAction: checkAccess('create_cadre', permissions) ? './cadre/ajaxCreate' : null,
                                updateAction: checkAccess('update_cadre', permissions) ? './cadre/ajaxUpdate' : null,
                                //deleteAction: checkAccess('manage_cadres', permissions) ? './cadre/ajaxDelete' : null
                        },
                        fields: {
                                cadre_id: {
                                        key: true,
                                        create: false,
                                        edit: false,
                                        list: false
                                },
                                cadre_title: {
                                        title: 'Cadre Name',
                                        width: '50%'
                                }
                        }
                });

                //Load cadre list from server
                $('#cadreTableContainer').jtable('load');

</script>