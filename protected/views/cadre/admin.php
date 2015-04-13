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
                    
                    <div class="row noborder whitebg ">
                            <div id="cadreTableContainer" class="col-md-6 margintop10"></div>
                      </div>
                        
                        
                </article>
            </section>
        </div>
    
    </div>
</div>
</div>


<script type="text/javascript">
            //var absUrl = <?php echo "'$this->absUrl'"; ?>;
            //console.log('abs: ' + absUrl);
            
            //Prepare jTable
                //'http://localhost/yii/mtrain'
                $('#cadreTableContainer').jtable({
                        //title: 'Table of people',
                        paging: true,
                        pageSize: 4,
                        sorting: true,
                        defaultSorting: 'cadre_title ASC',
                        actions: {
                                listAction: 'http://localhost/yii/mtrain/cadre/ajaxList',
                                createAction: 'http://localhost/yii/mtrain/cadre/ajaxCreate',
                                updateAction: 'http://localhost/yii/mtrain/cadre/ajaxUpdate',
                                deleteAction: 'http://localhost/yii/mtrain/cadre/ajaxDelete'
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