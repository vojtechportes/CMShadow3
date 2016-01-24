<nav class="navigation navigation-inverse navigation-fixed-top">
    <div class="container-fluid">
        <div class="navigation-header">
            <button type="button" class="navigation-toggle navigation-toggle-always" data-toggle="navigation" data-target="#navigation-main" data-controls='{"closed": -300, "opened": 0, "align": "right", "container": "#body-inner", "navigationContainer": ".navigation"}'>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
			<a class="navigation-brand" href="<?php echo $return["brand"]["href"]; ?>">
				<?php
				if ($return["brand"]["image"] !== false) {
					echo '<img alt="'.$return["brand"]["text"].'" src="'.$return["brand"]["image"].'" border="0">';
				} else {
					echo $return["brand"]["text"];
				}
				?>
			</a>
        </div>
        
	    <div class="navigation-collapse full" id="navigation-main">
	        <div class="navigation-full"  data-action="fullHeight" data-parent="body">
				<div>
					<a class="btn btn-block btn-gray btn-lg" href="#"  data-toggle="navigation" data-target="#navigation-main" data-controls='{"closed": -300, "opened": 0, "align": "right", "container": "#body-inner", "navigationContainer": ".navigation"}'>{_'navigations_close'}</a>
				</div>
				<?php
				if ($return["search"] !== false) {
				?>
		    	<form role="search">
		        	<div class="form-group">
		        		<input type="text" class="form-control" placeholder="Search">
		        	</div>
		    	</form>
		    	<?php
		    	}
		    	?>
	            <ul class="nav navigation-nav">
	            	<?php
	            	foreach ($return["NavigationItems"] as $item) {
	            		echo '<li><a href="'.$item["Path"].'">'.$item["Title"].'</a></li>';
	            	}
	            	?>
	            </ul>
	        </div>
        </div>
    </div>
</nav>