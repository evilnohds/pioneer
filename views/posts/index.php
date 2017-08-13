<h4><?= $title ?></h4>
<?php $catlist = true; ?>
<div class="row">
<?php foreach($posts as $post) : ?>
	
	
	<br><br>
	<div class="row">
		<div class="col-md-1">
		
		</div>
		<div class="col-md-3">
		<h4><?php echo $post['title']; ?></h4>
			<img class="post-thumb" src="<?php echo site_url(); ?>assets/images/posts/<?php echo $post['post_image']; ?>">
		</div>
		<div class="col-md-4">
			<small class="post-date">Posted on: <?php echo $post['created_at']; ?> by <?php echo $post['username']; ?> in <strong><?php echo $post['name']; ?></strong></small><br>
		<?php echo word_limiter($post['body'], 60); ?>
		<br><br>
		<p><a class="btn btn-default" href="<?php echo site_url('/posts/'.$post['slug']); ?>">Read More</a></p>
		</div>
		<div class="col-md-3">
			 <?php if($catlist) : ?>
	              <ul class="list-group">
					<?php foreach($categories as $category) : ?>
						<li class="list-group-item"><a href="<?php echo site_url('/categories/posts/'.$category['id']); ?>"><?php echo $category['name']; ?></a>
							
						</li>
					<?php endforeach; ?>
					</ul>   
					<?php $catlist = false; ?>             
            <?php endif; ?>
		</div>
		<div class="col-md-1">
		
		</div>
	</div>
<?php endforeach; ?>
</div>
<div class="pagination-links">
		<?php echo $this->pagination->create_links(); ?>
</div>