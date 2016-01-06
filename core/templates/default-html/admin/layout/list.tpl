<div class="layout-list full">
  <div class="clearfix"></div>
  <table class="table">
    <thead>
      <tr>
        <th>{_'layouts_layout_list_th_id'}</th>     
        <th>{_'layouts_layout_list_th_name'}</th>
        <th>{_'layouts_layout_list_th_createdAt'}</th>
        <th>{_'layouts_layout_list_th_modifiedAt'}</th>   
        <th class="text-right">{_'layouts_layout_list_th_actions'}</th>
      </tr>
    </thead>
    <tbody>
  <?php
  foreach ($return['layouts'] as $id => $layout) {
    ?>
    <tr>
      <td><?php echo $layout['LayoutID'] ?></td>
      <td><?php echo $layout['LayoutName'] ?></td>
      <td><?php echo $layout['CreatedAt'] ?></td>
      <td><?php echo $layout['ModifiedAt'] ?></td>
      <td class="text-right"><a href="<?php echo ADMIN_PATH ?>layout/edit/<?php echo $layout['LayoutID'] ?>" class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-in"></span></a></td>
    </tr> 
    <?php
  }
  ?>
    </tbody>
  </table>
</div>