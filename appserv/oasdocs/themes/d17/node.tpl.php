<div class="art-Post">
    <div class="art-Post-body">
<div class="art-Post-inner">
<h2 class="art-PostHeaderIcon-wrapper"> <span class="art-PostHeader"><a href="<?php echo $node_url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></span>
</h2>
<div class="art-PostMetadataHeader">
<?php if ($submitted): ?>
<div class="art-PostHeaderIcons art-metadata-icons">
<?php echo art_submitted_worker($submitted, $date, $name); ?>

</div>
<?php endif; ?>

</div>
<div class="art-PostContent">
<div class="art-article"><?php echo $content;?>
<?php if (isset($node->links['node_read_more'])) { echo '<div class="read_more">'.get_html_link_output($node->links['node_read_more']).'</div>'; }?></div>
</div>
<div class="cleared"></div>
<?php ob_start(); ?>
<?php if (is_art_links_set($node->links) || !empty($terms)): ?>
<div class="art-PostFooterIcons art-metadata-icons">
<?php if (!empty($links)) { echo art_links_woker($node->links);} ?>
<?php if (!empty($terms)) { echo art_terms_worker($node);} ?>

</div>
<?php endif; ?>
<?php $metadataContent = ob_get_clean(); ?>
<?php if (trim($metadataContent) != ''): ?>
<div class="art-PostMetadataFooter">
<?php echo $metadataContent; ?>

</div>
<?php endif; ?>

</div>

    </div>
</div>
