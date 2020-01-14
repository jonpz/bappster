<?php
$sections = new Sections();
$sectionActions = new SectionActions();
$as = false;
$ss = $sections->find(array('tag' => $section));
if ($ss) $as = $sectionActions->find(array('section_id' => $ss[0]['id'], 'hide_nav' => 0, 'order' => array('priority' => 'desc')));
$permitted = array();
foreach ($as as $row) if (in_array($row['id'], $_SESSION['perms'])) $permitted[] = $row['id'];
if ($permitted) :
  ?>
  <nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
      <?php foreach ($permitted as $id) : $sectionActions->get($id); ?>
        <li class="nav-item"><a class="nav-link" href="/admin/<?=$section?>/<?=$sectionActions->tag?>"><?=$sectionActions->name?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
<?php endif; ?>
