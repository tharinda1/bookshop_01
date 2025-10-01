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
    
    <?= form_open(); ?>
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name'); ?>">
        <span class="text-danger"><?= display_error($validation,'name') ?></span>
        <input type="submit" class="btn btn-primary" id="submit" name="save" value="Add">
        <input type="hidden" name="<?= csrf_token() ?>" id="csrf_token" value="<?= csrf_hash() ?>" />
    <?= form_close(); ?>
</div>
<hr/>

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
                    <tr data-author-id="<?= esc($author['id']) ?>">
                        <td><?= esc($author['id']) ?></td>
                        <td class="author-name-cell"><?= esc($author['name']) ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= esc($author['id']) ?>" data-name="<?= esc($author['name']) ?>">Edit</button>
                            <a href="<?= base_url('authors/delete/' . $author['id']) ?>" class="btn btn-danger btn-sm">Delete</a>
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
                
                </button>
            </div>
            <div class="modal-body">
                <form id="editAuthorForm">
                    <input type="hidden" id="authorId">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModalBtn">Close</button>
                <button type="button" class="btn btn-primary" id="updateAuthorBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Hide temporary messages after 3 seconds
    setTimeout(function() {
        $("#hidemsg").hide();
    }, 3000);

    // Show modal on edit button click
    $('.edit-btn').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        $('#authorId').val(id);
        $('#editName').val(name);
        $('#editAuthorModal').modal('show');
    });

    // Handle update button click inside the modal
    $('#updateAuthorBtn').on('click', function() {
        var id = $('#authorId').val();
        var newName = $('#editName').val();
        var csrfName = $('#csrf_token').attr('name');
        var csrfHash = $('#csrf_token').val();

        $.ajax({
            url: '<?= base_url('authors/update_inline/') ?>' + id,
            type: 'POST',
            data: {
                'name': newName,
                [csrfName]: csrfHash // Send the CSRF token
            },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    // Update the table row with the new name and hide modal
                    $('tr[data-author-id="' + id + '"]').find('.author-name-cell').text(newName);
                    $('#editAuthorModal').modal('hide');
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Log the full error to the console for detailed debugging
                console.log(xhr.responseText);
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Explicitly close the modal when the button is clicked
    $('#closeModalBtn').on('click', function() {
        $('#editAuthorModal').modal('hide');
    });
});
</script>

<?= $this->endSection(); ?>