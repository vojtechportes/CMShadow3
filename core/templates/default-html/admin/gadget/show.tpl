<div class="toolbar">
	<div class="btn-toolbar">
		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#gadgetList">
			<span class="glyphicon glyphicon-plus"></span>
		</button>
		<button type="button" class="btn btn-default">
			<span class="glyphicon glyphicon-cog"></span>
		</button>
	</div>
</div>
<div data-api-load='{"command": "gadgets", "action": "get", "type": "available", "message": false}'>
</div>

<div data-api-load='{"command": "loadModule", "message": false, "module": "admin/gadget/show"}'>
</div>

<div class="well well-lg">
	<p class="lead">{_'gadgets_add', sprintf(["#", "data-toggle=\"modal\" data-target=\"#gadgetList\""])}</p>
</div>

<div class="modal fade" id="gadgetList" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{_'gadgets_title'}</h4>
      </div>
      <div class="modal-body">
        <div class="list-group">
        <?php foreach ($return["GadgetsAvailable"] as $gadget) { ?>
			
			  <a href="#" class="list-group-item">
			    <h4 class="list-group-item-heading">{_'gadgets_<?php echo $gadget; ?>'}</h4>
			    <p class="list-group-item-text">{_'gadgets_<?php echo $gadget; ?>_description'}</p>
			  </a>
			
        <?php } ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>