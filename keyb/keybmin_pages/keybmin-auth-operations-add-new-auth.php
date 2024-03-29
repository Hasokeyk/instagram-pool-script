<?php

    if(isset($post) and !empty($post)){

        if($this->postControl(['authName','authDesc']) === true){

            $askAuth = $keybmin->db->query("SELECT * FROM kb_auth WHERE authName = '".$authName."'");
            if($askAuth->num_rows == 0){

                $addAuth = $keybmin->db->query("INSERT INTO kb_auth SET 
                authName='".$authName."',
                authDesc='".$authDesc."',
                parentID='".($subAuth??'0')."'
               ");
                if($addAuth){
	                $result = [
		                'status' => 'success',
		                'message' => $authName._(' Auth Added'),
	                ];
                }else{
	                $result = [
		                'status' => 'danger',
		                'message' => _('Auth Not Add'),
	                ];
                }

            }else{
                $result = [
                    'status' => 'danger',
                    'message' => _('This Auth is exists'),
                ];
            }

        }

    }

	$fileName = pathinfo((__FILE__))['filename'];

	$csses = [
	];

	require $this->settings['THEMEDIR']."/header.php";
?>

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">

            <div class="page-title">
                <h1><?=$keybmin->page_title?></h1>
                <p><?=$keybmin->page_desc?></p>
            </div>
            <hr>

            <?php
                if(isset($result)){
            ?>
            <div class="alert alert-<?=$result['status']?> bg-<?=$result['status']?> text-white"><?=$result['message']?></div>
            <?php
                }
            ?>

            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <input type="hidden" name="post" value="true">

                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <label for="authName" class="col-form-label"><?=_('Auth Name')?></label>
                                    <input id="authName" name="authName" type="text" class="form-control authName" required>
                                </div>
                                <div class="form-group">
                                    <label for="authDesc" class="col-form-label"><?=_('Auth Description')?></label>
                                    <input id="authDesc" name="authDesc" type="text" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="subAuth" class="col-form-label"><?=_('Sub Auth')?></label>
                                    <select class="form-control" name="subAuth" id="subAuth" required>
                                        <option value="0">No Sub Auth</option>
                                    <?php
                                        $allAuth = $this->getAuthList();
                                        foreach($allAuth as $auth){
                                    ?>
                                        <option value="<?=$auth['id']?>"><?=$auth['authName']?></option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 text-right">
                                <button class="btn btn-primary"><?=_('Save')?></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
<?php
	require $this->settings['THEMEDIR']."/footer.php";
?>