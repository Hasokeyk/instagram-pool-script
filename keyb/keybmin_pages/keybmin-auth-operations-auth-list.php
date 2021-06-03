<?php

    if($this->getControl(['action','authID']) === true and $action == 'del' and is_numeric($authID)){

        $askPage = $mysqli->query("SELECT * FROM kb_auth WHERE id = '".$authID."'");
        if($askPage->num_rows > 0){

            $ai = $askPage->fetch_assoc();

            $del = $mysqli->query("DELETE FROM kb_auth WHERE id = '".$authID."'");
            if($del){
	            $result = [
		            'status' => 'success',
		            'message' => $ai['authName']._(' auth delete'),
	            ];
            }else{
	            $result = [
		            'status' => 'danger',
		            'message' => $ai['authName']._(' not auth delete'),
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
                <h5 class="card-header"><?=$keybmin->page_info['title']?> List</h5>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Auth Name</th>
                                <th scope="col">Auth Desc</th>
                                <th scope="col">Sub Auth</th>
                                <th scope="col">Events</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $allPages = $this->getAuthList();
                                foreach($allPages as $p){
                            ?>
                            <tr>
                                <th scope="row"><?=$p['id']?></th>
                                <td><?=$p['authName']?></td>
                                <td><?=$p['authDesc']?></td>
                                <td>
                                    <?php
                                        $subAuth = $keybmin::getAuthList($p['id']);
                                        if($subAuth == null){
                                            echo '<div class="text-danger">'._('No Sub Auth').'</div>';
                                        }else{
                                            foreach($subAuth as $auth){
                                                echo $auth['authName']."\n </br>";
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="?page=keybmin-auth-operations-auth-edit&authID=<?=$p['id']?>" class="btn btn-primary"><?=_('Edit')?></a>
                                    <a href="?page=<?=$page?>&action=del&authID=<?=$p['id']?>" class="btn btn-danger del-btn"><?=_('Del')?></a>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
	</div>
<?php
	require $this->settings['THEMEDIR']."/footer.php";
?>