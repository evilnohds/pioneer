<h4><?php echo $post['title']; ?></h4>
<small class="post-date">Posted on: <?php echo $post['created_at']; ?> by <?php echo $post['username']; ?></small><br>
<div style="height: 360px">
	<img src="<?php echo site_url(); ?>assets/images/posts/<?php echo $post['post_image']; ?>" style="height: 100%; width: 100%; object-fit: contain;">
</div>

<div class="post-body">
	<br><?php echo $post['body']; ?>
</div>

<?php if(($this->session->userdata('user_id') == $post['user_id']) or ($this->session->userdata('access_lvl') == "Super User")): ?>
	<hr>
	<a class="btn btn-default pull-left" href="<?php echo base_url(); ?>posts/edit/<?php echo $post['slug']; ?>">Edit</a>
	<?php echo form_open('/posts/delete/'.$post['id']); ?>
		<input type="submit" value="Delete" class="btn btn-danger">
	</form>
<?php endif; ?>
<hr>
<h4>Comments</h4>
<?php if($comments) : ?>
	<?php foreach($comments as $comment) : ?>
		<div class="well">
			<p><?php echo $comment['body']; ?> [by <strong><?php echo $comment['name']; ?></strong>] last <?php echo $comment['created_at']; ?></p>

			<?php if($this->session->userdata('logged_in')) : ?>
			<?php if(($this->session->userdata('user_id') == $comment['user_id']) or ($this->session->userdata('access_lvl') == "Super User")): ?>
				<?php echo form_open('/comments/delete/'.$comment['id']); ?>
			<input type="hidden" name="slug" value="<?php echo $post['slug']; ?>">
				<input type="submit" value="Del" class="btn btn-danger" style="font-size: 9px;">
			</form>
			<?php endif; ?>
		<?php endif; ?>
			
			
		</div>
	<?php endforeach; ?>
<?php else : ?>
	<p>No Comments To Display</p>
<?php endif; ?>
<hr>
<h4>Add Comment</h4>
<?php echo validation_errors(); ?>
<?php echo form_open('comments/create/'.$post['id']); ?>
	<div class="form-group">
		<label>Name</label>
		<input type="text" name="name" class="form-control">
	</div>
	<div class="form-group">
		<label>Email</label>
		<input type="text" name="email" class="form-control">
	</div>
	<div class="form-group">
		<label>Body</label>
		<textarea name="body" class="form-control"></textarea>
	</div>
	<input type="hidden" name="slug" value="<?php echo $post['slug']; ?>">
	<button class="btn btn-primary" type="submit">Submit</button>
</form>
