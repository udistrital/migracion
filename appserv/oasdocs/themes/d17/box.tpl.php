<div class="box">  
<div class="art-Block clear-block block block-<?php print $block->module ?>" id="block-<?php print $block->module .'-'. $block->delta; ?>">
      <div class="art-Block-body">
      
    <?php if ($title): ?>
<div class="art-BlockHeader">
          <div class="l"></div>
          <div class="r"></div>
          <div class="art-header-tag-icon">
              <div class="t">
        <?php echo $title; ?>
</div>
          </div>
      </div>
    <?php endif; ?>
<div class="art-BlockContent content">
        <div class="art-BlockContent-tl"></div>
        <div class="art-BlockContent-tr"></div>
        <div class="art-BlockContent-bl"></div>
        <div class="art-BlockContent-br"></div>
        <div class="art-BlockContent-tc"></div>
        <div class="art-BlockContent-bc"></div>
        <div class="art-BlockContent-cl"></div>
        <div class="art-BlockContent-cr"></div>
        <div class="art-BlockContent-cc"></div>
        <div class="art-BlockContent-body">
    
      <?php echo $content; ?>

        </div>
    </div>
        

      </div>
  </div>
      
</div>
