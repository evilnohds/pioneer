<h4><?= $title; ?></h4>

<?php echo validation_errors(); ?>

<?php echo form_open_multipart('posts/create'); ?>
  <div class="form-group">
    <label>Title</label>
    <input type="text" class="form-control" name="title" placeholder="Add Title">
  </div>
  <div class="form-group">
    <label>Body</label>
    <textarea id="editor1" class="form-control" name="body" placeholder="Add Body"></textarea>
  </div>
  <div class="form-group">
	  <label>Category</label>
	  <select name="category_id" class="form-control">

      <?php if($this->session->userdata('logged_in') and $this->session->userdata('access_lvl') == "Member") : ?>
              <option value="Members Page">Members Page</option>
      <?php endif; ?></p>
      <?php if($this->session->userdata('logged_in') and !($this->session->userdata('access_lvl') == "Member")) : ?>
              <?php foreach($categories as $category): ?>
                  <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
              <?php endforeach; ?>
      <?php endif; ?></p>		  

	  </select>
  </div>
  <div class="form-group">
	  <label>Upload Image</label>
	  <input type="file" name="userfile" size="20">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>