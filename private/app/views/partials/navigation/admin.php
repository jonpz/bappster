<?php
$sections = new Sections();
$sectionActions = new SectionActions();
$ss = $sections->find(array('hide_nav' => 0, 'order' => array('priority' => 'desc')));
?>
<ul class="nav nav-pills nav-stacked">
  <?php foreach ($ss as $row) :
    $sections->get($row['id']);
    $sas = $sectionActions->find(array('section_id' => $sections->id, 'tag' => 'read'));
    $perm = (!empty($sas[0]['id']) && in_array($sas[0]['id'], $_SESSION['perms']));
    if ($perm) :
      ?>
      <li class="nav-item"><a class="nav-link <?php if ($section == $sections->tag) echo 'active'; ?>" href="/admin/<?=$sections->tag?>"><?=$sections->name?></a></li>
    <?php endif; ?>
  <?php endforeach; ?>
  <li class="nav-item"><a class="nav-link" href="/logout">Log Out</a></li>
</ul>
