<?php
// if testimonial is disable
if (!$config['testimonials_enable']) {
    error($lang['PAGE_NOT_FOUND'], __LINE__, __FILE__, 1);
}

if (checkloggedin()) {
    update_lastactive();
}

if (!isset($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$limit = 9;

$sql = "SELECT * FROM `" . $config['db']['pre'] . "privacy_policy`";

$total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM " . $config['db']['pre'] . "privacy_policy"));
$query = "$sql LIMIT " . ($page - 1) * $limit . ",$limit";

$result = ORM::for_table($config['db']['pre'] . 'privacy_policy')->raw_query($query)->find_many();

$privacy_policy = array();
if ($result) {
    foreach ($result as $row) {
        $privacy_policy[$row['id']]['id'] = $row['id'];
        $privacy_policy[$row['id']]['name'] = $row['name'];
        $privacy_policy[$row['id']]['designation'] = $row['designation'];
        $privacy_policy[$row['id']]['content'] = $row['content'];
        $privacy_policy[$row['id']]['image'] = !empty($row['image']) ? $row['image'] : 'default_user.png';
    }
}

$pagging = pagenav($total, $page, $limit, $link['LINK_PRIVACY_POLICY']);

$page = new HtmlTemplate('templates/' . $config['tpl_name'] . '/privacy_policy.tpl');
$page->SetParameter('OVERALL_HEADER', create_header($lang['LINK_PRIVACY_POLICY']));
$page->SetLoop('LINK_PRIVACY_POLICY', $privacy_policy);
$page->SetLoop('PAGES', $pagging);
$page->SetParameter('SHOW_PAGING', (int)($total > $limit));
$page->SetParameter('OVERALL_FOOTER', create_footer());
$page->CreatePageEcho();
