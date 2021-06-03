<?php

	if(isset($post) and !empty($post)){

	    $required = $this->postControl(['pageName','pageDesc','pageLink','pageShortcode','pageStatus','pageType','pageMenu','iconClass']);
		if($required === true){

			$pageAuth = $_POST['pageAuth']??[1];
			if(isset($_POST['pageAuth'])){
				$pageAuth = array_merge($pageAuth,["1"]);
			}else{
				$pageAuth = [1];
			}
			$pageAuth = json_encode($pageAuth);

			$askPage = $keybmin->db->query("SELECT * FROM kb_pages WHERE id = '".$pageID."'");
			if($askPage->num_rows > 0){

			    $pageDetail = $askPage->fetch_assoc();
				$addPage = $keybmin->db->query("UPDATE kb_pages SET 
                title='".$pageName."',
                description='".$pageDesc."',
                link='".$pageLink."',
                template='".$pageShortcode."',
                shortcode='".$pageShortcode."',
                status='".$pageStatus."',
                control='1',
                menu='".$pageMenu."',
                iconClass='".$iconClass."',
                type='".$pageType."',
                userAuth='".$pageAuth."',
                time='".time()."',
                parentID='".$parentPageID."' WHERE id = '".$pageID."'");
				if($addPage){

					$subIDs = $this->arrayValueLists($this->getSidebarMenuToArray($pageID),'id');
					foreach($subIDs as $pid){
					    $db->query("UPDATE kb_pages SET userAuth = '".$pageAuth."' WHERE id = '".$pid."'");
				    }

					if(file_exists($this->settings['THEMEDIR'].'pages/'.$pageDetail['template'].'.php')){
						rename($this->settings['THEMEDIR'].'pages/'.$pageDetail['template'].'.php', $this->settings['THEMEDIR'].'pages/'.$pageShortcode.'.php');
                    }

					$result = [
						'status' => 'success',
						'message' => $pageName._('Page Edit'),
					];
				}else{
					$result = [
						'status' => 'danger',
						'message' => _('Page Not Edit'),
					];
				}

			}else{
				$result = [
					'status' => 'danger',
					'message' => _('This page is not exists'),
				];
			}

		}else{
			$result = [
				'status' => 'danger',
				'message' => $required.' Parameter Problem',
			];
        }

	}

	if(isset($pageID) and !empty($pageID) and is_numeric($pageID)){

		$askPage = $keybmin->db->query("SELECT * FROM kb_pages WHERE id = '".$pageID."'");
		if($askPage->num_rows > 0){
		    $pageDetail = $askPage->fetch_assoc();
        }else{
			$result = [
				'status' => 'danger',
				'message' => _('This page is not exists'),
			];
        }

    }

	$fileName = pathinfo((__FILE__))['filename'];

	$csses = [
	];

	require $this->settings['THEMEDIR']."/header.php";
?>
    <div id="main">

        <div class="page-heading">

            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?=$keybmin->page_title?></h3>
                        <p class="text-subtitle text-muted"><?=$keybmin->page_desc?></p>
                    </div>
                </div>
            </div>

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
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="pageName" class="col-form-label"><?=_('Page Name')?></label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <button class="btn btn-dark icon-selector" data-icon="<?=$pageDetail['iconClass']?>" role="iconpicker"></button>
                                            <input type="hidden" name="iconClass" class="iconClass" value="<?=$pageDetail['iconClass']?>">
                                        </span>
                                        <input id="pageName" name="pageName" type="text" class="form-control pageName"  value="<?=$pageDetail['title']?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pageDesc" class="col-form-label"><?=_('Page Description')?></label>
                                    <input id="pageDesc" name="pageDesc" type="text" class="form-control" value="<?=$pageDetail['description']?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pageLink" class="col-form-label"><?=_('Page Link')?></label>
                                            <input id="pageLink" name="pageLink" type="text" class="form-control pageLink" value="<?=$pageDetail['link']?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pageShortcode" class="col-form-label"><?=_('Page Shortcode')?></label>
                                            <input id="pageShortcode" name="pageShortcode" type="text" class="form-control pageShortcode" value="<?=$pageDetail['shortcode']?>" readonly>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pageStatus" class="col-form-label"><?=_('Status')?></label>
                                            <select class="form-control" name="pageStatus" id="pageStatus" required>
                                                <option value="1" <?=$pageDetail['status']=='1'?'selected':''?>><?=_('Active')?></option>
                                                <option value="0" <?=$pageDetail['status']=='0'?'selected':''?>><?=_('Deactive')?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pageType" class="col-form-label"><?=_('Page Type')?></label>
                                            <select class="form-control" name="pageType" id="pageType">
                                                <option value="pages" <?=$pageDetail['type']=='pages'?'selected':''?>><?=_('Pages')?></option>
                                                <option value="ajax" <?=$pageDetail['type']=='ajax'?'selected':''?>><?=_('Ajax')?></option>
                                                <option value="keybmin" <?=$pageDetail['type']=='keybmin'?'selected':''?>><?=_('Keybmin')?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="pageMenu" class="col-form-label"><?=_('Do you look in the menu?')?></label>
                                            <select class="form-control" name="pageMenu" id="pageMenu">
                                                <option value="1" <?=$pageDetail['menu']=='1'?'selected':''?>><?=_('Yes')?></option>
                                                <option value="2" <?=$pageDetail['menu']=='2'?'selected':''?>><?=_('No')?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="parentPageID" class="col-form-label"><?=_('Parent Page')?></label>
                                    <select class="form-control parentPageID" name="parentPageID" id="parentPageID" required>
                                        <option value="0" data-pageshortcode=""><?=_('No Parent Page')?></option>
                                        <?php
                                            $allPage = $keybmin->get_page_lists();
                                            foreach($allPage as $page){
                                        ?>
                                        <option value="<?=$page['id']?>" data-pageshortcode="<?=$page['shortcode']?>" <?=$pageDetail['parentID']==$page['id']?'selected':''?>><?=$page['title']?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="pageAuth" class="col-form-label"><?=_('Choose Authorized?')?></label>
                                    <select class="form-control multiSelectBox" name="pageAuth[]" id="pageAuth" required multiple>
                                    <?php
                                        $allAuth = $this->getAuthList();
                                        foreach($allAuth as $auth){
                                    ?>
                                        <option value="<?=$auth['id']?>" <?=$auth['id']==1?'selected disabled':''?> <?=in_array($auth['id'], json_decode($pageDetail['userAuth']))?'selected':''?>><?=$auth['authName']?></option>
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