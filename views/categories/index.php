<h4><?= $title; ?></h4>
<ul class="list-group">
<?php foreach($categories as $category) : ?>
	<li class="list-group-item"><a href="<?php echo site_url('/categories/posts/'.$category['id']); ?>"><?php echo $category['name']; ?></a>
		<?php if($this->session->userdata('logged_in')) : ?>
			<?php if(($this->session->userdata('user_id') == $category['user_id']) or ($this->session->userdata('access_lvl') == "Super User")): ?>
				<form class="cat-delete" action="categories/delete/<?php echo $category['id']; ?>" method="POST">
					<input type="submit" class="btn-link text-danger" value="[X]">
				</form>
			<?php endif; ?>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>