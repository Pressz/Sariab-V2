<a href="../Statistics">Back</a>

<table class="table table-striped table-dark">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Referer</th>
      <th scope="col">Page</th>
      <th scope="col">Username</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0; foreach ($Data['UserStory'] as $Row) { $i++?>
    <tr>
      <td scope="row"><?php echo $Row['Submit']?></td>
      <td class="font-small"><?php echo $Row['HTTP_REFERER']?></td>
      <td class="font-small"><a class="btn btn-light btn-sm" href="<?php echo $Row['Uri']?>"><?php echo $Row['Uri']?></a></td>
      <td><?php echo $Row['PHP_AUTH_USER']?></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot">
    <tr>
      <td>Date</td>
      <td>Page</td>
    </tr>
  </tfoot>
</table>