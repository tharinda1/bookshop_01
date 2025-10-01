<?php
$page_session = \Config\Services::session();
?>

<?php $validation = \Config\Services::validation(); ?>
<?php helper('form'); ?>


<?= $this->extend('layouts/base'); ?>



<?= $this->section('content'); ?>


<div class="container">
    <h1>Add Authors</h1>
    <p>This is the add authors page.</p>

    <!-- This will disappear within 3 seconds -->
    <script>
        setTimeout(function() {
            $("#hidemsg").hide();
        },3000);
    </script>

<?php if($page_session->getTempdata('success')):?>
    <div id="hidemsg" class="alert alert-success">
        <?=$page_session->getTempdata('success'); ?>
    </div>
<?php endif; ?>

<?php if($page_session->getTempdata('error')):?>
    <div id="hidemsg" class="alert alert-danger">
        <?=$page_session->getTempdata('error'); ?>
    </div>
<?php endif; ?>

    <?php if (isset($validation) && !empty($validation->getErrors())) : ?>
    <div class="alert alert-danger">
        <?=$validation->listErrors(); ?>
    </div>
<?php endif; ?>
    
    <div class="container">
    <?= form_open(); ?>
         <label class="name-label" for="name">Name</label>
            <input type="text" class="form-control name" id="name" name="name" value="<?= set_value('name'); ?>">
            <span class="text-danger"><?= display_error($validation,'name') ?></span>

         <input type="submit" class="btn btn-primary" id="submit" name="save" value="Add">   
    <?= form_close(); ?>
    </div>
</div>
    
    <!-- The list of authors -->
    <div class="container">
        <h2>Authors List</h2>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($authors) && is_array($authors)): ?>
                <?php foreach ($authors as $author): ?>
                    <tr>
                        <td><?= esc($author['id']) ?></td>
                        <td><?= esc($author['name']) ?></td>
                        <td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= esc($author['id']) ?>">Edit</button>
                                <a href="<?= base_url('authors/delete/' . $author['id']) ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </td>
                    </tr>
                <?php endforeach; ?>    
            <?php else: ?>
                <tr>
                    <td colspan="3">No authors found.</td>
                </tr>        
            <?php endif; ?>
        </tbody>
        </table>
    </div>
  
    <div class="modal fade" id="editAuthorModal" tabindex="-1" role="dialog" aria-labelledby="editAuthorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAuthorModalLabel">Edit Author</h5>
                </div>
                <div class="modal-body">
                    <form id="editAuthorForm">
                        <input type="hidden" name="<?= csrf_token() ?>" id="csrf_token" value="<?= csrf_hash() ?>" />
                         <input type="hidden" id="authorId">
                         <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" required>
                         </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateAuthorBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>




<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Show modal on edit button click
    $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).closest('tr').find('.author-name').text();
        
        $('#authorId').val(id);
        $('#editName').val(name);
        $('#editAuthorModal').modal('show');
    });

    // Handle update button click inside the modal
    $('#updateAuthorBtn').on('click', function() {
        var id = $('#authorId').val();
        var newName = $('#editName').val();

        // In your JavaScript
        var csrfName = $('meta[name="csrf-token-name"]').attr('content');
        var csrfHash = $('meta[name="csrf-token-hash"]').attr('content');
        console.log("test");
        $.ajax({
            url: '<?= base_url('authors/update_inline/') ?>' + id,
            type: 'POST',
            data: {
                'name': newName,
                [csrfName]: csrfHash // This adds the token to the data
            },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    // Update the table row with the new name
                    $('tr[data-author-id="' + id + '"]').find('.author-name').text(newName);
                    $('#editAuthorModal').modal('hide');
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred. Please try again.');
                console.log(xhr.responseText); // This is key for debugging
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>