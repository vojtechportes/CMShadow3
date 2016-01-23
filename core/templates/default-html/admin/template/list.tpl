<div class="template-list full">
  <div class="clearfix"></div>
  <table class="table">
    <thead>
      <tr>
        <th>{_'templates_template_list_th_id'}</th>     
        <th>{_'templates_template_list_th_name'}</th>
        <th>{_'templates_template_list_th_createdAt'}</th>
        <th>{_'templates_template_list_th_modifiedAt'}</th>   
        <th class="text-right">{_'templates_template_list_th_actions'}</th>
      </tr>
    </thead>
    <tbody>
  <?php
  foreach ($return['templates'] as $id => $template) {
    ?>
    <tr <?php if ($template['Template']['NumPages'] > 0) { ?>class="has-pages"<?php } ?>>
		<td><?php echo $template['Template']['ID'] ?></td>
		<td><?php echo $template['Template']['Name'] ?></td>
		<td><?php echo $template['Template']['CreatedAt'] ?></td>
		<td><?php echo $template['Template']['ModifiedAt'] ?></td>
		<td class="text-right">
        	<a href="#" class="nolink" data-toggle="modal" data-target="#layoutDelete" data-arguments='{"templateId": "<?php echo $layout['ID']; ?>", "templateName": "<?php echo $layout['Name']; ?>"}' class="nolink" data-delete title="{_'templates_template_delete'}"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-cross"></span></a>
        	<a href="<?php echo ADMIN_PATH ?>templates/edit/<?php echo $layout['ID'] ?>" class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-in"></span></a>
		</td>
    </tr>
    <?php
    if ($template['Template']['NumPages'] > 0) {
    	?>
    	<tr>
    		<td colspan="5" class="inner-list">
    			<div>
	    			<div class="pages-container pull-right">
		    			<table class="table table-condensed table-transparent pull-right">
		    				<thead>				
		    					<tr>
							        <th>{_'templates_template_list_th_id'}</th>     
							        <th>{_'templates_template_list_th_name'}</th>
							        <th class="text-right">{_'templates_template_list_th_actions'}</th>
		    					</tr>
		    				</thead>
		    				<tbody>
		    					<?php
		    					foreach ($template['TemplatePages'] as $id => $page) {
		    					?>
		    					<tr>
		    						<td><?php echo $page['ID']; ?></td>
		    						<td><?php echo $page['Name']; ?></td>
									<td class="text-right">
										<a href="#" class="nolink" data-toggle="modal" data-target="#layoutDelete" data-arguments='{"pageId": "<?php echo $page['ID']; ?>", "pageName": "<?php echo $page['Name']; ?>"}' class="nolink" data-delete title="{_'templates_template_disconnect'}"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-cross"></span></a>
										<a href="<?php echo ADMIN_PATH ?>templates/pages/edit/<?php echo $page['ID'] ?>" class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-in"></span></a>
									</td>
		    					</tr>
		    					<?php
		    					}
		    					?>
		    				</tbody>
		    			</table>
	    			</div>
	    			<div class="clearfix"></div>
    			</div>
    		</td>
    	</tr>
    	<?php
    }
  }
  ?>
    </tbody>
  </table>
</div>